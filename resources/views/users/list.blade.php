@php use App\Http\Controllers\AuthController;use App\Models\RoleEnum;use Illuminate\Support\Facades\Session; @endphp
@extends('layouts.app')

@php
    /** @var $users \App\Models\User[] */
@endphp

@section('content')
    <h1>Liste des utilisateurs</h1>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nom</th>
            <th scope="col">Email de contact</th>
            <th scope="col">Role</th>
            <th scope="col">Etat</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <th scope="row">{{ $user->id }}</th>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>
                    @if($user->approval_by !== null)
                        <span class="badge bg-success">Approuvé</span>
                    @else
                        <span class="badge bg-warning text-dark">Non approuvé</span>
                    @endif
                </td>
                <td>
                    @if($user->approval_by !== null)
                        <a class="btn btn-warning"
                           href="{{ route('users.approve', ['id'=>$user->id, 'state'=>0]) }}"
                           role="button">
                            Désapprouver</a>
                    @else
                        <a class="btn btn-info" href="{{ route('users.approve', ['id'=>$user->id, 'state'=>1]) }}"
                           role="button">Approuver</a>
                    @endif
                    <a class="btn btn-danger" href="{{ route('users.delete', $user->id) }}" role="button">Supprimer</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

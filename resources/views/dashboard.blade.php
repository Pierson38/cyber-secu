@php use App\Http\Controllers\AuthController;use App\Models\RoleEnum;use Illuminate\Support\Facades\Session; @endphp
@extends('layouts.app')

@php
    /** @var $user \App\Models\User */
    /** @var $waitersCount int */
@endphp

@section('content')
    Bonjour {{ $user->name }} ! <br>
    <a href="{{ route('logout') }}">Cliquez ici pour vous déconnecter.</a>

    <p>
        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#profileInfos"
                aria-expanded="false" aria-controls="profileInfos">
            Voir vos informations de connexion
        </button>
    </p>
    <div class="collapse" id="profileInfos">
        <div class="card card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Email de contact</th>
                    <th scope="col">Role</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">{{ $user->id }}</th>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                </tr>
                </tbody>
            </table>
            <a href="#" class="btn btn-secondary" role="button" data-bs-toggle="collapse"
               data-bs-target="#profileEdit" aria-expanded="false" aria-controls="profileEdit">Changer mon
                profil</a>

            <div class="collapse" id="profileEdit">
                <form method="POST" action="{{ route('users.update') }}">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @csrf
                    <div class="mb-3">
                        <label for="access.name" class="form-label">Nom & prénom</label>
                        <input required name="name" type="text" class="form-control" id="access.name"
                               value="{{ $user->name }}">
                    </div>
                    <div class="mb-3">
                        <label for="access.password.current" class="form-label">Mot de passe actuel</label>
                        <input name="password" type="password" class="form-control"
                               id="access.password.current">
                    </div>
                    <div class="mb-3">
                        <label for="access.password.new" class="form-label">Nouveau mot de passe</label>
                        <input name="password-new" type="password" class="form-control"
                               id="access.password.new">
                    </div>
                    <div class="mb-3">
                        <label for="access.password.confirm" class="form-label">Confirmation</label>
                        <input name="password-confirm" type="password" class="form-control"
                               id="access.password.confirm">
                    </div>
                    <label for="user-picture" class="form-label">Image de profil (expérimental, nécessite d'avoir son
                        profil Teams à jour). Insérer votre nom d'utilisateur Teams avec .jpeg après.</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="randomid">{{ route('picture-get') }}/</span>
                        <input name="user-picture" type="text" class="form-control" id="user-picture"
                               aria-describedby="randomid" value="{{ $user->picture }}">
                    </div>


                    <button type="submit" class="btn btn-primary">Valider</button>
                </form>
            </div>
        </div>
    </div>

    @if($user->isUserApproved())
        @if($user->role === RoleEnum::ADMINISTRATEUR->name)
            @if($waitersCount > 0)
                <div class="alert alert-info" role="alert">
                    Il y a actuellement {{ $waitersCount }} personne(s) en attente de validation,
                    <a href="{{ route('users.list') }}">cliquez ici</a> pour les visualiser.
                </div>
            @endif

        @endif
    @else
        <div class="alert alert-warning" role="alert">
            Votre compte n'a pas encore été approuvé. Vous ne pouvez donc pas consulter la liste des clients.
            <!-- TODO : Faire la liste des clients (/clients/)-->
        </div>
    @endif
@endsection

@php use App\Models\RoleEnum; @endphp
@extends('layouts.app')

@php
    /** @var $orphans_clients \App\Models\Client[]|\Illuminate\Support\Collection */
    /** @var $user_clients \App\Models\Client[]|\Illuminate\Support\Collection */
    /** @var $user \App\Models\User */
@endphp

@section('content')
    <h1>Liste des clients</h1>
    Seuls vos clients et les clients sans référent EntrePromneurs apparaissent dans cette liste

    <p>
        <a class="btn btn-success" data-bs-toggle="collapse" href="#createClientForm" role="button"
           aria-expanded="false"
           aria-controls="createClientForm">
            Ajouter un client
        </a>
    </p>
    <h2>Recherche</h2>
    <form action="#" method="GET">
        <div class="row g-3 mb-3">
            <div class="col">
                <input name="q" value="{{ old('q', $_GET['q'] ?? '') }}" type="text" class="form-control"
                       placeholder="Chercher un client">
            </div>
            <div class="col-sm-3">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </div>
        </div>

    </form>
    @error('name')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    @error('address')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    @error('contact_info')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    <div class="collapse" id="createClientForm">
        <div class="card card-body">
            <form method="POST" action="{{ route('clients.create') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nom du client</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                </div>
                <div class="mb-3">
                    <label for="address">Adresse</label>
                    <textarea class="form-control" placeholder="N°, rue, code postal, ville, etc..."
                              id="address" style="height: 100px" name="address">{{ old('address') }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="contact_info">Information de contact</label>
                    <textarea class="form-control" placeholder="Nom contact, numéro de téléphone, adresse mail, etc..."
                              id="contact_info" style="height: 100px"
                              name="contact_info">{{ old('contact_info') }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="assignee_id" class="form-label">ID utilisateur référent</label>
                    <input type="number" class="form-control" id="assignee_id" name="assignee_id"
                           @if($user->role !== RoleEnum::ADMINISTRATEUR->name) disabled="disabled" @endif
                           aria-describedby="assignee_id_help" value="{{ old('assignee_id', $user->id) }}">
                    <div id="assignee_id_help"
                         class="form-text">@if($user->role === RoleEnum::ADMINISTRATEUR->name)
                            Vous pouvez préciser un autre utilisateur référent au besoin
                        @else
                            En tant que "{{ $user->role }}", le client vous sera automatiquement attribué
                        @endif</div>
                </div>
                <button type="submit" class="btn btn-primary">Créer le contact</button>
            </form>
        </div>
    </div>

    @if($orphans_clients->isEmpty())
        <div class="alert alert-primary" role="alert">
            Aucun client n'a aucun référent ! Bien joué à vous l'équipe.
        </div>
    @else
        <h2>Clients sans référent</h2>
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nom</th>
                <th scope="col">Adresse</th>
                <th scope="col">Infos de contact</th>
                <th scope="col">Création de la fiche</th>
                <th scope="col">S'attribuer le client</th>
            </tr>
            </thead>
            <tbody>
            @foreach($orphans_clients as $orphan_client)
                <tr>
                    <th scope="row">{{ $orphan_client->id }}</th>
                    <td>{{ $orphan_client->name }}</td>
                    <td>{{ $orphan_client->address }}</td>
                    <td>{!! $orphan_client->contact_info !!}</td>
                    <td>{{ $orphan_client->created_at }}</td>
                    <td>
                        <a class="btn btn-info" href="{{ route('clients.assign', $orphan_client->id) }}" role="button">
                            Devenir référent
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    <h2>Vos clients attribués ({{ $user_clients->count() }} client(s) attribué(s))</h2>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nom</th>
            <th scope="col">Adresse</th>
            <th scope="col">Infos de contact</th>
            <th scope="col">Création de la fiche</th>
        </tr>
        </thead>
        <tbody>
        @foreach($user_clients as $client)
            <tr>
                <th scope="row">{{ $client->id }}</th>
                <td>{{ $client->name }}</td>
                <td>{{ $client->address }}</td>
                <td>{!! $client->contact_info !!}</td>
                <td>{{ $client->created_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="alert alert-secondary" role="alert">
        Actuellement, il n'est pas possible via le CRM de changer le référent d'un client ou de modifier les
        informations merci de contacter le créateur de l'application via Teams si vous souhaitez effectuer un changement
    </div>
@endsection

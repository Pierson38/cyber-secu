@php use App\Http\Controllers\AuthController;use Illuminate\Support\Facades\Session; @endphp
@extends('layouts.app')

@section('content')

    @if(AuthController::isLoggedIn())
        Vous êtes déjà connecté :-) <br>
        <a href="{{ route('logout') }}">Cliquez ici pour vous déconnecter.</a>
        <br>
        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg" role="button">
            Accéder au dashboard
        </a>
    @else
        <form method="POST" action="{{ route('login-attempt') }}">
            <h1>Se connecter au CRM</h1>
            @if(Session::has('connexion.error'))
                <div class="alert alert-danger">
                    <ul>
                        <li>{!! \Session::get('connexion.error') !!}</li>
                    </ul>
                </div>
            @endif

            @error('email')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('password')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @csrf
            <div class="mb-3">
                <label for="login.email" class="form-label">Adresse email</label>
                <input required name="email" type="email" class="form-control" id="login.email"
                       aria-describedby="login.email.help">
                <div id="login.email.help" class="form-text">Ex: nom.prenom@gmail.com</div>
            </div>
            <div class="mb-3">
                <label for="login.password" class="form-label">Mot de passe</label>
                <input required name="password" type="password" class="form-control" id="login.password">
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>

        <form method="POST" action="{{ route('access-request') }}">
            <h1>Demander un accès au CRM</h1>
            @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('role')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @csrf
            <div class="mb-3">
                <label for="access.name" class="form-label">Nom & prénom</label>
                <input required name="name" type="text" class="form-control" id="access.name">
            </div>
            <div class="mb-3">
                <label for="access.email" class="form-label">Adresse email</label>
                <input required name="email" type="email" class="form-control" id="access.email"
                       aria-describedby="access.email.help">
                <div id="access.email.help" class="form-text">Ex: nom.prenom@gmail.com</div>
            </div>
            <div class="mb-3">
                <label for="access.password" class="form-label">Mot de passe</label>
                <input required name="password" type="password" class="form-control" id="access.password">
            </div>
            <div class="mb-3">
                <label for="access.role" class="form-label">Role dans l'entreprise</label>
                <select required name="role" class="form-select" id="access.role">
                    <option value="COMMERCIAL">Commercial</option>
                    <option value="CONSULTANT">Consultant</option>
                    <option value="TECHNICIEN">Technicien</option>
                    @if(App::environment() === "local")
                        <option value="ADMINISTRATEUR">Administrateur</option>
                    @endif
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Demander un accès</button>
        </form>
    @endif

@endsection

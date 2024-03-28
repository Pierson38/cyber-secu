@php use App\Http\Controllers\AuthController; use App\Models\RoleEnum;@endphp
@php
    /** @var $user \App\Models\User|null */
@endphp
<div class="container">
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
        <a href="{{ route('dashboard') }}"
           class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
            <span class="fs-4">Customer Relation Manager</span>
            <small>v 5.3</small>
        </a>
        <ul class="nav nav-pills">
            @if(AuthController::isLoggedIn() && isset($user))
                @if($user->isUserApproved())
                    @if($user->role === RoleEnum::ADMINISTRATEUR->name)
                        <li class="nav-item"><a href="{{ route('users.list') }}" class="nav-link">Utilisateurs</a></li>
                    @endif
                    <li class="nav-item"><a href="{{ route('clients.list') }}" class="nav-link">Clients</a></li>
                @endif

                <li class="nav-item"><a href="{{ route('logout') }}" class="nav-link">Se
                        déconnecter</a>
                </li>

                <li class="nav-item">
                    <img src="{{ route('picture-get', $user->picture) }}" alt="mdo" width="32" height="32"
                         class="rounded-circle">
                </li>
            @else
                <li class="nav-item"><a href="{{ route('login') }}" class="nav-link active" aria-current="page">Se
                        connecter</a></li>
            @endif
            {{--        <li class="nav-item"><a href="#" class="nav-link">Features</a></li>--}}
            {{--        <li class="nav-item"><a href="#" class="nav-link">Pricing</a></li>--}}
            {{--        <li class="nav-item"><a href="#" class="nav-link">FAQs</a></li>--}}
            {{--        <li class="nav-item"><a href="#" class="nav-link">About</a></li>--}}
        </ul>
    </header>
    @if(Session::has('connexion.success'))
        <div class="alert alert-success">
            <ul>
                <li>{!! \Session::get('connexion.success') !!}</li>
            </ul>
        </div>
    @endif
    @if(Session::has('connexion.error'))
        <div class="alert alert-danger">
            <ul>
                <li>{!! \Session::get('connexion.error') !!}</li>
            </ul>
        </div>
    @endif
</div>


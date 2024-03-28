@extends('layouts.app')

@section('content')
    <h1>Approbation du premier administrateur</h1>
    <form method="POST" action="{{ route('approval-process') }}">
        @csrf
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Adresse mail admin :</label>
            <input name="email" type="email" class="form-control" id="exampleInputEmail1">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Mot de passe de sécurité</label>
            <input name="password" type="password" class="form-control" id="exampleInputPassword1">
        </div>
        <button type="submit" class="btn btn-primary">Valider</button>
    </form>
@endsection

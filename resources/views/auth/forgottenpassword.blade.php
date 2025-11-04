@extends('layouts.authlayout')

@section('title', 'Mot de passe oublié')

@section('content')
    <div class="auth-form-cover">

        <form action="{{ route('auth.forgottenpassword') }}" method="post">
            @csrf
            <h1 class="text-center">Mot de passe oublié ?</h1>

            <br />
            <p class="text-center">
                Remplissez l'adresse e-mail qui a été utilisé pour la création de votre compte.
            </p>
            <br />

            @if ($errors->any())
                <ul class="form-errors-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <br />
            @endif

            <div class="input-cover">
                <label for="email">E-mail de l'utilisateur</label>
                <input type="text" id="email" name="email" value="{{ old('email') }}" autocomplete="off"
                    placeholder="saisir votre e-mail ici ..." />
            </div>

            <button type="submit" class="button button-primary full-width">
                Soumettre
            </button>
            <br /><br />
            <div class="text-center">
                Déjà membre ?
                <a href="{{ route('login') }}">
                    Se connecter
                </a>
            </div>
        </form>
    </div>

@endsection

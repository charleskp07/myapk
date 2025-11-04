@extends('layouts.authlayout')

@section('title', 'Connexion')

@section('content')

    <div class="auth-form-cover">

        <form action="{{ route('auth.login') }}" method="post">
            @csrf

            <h1 class="text-center">Se connecter</h1>
            <br />
            <p class="text-center">
                Remplir vos paramètres de compte pour vous connecter à votre compte.
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

            @if ($message = Session::get('success'))
                <p>{{ $message }}</p>
            @endif

            <div class="input-cover">
                <label for="email">E-mail de l'utilisateur</label>
                <input type="text" id="email" value="{{ old('email') }}" name="email" autocomplete="off"
                    placeholder="Saisir l'e-mail ici ..." />
            </div>

            <div class="input-cover">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Saisir le mot de passe ici ..." />
            </div>

            <div class="text-center">
                <a href="{{ route('auth.forgottenpassword') }}">
                    Mot de passe oublié ?
                </a>
            </div>
            <br />
            <button type="submit" class="button button-primary full-width">
                Se connecter
            </button>
        </form>

    </div>

@endsection

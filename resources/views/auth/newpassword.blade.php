@extends('layouts.authlayout')

@section('title', 'Nouveau mot de passe')

@section('content')
    <div class="auth-form-cover">
        <form action="{{ route('auth.newpassword') }}" method="post">
            @csrf
            <h1 class="text-center">Nouveau mot passe</h1>

            <br />
            <p class="text-center">
                Saisir votre nouveau mot de passe puis Confirmez-le dans les champs.
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
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Saisir le mot de passe ici ...">
            </div>

            <div class="input-cover">
                <label for="password-confirmation">Confirmer le mot de passe</label>
                <input type="password" id="password-confirmation" name="password_confirmation"
                    placeholder="Confirmer le mot de passe ici ...">
            </div>

            <button type="submit" class="button button-primary full-width">
                Mettre à jour le mot de passe
            </button>
        </form>
    </div>
@endsection

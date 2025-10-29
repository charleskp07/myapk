@extends('layouts.authlayout')

@section('title', 'Verification de code')

@section('content')
    <div class="auth-form-cover">
        <form action="{{route('auth.two-factor.verify')}}" method="post">
            @csrf

            <h1 class="text-center">Code de confirmation</h1>
            <br />
            <p class="text-center">
                Un code de confirmation à six chiffre a été envoyé à votre e-mail. saisissez-le dans le champs pour
                continer.
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
                <label for="otp-code">Code de confirmation</label>
                <input type="text" id="otp-code" name="code" autocomplete="off"
                    placeholder="Saisir le code de confirmation ici ..." maxlength="6" pattern="[0-9]{6}" autofocus />
            </div>

            <button type="submit" class="button button-primary full-width">
                Soumettre
            </button>
        </form>

        <div class="divider">
            <span>Vous n'avez pas reçu le code ?</span>
        </div>

        <form action="{{ route('auth.two-factor.resend') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-secondary">Renvoyer le code</button>
        </form>

        <div class="back-link">
            <a href="{{ route('login') }}">← Retour à la connexion</a>
        </div>

    </div>
@endsection

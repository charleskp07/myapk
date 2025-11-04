@extends('layouts.authchecked')

@section('css')
    <style>
        .center-aligned-button {
            display: flex;
            justify-content: center;
        }
    </style>
@endsection

@section('content')
    <div class="profile-cover text-center">
        <br />
        <img src="{{ Auth::user()->data && Auth::user()->data->profile_picture
            ? Storage::url(Auth::user()->data->profile_picture)
            : URL::asset('images/default-avatar.png') }}"
            alt="{{ Auth::user()->name }}" class="rounded-sm" />
        <br /><br /><br />
        <h2 style="margin-bottom: 10px;" class="roboto-black">
            {{ Auth::user()->name }}
        </h2>
        <p>
            <span style="font-weight: 700">Email :</span> {{ Auth::user()->email }}
        </p>
        <br />
        <br />
        <div class="center-aligned-button">
            <a href="{{ route('profile.edit') }}" class="button button-primary">
                <i class="fa-solid fa-user-pen"></i>
                Modifier mon profil
            </a>
        </div>
        <br />
        <div class="center-aligned-button">
            <a href="{{ route('logout') }}" style="color: red"
                onclick="return confirm('Êtes-vous sûr(e) de vouloir vous deconnectez ?')">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                Deconnexion
            </a>
        </div>

    </div>
    <br /><br /><br />
@endsection

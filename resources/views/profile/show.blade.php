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
    <div class="dashboard-cover text-center">
        <br />
        <img src="{{ Auth::user()->data && Auth::user()->data->profile_picture
            ? Storage::url(Auth::user()->data->profile_picture)
            : URL::asset('images/default-avatar.png') }}"
            alt="{{ Auth::user()->name }}" width="200" height="200" class="rounded-sm" />
        <br /><br />
        <h1 style="margin-bottom: 10px;" class="roboto-black">
            {{ Auth::user()->name }}
        </h1>
        <p>
            <span class="roboto-black">Email :</span> {{ Auth::user()->email }}
        </p>
        <br />
        <div class="center-aligned-button">
            <a href="{{ route('profile.edit') }}" class="button button-primary">
                <i class="fa-solid fa-user-pen"></i>
                Modifier mon profil
            </a>
        </div>

        <div class="center-aligned-button">
            <a href="{{ route('logout') }}" class="button button-primary">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                Deconnexion
            </a>
        </div>

    </div>
    <br /><br /><br />
@endsection

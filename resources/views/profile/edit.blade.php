@extends('layouts.authchecked')

@section('content')
    <div class="dashboard-cover">
        <div class="d-grid-2" style="gap: 40px;">
            <div class="text-right" style="padding-top: 5px;">
                <img src="{{ Auth::user()->data && Auth::user()->data->profile_picture
                    ? Storage::url(Auth::user()->data->profile_picture)
                    : URL::asset('images/default-avatar.png') }}"
                    alt="{{ Auth::user()->name }}" width="200" height="200" class="rounded-sm" />

                @if (Auth::user()->data && Auth::user()->data->profile_picture)
                    <form action="{{ route('profile.photo.delete') }}" method="POST" style="margin-top: 10px;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="button button-danger"
                            onclick="return confirm('Voulez-vous vraiment supprimer votre photo de profil ?')">
                            Supprimer la photo
                        </button>
                    </form>
                @endif
                
            </div>
            <div>
                <div style="max-width: 325px;">
                    <h1 class="roboto-black text-center">Modifier mon profil</h1>
                    <br />
                    <p class="text-center">
                        Remplir les nouvelles informations de votre profil pour le mettre à jour.
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
                        <p class="alert-success">{{ $message }}</p>
                        <br />
                    @endif
                    <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="input-cover">
                            <label for="profile-picture">Photo de profil</label>
                            <input type="file" id="profile-picture" name="profile_picture"
                                accept="image/png,image/jpg,/image/jpeg" />
                        </div>

                        <div class="d-grid-2">
                            <div class="input-cover">
                                <label for="name">Nom complet</label>
                                <input type="text" id="name" value="{{ Auth::user()->name }}" name="name"
                                    autocomplete="off" placeholder="Saisir l'e-mail ici ... ">
                            </div>
                            <div class="input-cover">
                                <label for="email">E-mail</label>
                                <input type="text" id="email" value="{{ Auth::user()->email }}" name="email"
                                    autocomplete="off" placeholder="Saisir l'e-mail ici ... ">
                            </div>
                        </div>
                        <br />
                        <p class="text-center roboto-black">
                            Si vous voulez mettre à jour votre mot de passe.
                        </p>
                        <br />
                        <div class="input-cover">
                            <label for="actual-password">Mot de passe actuel</label>
                            <input type="password" id="actual-password" name="actual_password" autocomplete="off"
                                placeholder="Saisir le mot de passe actuel ici ... ">
                        </div>

                        <div class="input-cover">
                            <label for="password">Noueau mot de passe</label>
                            <input type="password" id="password" name="password" autocomplete="off"
                                placeholder="Saisir le nouveau mot de passe ici ... ">
                        </div>

                        <button type="submit" class="button button-primary full-width">
                            Mettre à jour le profil
                        </button>
                    </form>
                </div>
                <br /><br /><br />
            </div>
        </div>
    </div>
@endsection

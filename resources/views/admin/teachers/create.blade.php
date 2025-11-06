@extends('layouts.authchecked')

@section('title', 'Ajouter un(e) engeinant(e)')

@section('content')

    <div>
        <div>
            <h2 class="">Ajouter un(e) enseignant(e)</h2>
            <br />
            <p class="">
                Remplir dans les champs les informations de l'enseignant(e) que vous voulez créer.
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

            <form action="{{ route('teachers.store') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="">
                    <label for="photo" class="form-label">Photo</label>
                    <input type="file" id="photo" name="photo" accept="image/png,image/jpg,/image/jpeg">
                </div>


                <div class="input-cover">
                    <label for="last_name">Nom </label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}"
                        placeholder="Saisir le nom de famille ici...">
                </div>

                <div class="input-cover">
                    <label for="first_name">Prénoms </label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}"
                        placeholder="Saisir le prénoms ici...">
                </div>

                <div class="input-cover">
                    <label for="date_of_birth" class="form-label">Date de naissance </label>
                    <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                </div>

                <div class="input-cover">
                    <label for="place_of_birth" class="form-label">Lieu de naissance *</label>
                    <input type="text" id="place_of_birth" name="place_of_birth" value="{{ old('place_of_birth') }}"
                        placeholder="Saisir le lieu de naissance ici...">
                </div>

                <div class="input-cover">
                    <label for="gender">Genre </label>
                    <select id="gender" name="gender">
                        <option value="">Sélectionnez...</option>
                        <option value="{{ App\Enums\GenderEnums::MASCULIN->value }}"
                            {{ old('gender') == 'Masculin' ? 'selected' : '' }}>
                            {{ App\Enums\GenderEnums::MASCULIN->value }}
                        </option>

                        <option value="{{ App\Enums\GenderEnums::FEMININ->value }}"
                            {{ old('gender') == 'Féminin' ? 'selected' : '' }}>
                            {{ App\Enums\GenderEnums::FEMININ->value }}
                        </option>
                    </select>
                </div>

                <div class="input-cover">
                    <label for="email" class="form-label">Email </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        placeholder="Saisir l'email ici...">
                </div>

                <div class="input-cover">
                    <label for="phone">Téléphone</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                        placeholder="Saisir le numero de telephone ici...">
                </div>


                <div class="input-cover">
                    <label for="nationality">Nationalité </label>
                    <input type="text" name="nationality" value="{{ old('nationality') }}"
                        placeholder="Saisir la nationalité ici...">
                </div>

                <div class="input-cover">
                    <label for="speciality">Spécialité </label>
                    <input type="text" name="speciality" value="{{ old('speciality') }}"
                        placeholder="Saisir la spécialité ici...">
                </div>


                <div style="display:flex; gap:10px; margin-top:20px;">
                    <button type="submit" class=""> Ajouter l'enseignant(e)</button>
                    <a href="{{ route('teachers.index') }}" class="btn-cancel">Annuler</a>
                </div>
                
            </form>
        </div>
    </div>

@endsection

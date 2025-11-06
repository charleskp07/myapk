@extends('layouts.authchecked')

@section('title', 'Modifier un(e) enseignant(e)')

@section('content')
    <div>
        <div>
            <h2 class="">Modifier un(e) enseignant(e)</h2>
            <br />
            <p class="">
                Modifier les informations de l'enseignant(e) sélectionné(e).
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

            <form action="{{ route('teachers.update', $teacher->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="">
                    <label for="photo" class="form-label">Photo</label><br>
                    @if ($teacher->photo)
                        <img src="{{ asset('storage/' . $teacher->photo) }}" alt="Photo de {{ $teacher->first_name }}" 
                             style="width:100px; height:100px; object-fit:cover; border-radius:8px; margin-bottom:10px;">
                    @endif
                    <input type="file" id="photo" name="photo" accept="image/png,image/jpg,image/jpeg">
                </div>

                
                <div class="input-cover">
                    <label for="last_name">Nom</label>
                    <input type="text" id="last_name" name="last_name"
                        value="{{ old('last_name', $teacher->last_name) }}"
                        placeholder="Modifier le nom de famille ici...">
                </div>

                
                <div class="input-cover">
                    <label for="first_name">Prénoms</label>
                    <input type="text" id="first_name" name="first_name"
                        value="{{ old('first_name', $teacher->first_name) }}"
                        placeholder="Modifier le prénom ici...">
                </div>

                
                <div class="input-cover">
                    <label for="date_of_birth" class="form-label">Date de naissance</label>
                    <input type="date" id="date_of_birth" name="date_of_birth"
                        value="{{ old('date_of_birth', $teacher->date_of_birth) }}">
                </div>

                
                <div class="input-cover">
                    <label for="place_of_birth" class="form-label">Lieu de naissance</label>
                    <input type="text" id="place_of_birth" name="place_of_birth"
                        value="{{ old('place_of_birth', $teacher->place_of_birth) }}"
                        placeholder="Modifier le lieu de naissance ici...">
                </div>

                
                <div class="input-cover">
                    <label for="gender">Genre</label>
                    <select id="gender" name="gender">
                        <option value="">Sélectionnez...</option>
                        <option value="{{ App\Enums\GenderEnums::MASCULIN->value }}"
                            {{ old('gender', $teacher->gender) == App\Enums\GenderEnums::MASCULIN->value ? 'selected' : '' }}>
                            {{ App\Enums\GenderEnums::MASCULIN->value }}
                        </option>

                        <option value="{{ App\Enums\GenderEnums::FEMININ->value }}"
                            {{ old('gender', $teacher->gender) == App\Enums\GenderEnums::FEMININ->value ? 'selected' : '' }}>
                            {{ App\Enums\GenderEnums::FEMININ->value }}
                        </option>
                    </select>
                </div>

                
                <div class="input-cover">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email"
                        value="{{ old('email', $teacher->email) }}"
                        placeholder="Modifier l'email ici...">
                </div>

                
                <div class="input-cover">
                    <label for="phone">Téléphone</label>
                    <input type="tel" id="phone" name="phone"
                        value="{{ old('phone', $teacher->phone) }}"
                        placeholder="Modifier le numéro de téléphone ici...">
                </div>

                
                <div class="input-cover">
                    <label for="nationality">Nationalité</label>
                    <input type="text" id="nationality" name="nationality"
                        value="{{ old('nationality', $teacher->nationality) }}"
                        placeholder="Modifier la nationalité ici...">
                </div>

                
                <div class="input-cover">
                    <label for="speciality">Spécialité</label>
                    <input type="text" id="speciality" name="speciality"
                        value="{{ old('speciality', $teacher->speciality) }}"
                        placeholder="Modifier la spécialité ici...">
                </div>

                
                <div style="display:flex; gap:10px; margin-top:20px;">
                    <button type="submit">Mettre à jour</button>
                    <a href="{{ route('teachers.index') }}" class="btn-cancel">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection

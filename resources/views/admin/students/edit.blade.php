@extends('layouts.authchecked')

@section('title', "Modifier l'apprenant(e)")

@section('content')
    <div>
        <div>
            <h2 class="roboto-black text-center">Modifier un(e) apprenant(e)</h2>
            <br />
            <p class="text-center">
                Modifiez les informations de l'apprenant(e) ci-dessous, puis cliquez sur "Mettre à jour".
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

            <form action="{{ route('students.update', $student->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')


                <div class="">
                    <label for="photo" class="form-label">Photo</label>
                    @if ($student->photo)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ asset('storage/' . $student->photo) }}" alt="Photo de {{ $student->first_name }}"
                                width="100" height="100" style="object-fit: cover; border-radius: 8px;">
                        </div>
                    @endif
                    <input type="file" id="photo" name="photo" accept="image/png,image/jpg,image/jpeg">
                </div>


                <div class="input-cover">
                    <label for="classroom_id">Classe</label>
                    <select id="classroom_id" name="classroom_id">
                        <option value="">Sélectionnez une classe...</option>
                        @forelse ($classrooms as $classroom)
                            <option value="{{ $classroom->id }}"
                                {{ old('classroom_id', $student->classroom_id) == $classroom->id ? 'selected' : '' }}>
                                {{ $classroom->name }} {{ $classroom->section }}
                            </option>
                        @empty
                            <option value="">Pas de classe créée</option>
                        @endforelse
                    </select>
                </div>


                <div class="input-cover">
                    <label for="last_name">Nom </label>
                    <input type="text" id="last_name" name="last_name"
                        value="{{ old('last_name', $student->last_name) }}" placeholder="Saisir le nom de famille ici...">
                </div>

                
                <div class="input-cover">
                    <label for="first_name">Prénoms </label>
                    <input type="text" id="first_name" name="first_name"
                        value="{{ old('first_name', $student->first_name) }}" placeholder="Saisir le prénom ici...">
                </div>

               
                <div class="input-cover">
                    <label for="date_of_birth" class="form-label">Date de naissance </label>
                    <input type="date" id="date_of_birth" name="date_of_birth"
                        value="{{ old('date_of_birth', $student->date_of_birth) }}">
                </div>

                
                <div class="input-cover">
                    <label for="place_of_birth" class="form-label">Lieu de naissance</label>
                    <input type="text" id="place_of_birth" name="place_of_birth"
                        value="{{ old('place_of_birth', $student->place_of_birth) }}"
                        placeholder="Saisir le lieu de naissance ici...">
                </div>

                
                <div class="input-cover">
                    <label for="gender">Genre </label>
                    <select id="gender" name="gender">
                        <option value="">Sélectionnez...</option>
                        <option value="{{ App\Enums\GenderEnums::MASCULIN->value }}"
                            {{ old('gender', $student->gender) == App\Enums\GenderEnums::MASCULIN->value ? 'selected' : '' }}>
                            {{ App\Enums\GenderEnums::MASCULIN->value }}
                        </option>
                        <option value="{{ App\Enums\GenderEnums::FEMININ->value }}"
                            {{ old('gender', $student->gender) == App\Enums\GenderEnums::FEMININ->value ? 'selected' : '' }}>
                            {{ App\Enums\GenderEnums::FEMININ->value }}
                        </option>
                    </select>
                </div>

                
                <div class="input-cover">
                    <label for="email" class="form-label">Email </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $student->email) }}"
                        placeholder="Saisir l'email ici...">
                </div>

                
                <div class="input-cover">
                    <label for="phone">Téléphone</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $student->phone) }}"
                        placeholder="Saisir le numéro de téléphone ici...">
                </div>

                
                <div class="input-cover">
                    <label for="nationality">Nationalité </label>
                    <input type="text" id="nationality" name="nationality"
                        value="{{ old('nationality', $student->nationality) }}" placeholder="Saisir la nationalité ici...">
                </div>

                
                <div style="display:flex; gap:10px; margin-top:20px;">
                    <button type="submit" class="">Mettre à jour</button>
                    <a href="javascript:history.back()" class="btn-cancel">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection

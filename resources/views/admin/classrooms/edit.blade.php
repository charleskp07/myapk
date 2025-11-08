@extends('layouts.authchecked')

@section('title', 'Modifier une classe')

@section('content')
    <div>
        <div>
            <h2 class="roboto-black text-center">Modifier une salle de classe</h2>
            <br />
            <p class="text-center">
                Vous pouvez modifier les informations de la salle de classe sélectionnée.
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

            <form action="{{ route('classrooms.update', $classroom->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="input-cover">
                    <label for="level">Niveau</label>
                    <select id="level" name="level">
                        <option value="">Sélectionnez...</option>
                        <option value="{{ App\Enums\ClassroomLevelEnums::COLLEGE->value }}"
                            {{ old('level', $classroom->level) == App\Enums\ClassroomLevelEnums::COLLEGE->value ? 'selected' : '' }}>
                            {{ App\Enums\ClassroomLevelEnums::COLLEGE->value }}
                        </option>
                        <option value="{{ App\Enums\ClassroomLevelEnums::LYCEE->value }}"
                            {{ old('level', $classroom->level) == App\Enums\ClassroomLevelEnums::LYCEE->value ? 'selected' : '' }}>
                            {{ App\Enums\ClassroomLevelEnums::LYCEE->value }}
                        </option>
                    </select>
                </div>

                <div class="input-cover">
                    <label for="name">Nom de la classe</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $classroom->name) }}"
                        placeholder="Saisir le nom de la salle de classe ici ...">
                </div>

                <div class="input-cover">
                    <label for="section">Section</label>
                    <input type="text" id="section" name="section" value="{{ old('section', $classroom->section) }}"
                        placeholder="Ex: Groupe A, Serie A4, ...">
                </div>


                <div class="input-cover">
                    <label for="teacher_id" >Enseignant Titulaire</label>
                    <select name="teacher_id" id="teacher_id">
                        <option value="">--Selectionner un enseignant--</option>
                        @forelse ($teachers as $teacher)
                            <option value="{{$teacher->id}}"  {{old('teacher_id',$classroom->teacher_id ) == $teacher->id ? 'selected' : ''}}>
                                {{ strtoupper($teacher->last_name )}} {{ $teacher->first_name }}
                            </option>        
                        @empty
                            <option value="">aucun enseignant n'a été trouvé</option>
                        @endforelse
                    </select>
                </div>

                <div style="display:flex; gap:10px; margin-top:20px;">
                    <button type="submit">Mettre à jour la salle de classe</button>
                    <a href="{{ route('classrooms.index') }}" class="btn-cancel">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection

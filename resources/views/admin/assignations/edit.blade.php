@extends('layouts.authchecked')

@section('title', 'Modifier une assignation')

@section('content')

    <div>
        <div>
            <h2 class="roboto-black text-center">Modifier une assignation</h2>
            <br />
            <p class="text-center">
                Mettre à jour les informations de l'assignation sélectionnée.
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

            
            <form action="{{ route('assignations.update', $assignation->id) }}" method="POST">
                @csrf
                @method('PUT')

               
                <div class="input-cover">
                    <label for="teacher_id">Enseignant </label>
                    <select id="teacher_id" name="teacher_id" class="@error('teacher_id') invalid @enderror">
                        <option value="">-- Sélectionner un enseignant --</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}"
                                {{ old('teacher_id', $assignation->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                {{ strtoupper($teacher->last_name )}} {{ $teacher->first_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                
                <div class="input-cover">
                    <label for="subject_id">Matière </label>
                    <select id="subject_id" name="subject_id" class="@error('subject_id') invalid @enderror">
                        <option value="">-- Sélectionner une matière --</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}"
                                {{ old('subject_id', $assignation->subject_id) == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                   
                </div>

                
                <div class="input-cover">
                    <label for="classroom_id">Classe </label>
                    <select id="classroom_id" name="classroom_id" class="@error('classroom_id') invalid @enderror">
                        <option value="">-- Sélectionner une classe --</option>
                        @foreach ($classrooms as $classroom)
                            <option value="{{ $classroom->id }}"
                                {{ old('classroom_id', $assignation->classroom_id) == $classroom->id ? 'selected' : '' }}>
                                {{ $classroom->name }} - {{ $classroom->section }}
                            </option>
                        @endforeach
                    </select>
                   
                </div>

                
                <div class="input-cover">
                    <label for="coefficient">Coefficient </label>
                    <input
                        type="number"
                        id="coefficient"
                        name="coefficient"
                        value="{{ old('coefficient', $assignation->coefficient) }}"
                        min="1"
                        max="10"
                        placeholder="Valeur entre 1 et 10" >
                    
                </div>
                
                <div class="input-cover">
                    <label for="number_of_hours">Nombre d'heure de cours </label>
                    <input
                        type="number"
                        id="number_of_hours"
                        name="number_of_hours"
                        value="{{ old('number_of_hours', $assignation->number_of_hours) }}"
                        min="1"
                        max="6"
                        placeholder="Valeur entre 1 et 6" >
                    
                </div>

                
                <div style="display:flex; gap:10px; margin-top:20px;">
                    <button type="submit"> Mettre à jour</button>
                    <a href="{{ route('assignations.index') }}" class="btn-cancel">Annuler</a>
                </div>
            </form>
        </div>
    </div>

@endsection

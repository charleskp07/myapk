@extends('layouts.authchecked')

@section('title', 'Nouvelle assignation')

@section('content')

    <div>
        <div>
            <h2 class="roboto-black text-center">Créer une nouvelle assignation</h2>
            <br />
            <p class="text-center">
                Sélectionnez un enseignant, une matière et une classe à associer ensemble.
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


            <form action="{{ route('assignations.store') }}" method="POST">
                @csrf


                <div class="input-cover">
                    <label for="teacher_id">Enseignant </label>
                    <select id="teacher_id" name="teacher_id" class="@error('teacher_id') invalid @enderror">
                        <option value="">-- Sélectionner un enseignant --</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->last_name }} {{ $teacher->first_name }}
                            </option>
                        @endforeach
                    </select>

                </div>


                <div class="input-cover">
                    <label for="subject_id">Matière </label>
                    <select id="subject_id" name="subject_id">
                        <option value="">-- Sélectionner une matière --</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>

                </div>


                <div class="input-cover">
                    <label for="classroom_id">Classe </label>
                    <select id="classroom_id" name="classroom_id">
                        <option value="">-- Sélectionner une classe --</option>
                        @foreach ($classrooms as $classroom)
                            <option value="{{ $classroom->id }}"
                                {{ (isset($classroom_id) && $classroom_id == $classroom->id) || old('classroom_id') == $classroom->id ? 'selected' : '' }}>
                                {{ $classroom->name }} - {{ $classroom->section }}
                            </option>
                        @endforeach
                    </select>

                </div>


                <div class="input-cover">
                    <label for="coefficient">Coefficient </label>
                    <input type="number" id="coefficient" name="coefficient" value="{{ old('coefficient', 1) }}"
                        min="1" max="10" placeholder="Valeur entre 1 et 10">

                </div>

                <div style="display:flex; gap:10px; margin-top:20px;">
                    <button type="submit"> Créer l'assignation</button>
                    <a href="{{ route('assignations.index') }}" class="btn-cancel">Annuler</a>
                </div>
            </form>
        </div>
    </div>

@endsection

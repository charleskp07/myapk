@extends('layouts.authchecked')

@section('title', 'Ajouter une matière')

@section('content')

    <div>
        <div>
            <h2 >Créer une matière</h2>
            <br />
            <p >
                Remplir dans les champs les informations de la matière que vous voulez créer.
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


            <form action="{{ route('subjects.store') }}" method="POST">
                @csrf

                <div class="input-cover">
                    <label for="name">Nom de la matière</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        placeholder="Saisir le nom de la matière ici ...">
                </div>

                <div class="input-cover">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" cols="30" rows="5" placeholder="Optionnel ...">{{ old('description') }}</textarea>
                </div>

                <div class="input-cover">
                    <label for="time_preference">Temps de préference</label>
                    <select id="time_preference" name="time_preference">
                        <option value="">Sélectionnez...</option>
                        @foreach (\App\Enums\TimePreference::cases() as $case)
                            <option value="{{ $case->value }}"
                                {{ old('time_preference', $subject->time_preference ?? '') === $case->value ? 'selected' : '' }}>
                                {{ $case->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="display:flex; gap:10px; margin-top:20px;">
                    <button type="submit">Créer la matière</button>
                    <a href="{{ route('subjects.index') }}" class="btn-cancel">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@extends('layouts.authchecked')

@section('title', 'Modifier une matière')

@section('content')

    <div>
        <div>
            <h2 >Modifier une matière</h2>
            <br />
            <p >
                Mettre à jour les informations de la matière sélectionnée.
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


            <form action="{{ route('subjects.update', $subject->id) }}" method="POST">
                @csrf
                @method('PUT')


                <div class="input-cover">
                    <label for="name">Nom de la matière</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $subject->name) }}"
                        placeholder="Modifier le nom de la matière ici ...">
                </div>


                <div class="input-cover">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" cols="30" rows="5" placeholder="Optionnel ...">{{ old('description', $subject->description) }}</textarea>
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
                    <button type="submit">Mettre à jour</button>
                    <a href="{{ route('subjects.index') }}" class="btn-cancel">Annuler</a>
                </div>
            </form>
        </div>
    </div>

@endsection

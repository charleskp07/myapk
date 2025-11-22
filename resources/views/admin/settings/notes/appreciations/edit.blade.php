@extends('layouts.authchecked')

@section('title', 'Modifier une appréciation')

@section('content')
    <div>
        <a href="javascript:history.back()">Retour</a>
        <div>
            <h2 class="roboto-black text-center">Modifier une appréciation</h2>
            <br />
            <p class="text-center">
                Modifiez les informations de l’appréciation sélectionnée, puis enregistrez les changements.
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
        </div>

        <div class="form-cover">
            <form action="{{ route('admin.appreciations.update', $appreciation->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="input-cover">
                    <label for="bareme_id">Barème</label>
                    <select name="bareme_id" id="bareme_id">
                        <option value="">Sélectionner un barème</option>
                        @forelse ($baremes as $bareme)
                            <option value="{{ $bareme->id }}"
                                {{ old('bareme_id', $appreciation->bareme_id) == $bareme->id ? 'selected' : '' }}>
                                {{ $bareme->value }}
                            </option>
                        @empty
                            <option value="">Aucun barème n'a été créé</option>
                        @endforelse
                    </select>
                </div>

                <div class="input-cover">
                    <label for="appreciation">Appréciation</label>
                    <select name="appreciation" id="appreciation">
                        <option value="">-- Sélectionner l'appréciation --</option>
                        @foreach (App\Enums\NoteAppreciationEnums::cases() as $enum)
                            <option value="{{ $enum->value }}"
                                {{ old('appreciation', $appreciation->appreciation) == $enum->value ? 'selected' : '' }}>
                                {{ $enum->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="input-cover">
                    <label for="min_value">Valeur minimale</label>
                    <input type="number" name="min_value" id="min_value"
                        value="{{ old('min_value', $appreciation->min_value) }}" step="0.1">
                </div>

                <div class="input-cover">
                    <label for="max_value">Valeur maximale</label>
                    <input type="number" name="max_value" id="max_value"
                        value="{{ old('max_value', $appreciation->max_value) }}" step="0.1">
                </div>

                <div style="display:flex; gap:10px; margin-top:20px;">
                    <button type="submit">Mettre à jour</button>
                    <a href="{{ route('admin.baremes.show', $appreciation->bareme_id) }}" class="btn-cancel">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection

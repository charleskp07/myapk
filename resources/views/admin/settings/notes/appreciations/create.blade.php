@extends('layouts.authchecked')

@section('title', 'Nouveau appréciation')


@section('content')

    <div>
        <div>
            <h2 class="roboto-black text-center">Créer un appréciation</h2>
            <br />
            <p class="text-center">
                Remplir dans les champs les informations du appréciation que vous voulez créer.
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
            <form action="{{route('admin.appreciations.store')}}" method="post">
                @csrf

                <div class="input-cover">
                    <label for="bareme_id">Barème</label>
                    <select name="bareme_id" id="bareme_id">
                        <option value="">selectionner un barème</option>
                        @forelse ($baremes as $bareme)
                            <option value="{{ $bareme->id }}"
                                {{ (isset($bareme_id) && $bareme_id == $bareme->id) || old('bareme_id') == $bareme->id ? 'selected' : '' }}>
                                {{ $bareme->value }}</option>
                        @empty
                            <option value="">aucun barème n'a été creer</option>
                        @endforelse
                    </select>
                </div>

                <div class="input-cover">
                    <label for="appreciation">Appréciation</label>
                    <select name="appreciation" id="appreciation">
                        <option value="">-- Sélectionner l'appréciation --</option>
                        @foreach (App\Enums\NoteAppreciationEnums::cases() as $enum)
                            <option value="{{ $enum->value }}" {{ old('appreciation') == $enum->value ? 'selected' : '' }}>
                                {{ $enum->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="input-cover">
                    <label for="min_value">Valeur minimale</label>
                    <input type="number" name="min_value" value="{{ old('min_value') }}" step="0.1">
                </div>

                <div class="input-cover">
                    <label for="max_value">Valeur maximale</label>
                    <input type="number" id="max_value" name="max_value" value="{{ old('max_value') }}" step="0.1">
                </div>

                <div style="display:flex; gap:10px; margin-top:20px;">
                    <button type="submit">Créer l'appreciation</button>
                    <a href="{{ route('admin.baremes.show', $bareme_id ) }}" class="btn-cancel">Annuler</a>
                </div>
            </form>

        </div>



    </div>


@endsection

@extends('layouts.authchecked')

@section('title', 'Modifier le barème')

@section('content')
    <div>
        <a href="javascript:history.back()">Retour</a>
        <div>
            <h2 class="roboto-black text-center">Modifier le barème</h2>
            <br />
            <p class="text-center">
                Modifier la valeur du barème sélectionné.
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

            <form action="{{ route('admin.baremes.update', $bareme->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="input-cover">
                    <label for="value">Valeur</label>
                    <input type="number" id="value" name="value"
                           value="{{ old('value', $bareme->value) }}"
                           placeholder="Modifier la valeur du barème ici ...">
                </div>

                <div style="display:flex; gap:10px; margin-top:20px;">
                    <button type="submit">Mettre à jour</button>
                    <a href="{{ route('admin.baremes.index') }}" class="btn-cancel">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection

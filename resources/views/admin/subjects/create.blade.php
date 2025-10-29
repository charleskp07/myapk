@extends('layouts.authchecked')

@section('title', 'Ajouter une matière')

@section('content')

    <div>
        <div>
            <h2 class="roboto-black text-center">Créer une matière</h2>
            <br />
            <p class="text-center">
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

                <div style="display:flex; gap:10px; margin-top:20px;">
                    <button type="submit">Créer la matière</button>
                    <a href="{{ route('subjects.index') }}" class="btn-cancel">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection

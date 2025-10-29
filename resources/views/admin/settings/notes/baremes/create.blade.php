@extends('layouts.authchecked')

@section('title', 'Nouveau Barème')


@section('content')
    <div>
        <div>
            <h2 class="roboto-black text-center">Créer un barème</h2>
            <br />
            <p class="text-center">
                Remplir dans les champs les informations du barème que vous voulez créer.
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


            <form action="{{ route('admin.baremes.store') }}" method="POST">
                @csrf

                <div class="input-cover">
                    <label for="name">Valeur</label>
                    <input type="number" id="value" name="value" value="{{ old('value') }}"
                        placeholder="Saisir la valeur du barème ici ...">
                </div>


                <div style="display:flex; gap:10px; margin-top:20px;">
                    <button type="submit">Créer le barème</button>
                    <a href="{{ route('admin.baremes.index') }}" class="btn-cancel">Annuler</a>
                </div>
            </form>
        </div>
    </div>

@endsection

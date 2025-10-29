@extends('layouts.authchecked')

@section('title', 'Modifier un Découpage')

@section('content')
    <div>
        <div>
            <h2 class="roboto-black text-center">Modifier le découpage</h2>
            <br />
            <p class="text-center">
                Mettez à jour les informations du découpage sélectionné.
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

            
            <form action="{{ route('admin.breakdowns.update', $breakdown->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="input-cover">
                    <label for="name">Nom du découpage *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $breakdown->name) }}"
                        placeholder="Ex: 1er Trimestre, 2e Semestre, etc.">
                </div>

                <div class="input-cover">
                    <label for="start_date">Date de début *</label>
                    <input type="date" id="start_date" name="start_date"
                        value="{{ old('start_date', $breakdown->start_date) }}" min="{{ now()->format('Y-m-d') }}">
                </div>

                <div class="input-cover">
                    <label for="end_date">Date de fin *</label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $breakdown->end_date) }}"
                        min="{{ now()->format('Y-m-d') }}">
                </div>

                <div style="display:flex; gap:10px; margin-top:20px;">
                    <button type="submit">Mettre à jour</button>
                    <a href="{{ route('admin.breakdowns.index') }}" class="btn-cancel">Annuler</a>
                </div>
            </form>
        </div>
    </div>


@endsection

@section('js')

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const start = new Date(document.getElementById('start_date').value);
            const end = new Date(document.getElementById('end_date').value);

            if (end < start) {
                e.preventDefault();
                alert("La date de fin doit être postérieure à la date de début.");
            }
        });
    </script>

@endsection

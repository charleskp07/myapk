@extends('layouts.authchecked')

@section('title', 'Nouveau Découpage')

@section('content')
    <div>
        <div>
            <h2 class="">Créer un découpage</h2>
            <br />
            <p class="">
                Remplir dans les champs les informations du découpage que vous souhaitez créer.
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


            <form action="{{ route('admin.breakdowns.store') }}" method="POST">
                @csrf

                <div class="input-cover">
                    <label for="type">Type du découpage *</label>

                    <select name="type" id="type">
                        <option value="">Selectionner un type</option>
                        @foreach (App\Enums\BreakdownNameEnums::cases() as $enum)
                            <option value="{{ $enum->value }}" {{ old('type') === $enum->value ? 'selected' : '' }}>
                                {{ ucfirst(strtolower($enum->value)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="input-cover">
                    <label for="value">valeur *</label>
                    <input type="number" id="value" name="value" value="{{ old('value') }}"
                        placeholder="entrer une valeur entre 1 et 3">
                </div>

                <div class="input-cover">
                    <label for="start_date">Date de début *</label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}">
                </div>

                <div class="input-cover">
                    <label for="end_date">Date de fin *</label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}"
                        min="{{ now()->format('Y-m-d') }}">
                </div>

                <div style="display:flex; gap:10px; margin-top:20px;">
                    <button type="submit">Créer le découpage</button>
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

            if (end <= start) {
                e.preventDefault();
                alert("La date de fin doit être postérieure à la date de début.");
            }
        });
    </script>
@endsection

@extends('layouts.authchecked')

@section('title', 'Modifier un Découpage')

@section('content')
    <div>
        <div>
            <h2 class="">Modifier le découpage</h2>
            <br />
            <p class="">
                Modifier les informations du découpage sélectionné, puis enregistrez les changements.
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
                    <label for="type">Type du découpage *</label>
                    <select name="type" id="type">
                        <option value="">Sélectionner un type</option>
                        @foreach (App\Enums\BreakdownNameEnums::cases() as $enum)
                            <option value="{{ $enum->value }}"
                                {{ old('type', $breakdown->type) === $enum->value ? 'selected' : '' }}>
                                {{ ucfirst(strtolower($enum->value)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                
                <div class="input-cover">
                    <label for="value">Valeur *</label>
                    <input type="number" id="value" name="value" value="{{ old('value', $breakdown->value) }}"
                        placeholder="Entrer une valeur (1 à 3 ou 1 à 2)">
                </div>

             
                <div class="input-cover">
                    <label for="start_date">Date de début *</label>
                    <input type="date" id="start_date" name="start_date"
                        value="{{ old('start_date', $breakdown->start_date) }}">
                </div>

        
                <div class="input-cover">
                    <label for="end_date">Date de fin *</label>
                    <input type="date" id="end_date" name="end_date"
                        value="{{ old('end_date', $breakdown->end_date) }}">
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
        // Vérification côté client de la cohérence des dates
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

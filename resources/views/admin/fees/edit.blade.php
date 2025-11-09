@extends('layouts.authchecked')

@section('title', 'Modification de frais')

@section('content')
    <div>
        <div>
            <div>
                <h2>Modification de Frais</h2>
            </div>
            <br />
            <p>
                Modifiez les informations du frais sélectionné ci-dessous.
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

            <div>
                <form action="{{ route('fees.update', $fee->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="input-cover">
                        <label for="classroom_id">Salle de classe</label>
                        <select name="classroom_id" id="classroom_id">
                            <option value="">Sélectionner une salle de classe</option>
                            @forelse ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}"
                                    {{ old('classroom_id', $fee->classroom_id) == $classroom->id ? 'selected' : '' }}>
                                    {{ $classroom->name }} {{ $classroom->section }}
                                </option>
                            @empty
                                <option value="">Aucune salle de classe trouvée</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="input-cover">
                        <label for="name">Nom</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $fee->name) }}"
                            placeholder="Ex: Frais d'écolage, cotisations...">
                    </div>

                    <div class="input-cover">
                        <label for="amount">Montant</label>
                        <input type="number" id="amount" name="amount" value="{{ old('amount', $fee->amount) }}" step="100"
                            placeholder="Ex: 50000">
                    </div>

                    <div class="input-cover">
                        <label for="type">Type de frais</label> <br />
                        <select name="type" id="type" class="form-select">
                            <option value="">Sélectionnez le type de frais</option>
                            @foreach (\App\Enums\FeeTypeEnums::cases() as $type)
                                <option value="{{ $type->value }}"
                                    {{ old('type', $fee->type) === $type->value ? 'selected' : '' }}>
                                    {{ $type->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-cover">
                        <label for="deadline">Date limite de paiement</label>
                        <input type="date" id="deadline" name="deadline" value="{{ old('deadline', $fee->deadline) }}">
                    </div>

                    <div>
                        <button type="submit">
                            Mettre à jour le frais
                        </button>
                        <a href="{{ route('classrooms.show', $fee->classroom_id) }}">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

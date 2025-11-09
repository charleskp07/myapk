@extends('layouts.authchecked')

@section('title', 'Ajouter des frais')

@section('content')
    <div>
        <div>
            <div>
                <h2>Creation de Frais</h2>
            </div>
            <br />
            <p class="">
                Remplir dans les champs les frais que vous souhaitez créer.
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
                <form action="{{ route('fees.store') }}" method="POST">
                    @csrf

                    <div class="input-cover">
                        <label for="classroom_id">La salle de classe </label>
                        <select name="classroom_id" id="classroom_id">
                            <option value="">Selectionner une salle de classe</option>
                            @forelse ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}"
                                    {{ (isset($classroom_id) && $classroom_id == $classroom->id) || old('classroom_id') == $classroom->id ? 'selected' : '' }}>
                                    {{ $classroom->name }} {{ $classroom->section }}
                                </option>
                            @empty
                                <option value="">Aucune salle de classe n'a été trouvée</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="input-cover">
                        <label for="name">Nom </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            placeholder="Ex: Frais d'ecolage, Cotisations...">
                    </div>

                    <div class="input-cover">
                        <label for="amount">Montant </label>
                        <input type="number" name="amount" id="amount" value="{{ old('amount') }}" step="100"
                            placeholder="Ex: 50000">
                    </div>

                    <div class="input-cover">
                        <label for="type">Type de frais</label> <br />
                        <select name="type" id="type" class="form-select">
                            <option value="">Sélectionnez le type de frais</option>
                            @foreach (\App\Enums\FeeTypeEnums::cases() as $type)
                                <option value="{{ $type->value }}" {{ old('type') === $type->value ? 'selected' : '' }}>
                                    {{ $type->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-cover">
                        <label for="deadline">Date limite de paiement</label>
                        <input type="date" id="deadline" name="deadline" value="{{ old('deadline') }}">
                    </div>

                    <div>
                        <button type="submit">
                            Enregistre le frais
                        </button>
                        <a href="{{ route('classrooms.show', $classroom->id) }}">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

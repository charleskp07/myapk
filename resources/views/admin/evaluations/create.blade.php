@extends('layouts.authchecked')

@section('title', 'Nouvelle evaluation')

@section('content')

    <div>
        <div>
            <h2 class="">Créer une nouvelle évaluation</h2>
            <br />
            <p class="text-center">
                Remplir dans les champs les informations de l'evaluation que vous voulez créer.
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

            <form action="{{ route('evaluations.store') }}" method="post">
                @csrf

                <div class="input-cover">
                    <label for="breakdown_id">Decoupage</label>
                    <select name="breakdown_id" id="breakdown_id">
                        <option value="">Sélectionner un decoupage</option>

                        @forelse ($breakdowns as $breakdown)
                            <option value="{{ $breakdown->id }}"
                                {{ old('breakdown_id') == $breakdown->id ? 'selected' : '' }}>
                                {{ $breakdown->type }}
                                {{ $breakdown->value }}
                            </option>
                        @empty
                            <option value="">Aucun decoupage n'a été defini</option>
                        @endforelse
                    </select>
                </div>

                <div class="input-cover">
                    <label for="assignation_id">Assignation</label>
                    <select name="assignation_id" id="assignation_id">
                        <option value="">Sélectionner une assignation</option>

                        @forelse ($assignations as $assignation)
                            <option value="{{ $assignation->id }}"
                                {{ old('assignation_id') == $assignation->id ? 'selected' : '' }}>
                                {{ $assignation->teacher->last_name }} {{ $assignation->teacher->first_name }}
                                -
                                {{ $assignation->subject->name }} -
                                {{ $assignation->classroom->name }}
                                {{ $assignation->classroom->section }}
                            </option>
                        @empty
                            <option value="">Aucune assignation n'a ete enregistrée</option>
                        @endforelse
                    </select>
                </div>

                <div class="input-cover">
                    <label for="title">
                        Titre
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}"
                        placeholder="Ex: Composition du 1er Semestre">
                </div>

                <div class="input-cover">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" value="{{ old('date') }}"
                        min="{{ now()->format('Y-m-d') }}"
                        {{-- oninput="if(['6','0'].includes(new Date(this.value).getDay().toString())) { alert('Week-end non autorisé'); this.value=''; }" --}}
                        >
                </div>

                <div class="input-cover">
                    <label for="type">Type</label>
                    <select name="type" id="type">
                        <option value="">-- Sélectionner un type --</option>
                        @foreach (App\Enums\EvaluationTypeEnums::cases() as $enum)
                            <option value="{{ $enum->value }}" {{ old('type') === $enum->value ? 'selected' : '' }}>
                                {{ ucfirst(strtolower($enum->value)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="input-cover">
                    <label for="bareme_id">Note Maximale</label>
                    <select name="bareme_id" id="bareme_id">
                        <option value="">selectionner une note maximale</option>
                        @forelse ($baremes as $bareme)
                            <option value="{{ $bareme->id }}" {{ old('bareme_id') == $bareme->id ? 'selected' : '' }}>
                                Sur {{ $bareme->value }}
                            </option>
                        @empty
                            <option value="">Aucun note maximale n'a été defini</option>
                        @endforelse
                    </select>
                </div>

                <div style="display:flex; gap:10px; margin-top:20px;">
                    <button type="submit">Créer l'evaluation</button>
                    <a href="{{ route('evaluations.index') }}" class="btn-cancel">Annuler</a>
                </div>


            </form>

        </div>
    </div>

@endsection

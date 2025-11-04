@extends('layouts.authchecked')

@section('title', 'Modifier une évaluation')

@section('content')
    <div>
        <div>
            <h2 class="">Modifier une évaluation</h2>
            <br />
            <p class="text-center">
                Mettre à jour les informations de l'évaluation sélectionnée.
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

            <form action="{{ route('evaluations.update', $evaluation->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="input-cover">
                    <label for="breakdown_id">Découpage</label>
                    <select name="breakdown_id" id="breakdown_id">
                        <option value="">Sélectionner un découpage</option>
                        @foreach ($breakdowns as $breakdown)
                            <option value="{{ $breakdown->id }}"
                                {{ $evaluation->breakdown_id == $breakdown->id ? 'selected' : '' }}>
                                {{ $breakdown->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="input-cover">
                    <label for="assignation_id">Assignation</label>
                    <select name="assignation_id" id="assignation_id">
                        <option value="">Sélectionner une assignation</option>
                        @foreach ($assignations as $assignation)
                            <option value="{{ $assignation->id }}"
                                {{ $evaluation->assignation_id == $assignation->id ? 'selected' : '' }}>
                                {{ $assignation->teacher->last_name }} {{ $assignation->teacher->first_name }}
                                - {{ $assignation->subject->name }}
                                - {{ $assignation->classroom->name }} {{ $assignation->classroom->section }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="input-cover">
                    <label for="title">Titre</label>
                    <input type="text" id="title" name="title" value="{{ $evaluation->title }}"
                        placeholder="Ex: Composition du 1er Semestre">
                </div>

                <div class="input-cover">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" value="{{ $evaluation->date->format('Y-m-d') }}">
                </div>

                <div class="input-cover">
                    <label for="type">Type</label>
                    <select name="type" id="type">
                        <option value="">-- Sélectionner un type --</option>
                        @foreach (App\Enums\EvaluationTypeEnums::cases() as $enum)
                            <option value="{{ $enum->value }}"
                                {{ $evaluation->type === $enum->value ? 'selected' : '' }}>
                                {{ ucfirst(strtolower($enum->value)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="input-cover">
                    <label for="bareme_id">Note Maximale</label>
                    <select name="bareme_id" id="bareme_id">
                        <option value="">Sélectionner une note maximale</option>
                        @foreach ($baremes as $bareme)
                            <option value="{{ $bareme->id }}"
                                {{ $evaluation->bareme_id == $bareme->id ? 'selected' : '' }}>
                                Sur {{ $bareme->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="display:flex; gap:10px; margin-top:20px;">
                    <button type="submit">Mettre à jour</button>
                    <a href="{{ route('evaluations.index') }}" class="btn-cancel">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@extends('layouts.authchecked')

@section('title', 'Remplissage de notes')

@section('content')
    <div>
        <div>
            <div>
                <h2>Remplissage des notes</h2>
            </div>
            <br />
            <p class="">
                Remplir dans les champs les notes des apprenants.
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

            <br />

            <div class="card-body">
                <div>
                    <h4>
                        Classe :
                        {{ $evaluation->assignation->classroom->name }}
                        {{ $evaluation->assignation->classroom->section }}
                    </h4>
                    <p>
                        <strong>Matière :</strong>
                        {{ $evaluation->assignation->subject->name }}
                    </p>
                    <p>
                        <strong>Type d'évaluation :</strong>
                        {{ ucfirst($evaluation->type) }}
                    </p>
                    <p>
                        <strong>Date :</strong>
                        {{ $evaluation->date->format('d/m/Y') }}
                    </p>
                </div>
                <br />
                <form action="{{ route('notes.store', $evaluation) }}" method="POST">
                    @csrf
                    <input type="hidden" name="evaluation_id" value="{{ $evaluation->id }}">

                    <div style="max-width: 700px;">
                        <table {{-- id="datatables" --}}>
                            <thead>
                                <tr>
                                    <th>Étudiant</th>
                                    <th>Note /{{ $evaluation->bareme->value }}</th>
                                    <th>Commentaire</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($evaluation->assignation->classroom->students as $index => $student)
                                    @php
                                        $existingNote = $student->notes
                                            ->where('evaluation_id', $evaluation->id)
                                            ->first();
                                    @endphp

                                    @if (!$existingNote)
                                        <tr>
                                            <td>
                                                <input type="hidden" name="students[{{ $index }}][student_id]"
                                                    value="{{ $student->id }}">
                                                {{ $student->last_name }} {{ $student->first_name }}
                                            </td>

                                            <td>
                                                <input type="number" name="students[{{ $index }}][value]"
                                                    step="0.25" min="0" max="{{ $evaluation->bareme->value }}"
                                                    value="{{ old('students.' . $index . '.value') }}">
                                            </td>

                                            <td>
                                                <input type="text" name="students[{{ $index }}][comment]"
                                                    value="{{ old('students.' . $index . '.comment') }}"
                                                    placeholder="Commentaire...">

                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div>
                        <button type="submit">
                            Enregistrer toutes les notes
                        </button>
                        <a href="{{ route('evaluations.show', $evaluation->id) }}">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        new DataTable('#datatables', {
            responsive: true,
        });
    </script>
@endsection

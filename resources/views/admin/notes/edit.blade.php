@extends('layouts.authchecked')

@section('title', 'Modification des notes')

@section('content')
    <div>
        <div>
            <div>
                <h2>Modification des notes</h2>
            </div>
            <br />
            <p class="text-center">
                Modifier les notes ou commentaires des apprenants ci-dessous.
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


                <form action="{{ route('notes.update', $evaluation->id) }}" method="POST">
                    @csrf
                    
                    @method('PUT')

                    <input type="hidden" name="evaluation_id" value="{{ $evaluation->id }}">

                    <table>
                        <thead>
                            <tr>
                                <th>Étudiant</th>
                                <th>Note /{{ $evaluation->bareme->value }}</th>
                                <th>Commentaire</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($evaluation->notes as $index => $note)
                                <tr>
                                    <td>
                                        <input type="hidden" name="students[{{ $index }}][student_id]"
                                            value="{{ $note->student->id }}">
                                        {{ $note->student->last_name }} {{ $note->student->first_name }}
                                    </td>
                                    <td>
                                        <input type="number" name="students[{{ $index }}][value]"
                                            class="form-control"
                                            value="{{ old('students.' . $index . '.value', $note->value) }}" step="0.25"
                                            min="0" max="{{ $evaluation->bareme->value }}">
                                    </td>
                                    <td>
                                        <input type="text" name="students[{{ $index }}][comment]"
                                            class="form-control"
                                            value="{{ old('students.' . $index . '.comment', $note->comment) }}"
                                            placeholder="Commentaire...">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div>
                        <button type="submit">Mettre à jour</button>
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

@extends('layouts.authchecked')

@section('title', 'Détails de l\'evaluation')


@section('content')

    <div class="back-btn">
        <a href="{{ route('evaluations.index') }}">
            <i class="bi bi-arrow-left"></i>
            retour
        </a>
    </div>
    
    <h1>Details d'evaluation</h1>

    <br />

    <div>
        <div class="">
            <b>Titre:</b>
            <span>
                {{ $evaluation->title }}
            </span>
        </div>

        <div class="">
            <b>Découpage:</b>
            <span>
                {{ $evaluation->breakdown->type }}
                {{ $evaluation->breakdown->value }}
            </span>
        </div>

        <div class="">
            <b>Date:</b>
            <span>
                {{ $evaluation->date->format('d/m/Y') }}
            </span>
        </div>

        <div class="">
            <b>Type:</b>
            <span>
                {{ $evaluation->type }}
            </span>
        </div>

        <div class="">
            <b>Note Maximale:</b>
            <span>
                {{ $evaluation->bareme->value }}
            </span>
        </div>

        <div class="">
            <b>Enseignant:</b>
            <span>
                {{ $evaluation->assignation->teacher->last_name }}
                {{ $evaluation->assignation->teacher->first_name }}
            </span>
        </div>

        <div class="">
            <b>Matière:</b>
            <span>
                {{ $evaluation->assignation->subject->name }}
            </span>
        </div>

        <div class="">
            <b>Classe:</b>
            <span>
                {{ $evaluation->assignation->classroom->name }} -
                {{ $evaluation->assignation->classroom->section }}
            </span>
        </div>

        <div class="">
            <b>Nombre de notes:</b>
            <span>
                {{ $evaluation->notes->count() }}
            </span>

        </div>
        <br />

        @if ($evaluation->notes->count() < $evaluation->assignation->classroom->students->count())
            <div class="">
                <a href="{{ route('notes.create', ['evaluation_id' => $evaluation->id]) }}" class="">
                    Ajouter des notes
                </a>
            </div>
        @endif

    </div>

    @if ($evaluation->notes->count() > 0)
        <div class="card-header">
            <h5>Notes associées</h5>
        </div>

        <div class="">
            <table id="datatables">
                <thead>
                    <tr>
                        <th>Étudiant</th>
                        <th>Note</th>
                        <th>Appréciation</th>
                        <th>Commentaire</th>
                        <th>Statut</th>
                        <th>

                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($evaluation->notes as $note)
                        <tr>
                            <td onclick='onRowClick("{{ $note->student->id }}")'>
                                {{ $note->student->last_name }} {{ $note->student->first_name }}
                            </td>

                            <td onclick='onRowClick("{{ $note->student->id }}")'>
                                {{ $note->value }}/{{ $evaluation->bareme->value }}
                            </td>

                            <td onclick='onRowClick("{{ $note->student->id }}")'>
                                {{ $note->appreciation?->appreciation }}
                            </td>

                            <td onclick='onRowClick("{{ $note->student->id }}")'>
                                {{ $note->comment ?? '--Pas de commentaire--' }}
                            </td>

                            <td onclick='onRowClick("{{ $note->student->id }}")'>

                                @if ($evaluation->bareme->value == 20)
                                    @if ($note->value >= 10)
                                        <p style="color: green;">Validé</p>
                                    @else
                                        <p style="color: red;">Non-validé</p>
                                    @endif
                                @else
                                    @if ($evaluation->bareme->value == 10)
                                        @if ($note->value >= 5)
                                            <p style="color: green;">Validé</p>
                                        @else
                                            <p style="color: red;">Non-validé</p>
                                        @endif
                                    @else
                                        @if ($evaluation->bareme->value == 5)
                                            @if ($note->value >= 2.5)
                                                <p style="color: green;">Validé</p>
                                            @else
                                                <p style="color: red;">Non-validé</p>
                                            @endif
                                        @endif
                                    @endif
                                @endif

                            </td>

                            <td>
                                <a href="{{ route('notes.edit', $evaluation->id) }}">
                                    <i class="fas fa-edit"></i>
                                    Modifer
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align: center;">
            <p>--Aucune note n'a été enregistré--</p><br />
            <img src="{{ asset('images/icons/trash-empty-svgrepo-com.png') }}" alt="" width="50">
        </div>
    @endif

@endsection

@section('js')
    <script>
        new DataTable('#datatables', {
            responsive: true,
            info: false,
            columnDefs: [{
                orderable: false,
                targets: [5],
            }]
        });

        function onRowClick(id) {
            window.location.href = `students/${id}`;
        }
    </script>
@endsection

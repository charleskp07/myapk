@extends('layouts.authchecked')

@section('title', 'Details d\'un(e) apprenant(e)')


@section('content')
    <div>
        <div class="back-btn">
            <a href="{{ route('students.index') }}">
                <i class="bi bi-arrow-left"></i>
                Retour
            </a>
        </div>

        <h1>{{ $student->last_name }} {{ $student->first_name }}</h1>

        <br />
        <div style="display: flex; gap: 20px;">
            <img src="{{ $student->photo_url }}" alt="{{ $student->full_name }}" width="200" style="border-radius: 20px">

            <div>
                <p>
                    <strong>Classe:</strong>
                    {{ $student->classroom->name }} {{ $student->classroom->section }}<br>
                </p>

                <p>
                    <strong>Email:</strong>
                    {{ $student->email }}<br>
                </p>

                <p>
                    <strong>Téléphone:</strong>
                    {{ $student->phone }}
                </p>

                <p>
                    <strong>Age:</strong>
                    {{ $student->age }} ans
                </p>

                <p>
                    <strong>Date et lieu de naissance:</strong>
                    Né le {{ \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') }} à
                    {{ $student->place_of_birth }}
                </p>

                <p>
                    <strong>Genre:</strong>
                    {{ $student->gender }}
                </p>

                <p>
                    <strong>Nationalité:</strong>
                    {{ $student->nationality }}
                </p>

            </div>
        </div>

        <br />
        <br />

        <hr>

        <br />
        <h2>Liste des notes</h2>
        <br />

        @if (!$student->notes->isEmpty())
            <table id="datatables">
                <thead>
                    <tr>
                        <th>
                            {{ $student->classroom->level == 'Lycée' ? 'Semestre' : 'Trimestre' }}
                        </th>

                        <th>
                            Matière
                        </th>

                        <th>
                            Type
                        </th>

                        <th>
                            Note
                        </th>

                        <th>
                            Appreciation
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($student->notes as $note)
                        <tr>
                            <td>
                                {{ $note->evaluation->breakdown->name }}
                            </td>
                            <td>
                                {{ $note->evaluation->assignation->subject->name }}
                            </td>
                            <td>
                                {{ $note->evaluation->type }}
                            </td>
                            <td>
                                {{ $note->value }}
                            </td>
                            <td>
                                {{ $note->appreciation?->appreciation }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center">
                <p>--Aucune note enregistrée pour cet apprenant--</p>
                <img src="{{ asset('images/icons/trash-empty-svgrepo-com.png') }}" alt="" width="50px">
            </div>
        @endif
    </div>

@endsection

@section('js')
    <script>
        new DataTable('#datatables', {
            responsive: true,
        });
    </script>
@endsection

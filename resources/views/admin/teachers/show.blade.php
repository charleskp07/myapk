@extends('layouts.authchecked')

@section('title', 'Details d\'un enseignant(e)')

@section('content')

    <div>
        <div class="back-btn">
            <a href="{{ route('teachers.index') }}">
                <i class="bi bi-arrow-left"></i>
                Retour
            </a>
        </div>

        <h1>{{ $teacher->last_name }} {{ $teacher->first_name }}</h1>

        <br />
        <div style="display: flex; gap: 20px;">
            <img src="{{ $teacher->photo_url }}" alt="{{ $teacher->full_name }}" width="200" style="border-radius: 20px">

            <div>
                <p>
                    <strong>Email:</strong>
                    {{ $teacher->email }}<br>
                </p>

                <p>
                    <strong>Téléphone:</strong>
                    {{ $teacher->phone }}
                </p>

                <p>
                    <strong>Age:</strong>
                    {{ $teacher->age }} ans
                </p>

                <p>
                    <strong>Date et lieu de naissance:</strong>
                    Né le {{ \Carbon\Carbon::parse($teacher->date_of_birth)->format('d/m/Y') }} à
                    {{ $teacher->place_of_birth }}
                </p>

                <p>
                    <strong>Genre:</strong>
                    {{ $teacher->gender }}
                </p>

                <p>
                    <strong>Specialité:</strong>
                    {{ $teacher->speciality }}
                </p>

                <p>
                    <strong>Nationalité:</strong>
                    {{ $teacher->nationality }}
                </p>

            </div>
        </div>

        <br />
        <br />

        <hr>

        <br />
        <h2>Liste des assignations</h2>
        <br />

        @if ($teacher->assignations->isEmpty())
            <div style="text-align: center">
                <p>--Aucune assignation enregistrée pour cet(te) enseignant(e)--</p>
                <img src="{{ asset('images/icons/trash-empty-svgrepo-com.png') }}" alt="" width="50px">
            </div>
        @else
            <table id="datatables">
                <thead>
                    <tr>
                        <th>
                            Classe
                        </th>

                        <th>
                            Matière
                        </th>

                        <th class="label-first no-sort">
                            coefficient
                        </th>

                        <th width="40">
                            
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($teacher->assignations as $assignation)
                        <tr>
                            <td>
                                {{ $assignation->classroom->name }} {{ $assignation->classroom->section }}
                            </td>
                            <td>
                                {{ $assignation->subject->name }}
                            </td>
                            <td>
                                {{ $assignation->coefficient }}
                            </td>

                            <td>
                                <div class="dropdown-cover">
                                    <button class="more-icon" data-target="dropdown-parent-{{ $assignation->id }}">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <div class="dropdown-items" id="dropdown-parent-{{ $assignation->id }}">
                                        <ul>
                                            {{-- <li>
                                                <a href="{{ route('assignations.show', $assignation->id) }}">
                                                    <div>
                                                        <i class="fa-solid fa-list-ul"></i>
                                                        Détails
                                                    </div>
                                                </a>
                                            </li> --}}

                                            <li>
                                                <a href="{{ route('assignations.edit', $assignation->id) }}">
                                                    <div>
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                        Modifier
                                                    </div>
                                                </a>
                                            </li>

                                            <li>
                                                <form action="{{ route('assignations.destroy', $assignation->id) }}"
                                                    method="post"
                                                    onclick="return confirm('Êtes-vous sûr(e) de vouloir supprimer cette assignation ? Cette action sera irréversible !')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button>
                                                        <i class="fa-solid fa-trash"></i>
                                                        Supprimer
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    </div>
@endsection

@section('js')
    <script>
        new DataTable('#datatables', {
            responsive: true,
            info: false,
            columnDefs: [{
                orderable: false,
                targets: [3],
            }]
        });
    </script>
@endsection

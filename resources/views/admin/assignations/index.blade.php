@extends('layouts.authchecked')

@section('title', 'Gestion des assignations')

@section('css')
    <style>
        h2,
        h3 {
            text-align: center;
            font-family: "Roboto", sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        th,
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #e0e0e0;
            text-align: left;
            font-size: 15px;
        }

        th {
            background: #ececec;
            font-weight: 600;
        }

        tr:hover {
            background: #f3f8ff;
            transition: background 0.2s ease;
        }
    </style>
@endsection

@section('content')
    <div>
        <h1>Gestion des assignations</h1>

        <a href="{{ route('assignations.create') }}">
            Ajouter une assignation
        </a>
    </div>

    @if ($message = Session::get('success'))
        <p class="alert-success">{{ $message }}</p>
    @endif


    @if ($assignations->isEmpty())
        <div style="text-align: center;">
            <p>--Aucune assignation n'a été enregistrée--</p><br />
            <img src="{{ asset('images/icons/trash-empty-svgrepo-com.png') }}" alt="" width="50">
        </div>
    @else
        <div class="datatables-cover">
            <table id="datatables" class="display">
                <thead>
                    <tr>
                        <th>Salle de classe</th>
                        <th>Enseignant</th>
                        <th>Matière</th>
                        <th width=""></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($assignations as $assignation)
                        <tr>

                            <td onclick='onRowClick("{{ $assignation->id }}")'>
                                {{ $assignation->classroom->name }} - {{ $assignation->classroom->section }}
                            </td>


                            <td onclick='onRowClick("{{ $assignation->id }}")'>
                                {{ $assignation->teacher->first_name }} {{ $assignation->teacher->last_name }}
                            </td>


                            <td onclick='onRowClick("{{ $assignation->id }}")'>
                                {{ $assignation->subject->name }}
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
        </div>
    @endif


@endsection



@section('js')
    <script>
        new DataTable('#datatables', {
            responsive: true,
        });

        function onRowClick(id) {
            window.location.href = `teachers/${id}`;
        }

        // petit effet de surbrillance au survol des lignes
        document.querySelectorAll("tbody tr").forEach(row => {
            row.addEventListener("mouseover", () => row.style.backgroundColor = "#eaf2ff");
            row.addEventListener("mouseout", () => row.style.backgroundColor = "");
        });
    </script>
@endsection

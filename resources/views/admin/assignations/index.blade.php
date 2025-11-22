@extends('layouts.authchecked')

@section('title', 'Gestion des assignations')


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
            <img src="{{ asset('images/icons/trash-empty-svgrepo-com.png') }}" alt="" width="50" class="empty-icon">
        </div>
    @else
        <div class="datatables-cover">
            <table id="datatables">
                <thead>
                    <tr>
                        <th>Salle de classe</th>
                        <th>Enseignant</th>
                        <th>Matière</th>
                        <th class="label-first no-sort">Coefficient</th>
                        <th>Nombre d'heure de cours</th>
                        <th width="40"></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($assignations as $assignation)
                        <tr>

                            <td onclick='onRowClick("{{ $assignation->teacher->id }}")'>
                                {{ $assignation->classroom->name }} - {{ $assignation->classroom->section }}
                            </td>


                            <td onclick='onRowClick("{{ $assignation->teacher->id }}")'>
                                {{ $assignation->teacher->last_name }} {{ $assignation->teacher->first_name }}
                            </td>


                            <td onclick='onRowClick("{{ $assignation->teacher->id }}")'>
                                {{ $assignation->subject->name }}
                            </td>

                            
                            <td onclick='onRowClick("{{ $assignation->teacher->id }}")'>
                                {{ $assignation->coefficient }}
                            </td>
                            
                            <td onclick='onRowClick("{{ $assignation->teacher->id }}")'>
                                {{ $assignation->weekly_hours }} Heures
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
            info: false,
            columnDefs: [{
                orderable: false,
                targets: [5],
            }]
        });

        function onRowClick(id) {
            window.location.href = `teachers/${id}`;
        }

    </script>
@endsection

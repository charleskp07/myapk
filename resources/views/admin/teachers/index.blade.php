@extends('layouts.authchecked')

@section('title', 'Gestion des enseignants')

@section('content')
    <div>
        <h1>Gestion des enseignants</h1>

        <a href="{{ route('teachers.create') }}">
            Ajouter un(e) enseignant(e)
        </a>
    </div>


    @if ($message = Session::get('success'))
        <p class="alert-success">{{ $message }}</p>
    @endif

    <br />

 
    @if ($teachers->isEmpty())
        <div style="text-align: center;">
            <p>--Aucun(e) enseignant(e) n'a été enregistré(e)--</p><br />
            <img src="{{ asset('images/icons/trash-empty-svgrepo-com.png') }}" alt="" width="50">
        </div>
    @else
        <div class="datatables-cover">
            <table id="datatables" class="display">
                <thead>
                    <tr>
                        <th>Nom de famille</th>
                        <th>Prénoms</th>
                        <th>Téléphone</th>
                        <th>Spécialité</th>
                        <th width="40"></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($teachers as $teacher)
                        <tr>
                            
                            <td onclick='onRowClick("{{ $teacher->id }}")'>
                                {{ $teacher->last_name }}
                            </td>

                            
                            <td onclick='onRowClick("{{ $teacher->id }}")'>
                                {{ $teacher->first_name }}
                            </td>
                            
                            
                            <td onclick='onRowClick("{{ $teacher->id }}")'>
                                {{ $teacher->phone ?? '-' }}
                            </td>
                  
                            
                            <td onclick='onRowClick("{{ $teacher->id }}")'>
                                {{ $teacher->speciality ?? '-' }}
                            </td>
                            
                            <td>
                                <div class="dropdown-cover">
                                    <button class="more-icon" data-target="dropdown-parent-{{ $teacher->id }}">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <div class="dropdown-items" id="dropdown-parent-{{ $teacher->id }}">
                                        <ul>
                                            <li>
                                                <a href="{{ route('teachers.show', $teacher->id) }}">
                                                    <div>
                                                        <i class="fa-solid fa-list-ul"></i>
                                                        Détails
                                                    </div>
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ route('teachers.edit', $teacher->id) }}">
                                                    <div>
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                        Modifier
                                                    </div>
                                                </a>
                                            </li>

                                            <li>
                                                <form action="{{ route('teachers.destroy', $teacher->id) }}" method="post"
                                                      onclick="return confirm('Êtes-vous sûr(e) de vouloir supprimer cet enseignant ? Cette action sera irréversible !')">
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
                targets: [4],
            }]
        });

        function onRowClick(id) {
            window.location.href = `teachers/${id}`;
        }
    </script>
@endsection

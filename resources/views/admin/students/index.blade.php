@extends('layouts.authchecked')

@section('title', 'Gestion des apprenants')

@section('content')
    <div>
        <h1>Gestion des apprenants</h1>

        <a href="{{ route('students.create') }}" >
            Ajouter un(e) apprenant(e)
        </a>
    </div>

    @if ($message = Session::get('success'))
        <p class="alert-success">{{ $message }}</p>
    @endif

    <br />


    @if ($students->isEmpty())
        <div style="text-align: center;">
            <p>--Aucun(e) apprenant(e) n'a été enregistré(e)--</p><br />
            <img src="{{ asset('images/icons/trash-empty-svgrepo-com.png') }}" alt="" width="50" class="empty-icon">
        </div>
    @else
        <div class="datatables-cover">
            <table id="datatables" class="display">
                <thead>
                    <tr>
                        <th>
                            Nom de famille
                        </th>

                        <th>
                            Prénoms
                        </th>

                        <th>
                            Classe
                        </th>

                        <th width="40">

                        </th>
                    </tr>

                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr>
                            <td onclick='onRowClick("{{ $student->id }}")'>
                                {{ $student->last_name }}
                            </td>

                            <td onclick='onRowClick("{{ $student->id }}")'>
                                {{ $student->first_name }}
                            </td>

                            <td onclick='onRowClick("{{ $student->id }}")'>
                                {{ $student->classroom->name }}-{{ $student->classroom->section }}
                            </td>

                            <td>
                                <div class="dropdown-cover">
                                    <button class="more-icon" data-target="dropdown-parent-{{ $student->id }}">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <div class="dropdown-items" id="dropdown-parent-{{ $student->id }}">
                                        <ul>
                                            <li>
                                                <a href="{{ route('students.show', $student->id) }}">
                                                    <div>
                                                        <i class="fa-solid fa-list-ul"></i>
                                                        Détails
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('students.edit', $student->id) }}">
                                                    <div>
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                        Modifier
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('students.destroy', $student->id) }}" method="post"
                                                    onclick="return confirm ('Êtes-vous sûr(e) de vouloir supprimer cet enregistrement ? Cette action sera irréversible !')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button>
                                                        <i class="fa-solid fa-trash"></i>
                                                        {{-- <i class="bi bi-trash3"></i> --}}
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
                targets: [3],
            }]
        })

        function onRowClick(id) {
            window.location.href = `students/${id}`
        }
    </script>

@endsection

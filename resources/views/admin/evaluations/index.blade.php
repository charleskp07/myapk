@extends('layouts.authchecked')

@section('title', 'Liste des évaluations')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/datatables/datatables.responsive.min.css') }}">
@endsection


@section('content')

    <div>
        <h1 class="">Liste des évaluations</h1>
        <br />
        <a href="{{ route('evaluations.create') }}"> Nouvelle évaluation</a>
    </div>

    <br /><br />

    @if (session('success'))
        <p class="alert-success">{{ session('success') }}</p>
    @endif
    <br />
    @if ($errors->any())
        <ul class="form-errors-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <br />
    @endif

    @if ($evaluations->isEmpty())
        <div style="text-align: center;">
            <p>--Aucune évaluation n'a été enregistré--</p><br />
            <img src="{{ asset('images/icons/trash-empty-svgrepo-com.png') }}" alt="" width="50" class="empty-icon">
        </div>
    @else
        <div>
            <table id="datatables">
                <thead>
                    <tr>
                        <th>
                            Decoupage
                        </th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Classe</th>
                        <th>Matière</th>
                        <th width="40"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($evaluations as $evaluation)
                        <tr>
                            <td onclick='onRowClick("{{ $evaluation->id }}")'>
                                {{ $evaluation->breakdown->type }}
                                {{ $evaluation->breakdown->value }}
                            </td>

                            <td onclick='onRowClick("{{ $evaluation->id }}")'>
                                {{ ucfirst(strtolower($evaluation->type)) }}
                            </td>

                            <td onclick='onRowClick("{{ $evaluation->id }}")'>
                                {{ \Carbon\Carbon::parse($evaluation->date)->format('d/m/Y') }}
                            </td>

                            <td onclick='onRowClick("{{ $evaluation->id }}")'>
                                {{ $evaluation->assignation->classroom->name }} -
                                {{ $evaluation->assignation->classroom->section }}
                            </td>

                            <td onclick='onRowClick("{{ $evaluation->id }}")'>
                                {{ $evaluation->assignation->subject->name }}
                            </td>

                            <td>
                                <div class="dropdown-cover">
                                    <button class="more-icon" data-target="dropdown-parent-{{ $evaluation->id }}">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <div class="dropdown-items" id="dropdown-parent-{{ $evaluation->id }}">
                                        <ul>
                                            <li>
                                                <a href="{{ route('evaluations.show', $evaluation->id) }}">
                                                    <div>
                                                        <i class="fa-solid fa-list-ul"></i>
                                                        Détails
                                                    </div>
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ route('evaluations.edit', $evaluation->id) }}">
                                                    <div>
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                        Modifier
                                                    </div>
                                                </a>
                                            </li>

                                            <li>
                                                <form action="{{ route('evaluations.destroy', $evaluation->id) }}"
                                                    method="post"
                                                    onclick="return confirm('Êtes-vous sûr(e) de vouloir supprimer cette evaluation ? Cette action sera irréversible !')">
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
            window.location.href = `evaluations/${id}`;
        }
    </script>
@endsection

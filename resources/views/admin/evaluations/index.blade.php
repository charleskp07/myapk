@extends('layouts.authchecked')

@section('title', 'Liste des évaluations')

@section('css')
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        th,
        td {
            padding: 10px 14px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background: #f4f4f4;
            font-weight: 600;
        }

        tr:hover {
            background: #f9f9ff;
            transition: 0.2s;
        }

        .actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn {
            padding: 5px 10px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-view {
            background: #e0f3ff;
            color: #0073e6;
        }

        .btn-edit {
            background: #fff3cd;
            color: #8a6d3b;
        }

        .btn-delete {
            background: #fde2e1;
            color: #d33;
            border: none;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <div>
        <h2 class="roboto-black text-center">Liste des évaluations</h2>
        <br />
        <a href="{{ route('evaluations.create') }}" class="btn btn-primary">+ Nouvelle évaluation</a>
        <br /><br />

        @if (session('success'))
            <p class="alert-success">{{ session('success') }}</p>
        @endif

        @if ($evaluations->isEmpty())
            <p class="text-center">Aucune évaluation enregistrée.</p>
        @else
            <table id="datatable">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>
                            Semestre/Trimestre
                        </th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Classe</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($evaluations as $evaluation)
                        <tr>
                            <td>{{ $evaluation->title }}</td>
                            <td>
                                {{ $evaluation->breakdown->name }}
                            </td>
                            <td>{{ ucfirst(strtolower($evaluation->type)) }}</td>
                            <td>{{ \Carbon\Carbon::parse($evaluation->date)->format('d/m/Y') }}</td>
                            <td>{{ $evaluation->assignation->classroom->name }} - {{ $evaluation->assignation->classroom->section }}</td>
                            <td class="">
                                
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
        new DataTable('#datatable', {
            responsive: true,
            pageLength: 10,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.1/i18n/fr-FR.json'
            }
        });
    </script>
@endsection

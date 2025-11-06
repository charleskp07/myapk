@extends('layouts.authchecked')

@section('title', 'Détails du Barème')

@section('content')
    <style>
        .bareme-container {
            max-width: 850px;
            margin: 0 auto;
            padding: 30px;
        }

        .bareme-card {
            background: #f9f9f9;
            border-radius: 12px;
            padding: 20px 30px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        }

        .bareme-card p {
            margin: 10px 0;
            font-size: 16px;
        }

        .bareme-card strong {
            color: #222;
        }

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

        .actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 6px;
            font-weight: 500;
            transition: 0.3s ease;
        }

        .btn-cancel {
            background: #ccc;
            color: #333;
        }

        .btn-cancel:hover {
            background: #b4b4b4;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0069d9;
        }
    </style>

    <div class="bareme-container">
        <h2>Détails du barème</h2>
        <br>

        <div class="bareme-card">
            <p><strong>Valeur :</strong> {{ $bareme->value }}</p>
            <p><strong>Date de création :</strong> {{ $bareme->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Dernière modification :</strong> {{ $bareme->updated_at->format('d/m/Y H:i') }}</p>
        </div>

        <br>

        <h3>Appréciations associées</h3>
        <br>

        <div>
            <a href="{{ route('admin.appreciations.create', ['bareme_id' => $bareme->id]) }}" class="btn btn-primary">Ajouter
                une appreciation</a>
        </div>

        <br>

        @if ($bareme->noteAppreciations->isEmpty())
            <div style="text-align: center">
                <p>--Aucune appréciation associée à ce barème.--</p><br />
                <img src="{{ asset('images/icons/trash-empty-svgrepo-com.png') }}" alt="" width="50">
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Appréciation</th>
                        <th>Valeur minimale</th>
                        <th>Valeur maximale</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bareme->noteAppreciations as $appreciation)
                        <tr>
                            <td>{{ $appreciation->appreciation }}</td>
                            <td>{{ $appreciation->min_value }}</td>
                            <td>{{ $appreciation->max_value }}</td>
                            <td>
                                <a href="{{route('admin.appreciations.edit', $appreciation->id)}}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    Modifier
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="actions">
            <a href="{{ route('admin.baremes.index') }}" class="btn btn-cancel">← Retour à la liste</a>

        </div>
    </div>

    <script>
        // petit effet de surbrillance au survol des lignes
        document.querySelectorAll("tbody tr").forEach(row => {
            row.addEventListener("mouseover", () => row.style.backgroundColor = "#eaf2ff");
            row.addEventListener("mouseout", () => row.style.backgroundColor = "");
        });
    </script>
@endsection

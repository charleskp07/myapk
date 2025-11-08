<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Liste des apprenants de la classe de {{ $classroom->name }}</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
            margin: 5mm 0mm 5mm 0mm;
        }

        h1 {
            text-align: center;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #f2f2f2;
        }

        tr:nth-child(even) {
            background: #fafafa;
        }
    </style>
</head>

<body>

    <h2 style="text-align: center">Liste des apprenants de la classe de {{ $classroom->name }} {{ $classroom->section }}</h2>

    <p style="text-align: center">
        <b>Effectif :</b>{{$classroom->students->count()}}
    </p>

    @if ($classroom->students->isEmpty())
        <div style="text-align: center">
            <p style="font-size: 20px">Aucun(e) apprenant(e) n'est ajouté(e) à la {{ $classroom->name }} {{ $classroom->section }}</p>
            <br />
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Nom</th>
                    <th>Prénoms</th>
                    <th>Date de naissance</th>
                    <th>Sexe</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($classroom->students as $index => $student)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ strtoupper($student->last_name) }}</td>
                        <td>{{ ucfirst($student->first_name) }}</td>
                        <td>{{ \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') }}</td>
                        <td>{{ $student->gender }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>

</html>

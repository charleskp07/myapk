<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Bulletin - {{ $student->last_name }}</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
            margin: 5mm 0mm 5mm 0mm;
        }

        h3,
        h4 {
            text-align: center;
            margin: 2px 0;
        }

        p {
            margin: 2px 0;
        }

        .bulletin-header {
            text-align: center;
            margin-bottom: 10px;
        }

        .bulletin-header h4 u {
            text-decoration: underline;
        }

        .student-info {
            margin: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 5px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            font-weight: bold;
        }

        tfoot th {
            text-align: center;
        }

        .section-title {
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .footer div p {
            margin: 2px 0;
        }

        .signatures {
            text-align: right;
        }

        .page-footer {
            text-align: right;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="bulletin-container">

        <div style="text-align: center;">
            <img src="{{ public_path('images/Lycee.png') }}" alt="" width="150">
        </div>
        <br /><br />
        <div class="bulletin-header">
            <h3>RÉPUBLIQUE TOGOLAISE</h3>
            <p><b>Travail - Liberté - Patrie</b></p>
            <p><b>DIRECTION RÉGIONALE DE L'ÉDUCATION</b></p>
            <p><b>ENSEIGNEMENT SECONDAIRE</b></p>
            <h4><b>LYCÉE AYIMOLOU</b></h4>
            <p>Année scolaire : {{ '2024 - 2025' }}</p>
            <h4>Bulletin d'évaluation du {{ $breakdown->type }} {{ $breakdown->value }}</h4>
        </div>
        <br />
        <hr>

        <div class="student-info">
            <p>
                <b>Nom :</b> {{ $student->last_name }} &nbsp;&nbsp;
                <b>Prénoms :</b> {{ $student->first_name }} &nbsp;&nbsp;
                <b>Classe :</b> {{ $student->classroom->name }} {{ $student->classroom->section }} &nbsp;&nbsp;
                <b>Sexe :</b> {{ $student->gender }} &nbsp;&nbsp;
            </p>
        </div>

        <table>
            <thead style="background-color: #EEE;">
                <tr>
                    <th>Matières</th>
                    <th>Interrogation</th>
                    <th>Devoir</th>
                    <th>Note de classe</th>
                    <th>Composition</th>
                    <th>Note Moy</th>
                    <th>Coeff</th>
                    <th>Note déf</th>
                    <th>Appreciation</th>
                    <th>Professeur</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($results as $item)
                    <tr>
                        <td style="text-align: left">{{ $item['subject'] }}</td>
                        <td>{{ $item['note_interro'] ? number_format($item['note_interro'], 2) : '-' }}</td>
                        <td>{{ $item['note_devoir'] ? number_format($item['note_devoir'], 2) : '-' }}</td>
                        <td>{{ $item['note_classe'] ? number_format($item['note_classe'], 2) : '-' }}</td>
                        <td>{{ $item['note_composition'] ? number_format($item['note_composition'], 2) : '-' }}</td>
                        <td><b>{{ number_format($item['note_finale'], 2) }}</b></td>
                        <td>{{ $item['coefficient'] }}</td>
                        <td><b>{{ number_format($item['note_def'], 2) }}</b></td>
                        <td>
                            @if ($item['note_finale'] < 5)
                                Médiocre
                            @elseif($item['note_finale'] < 10)
                                Insuffisant
                            @elseif($item['note_finale'] < 12)
                                Passable
                            @elseif($item['note_finale'] < 14)
                                Assez-bien
                            @elseif($item['note_finale'] < 16)
                                Bien
                            @elseif($item['note_finale'] < 19)
                                Très-bien
                            @else
                                Excellent
                            @endif
                        </td>
                        <td style="text-align: left">
                            {{ strtoupper($item['teacher']->last_name ?? '') }}
                            {{-- {{ ucfirst($item['teacher']->first_name ?? '') }} --}}

                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6">Total</th>
                    <th>{{ number_format($totalCoeff, 2) }}</th>
                    <th>{{ number_format($totalNote, 2) }}</th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan="7">Moyenne générale</th>
                    <th>{{ number_format($moyenneGenerale, 2) }}</th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>


        <p class="section-title">Observations du Conseil de Classe :</p>
        <p>{{ $observations ?? 'Bon travail, continuez vos efforts.' }}</p>


        <div class="footer">
            <div>
                <p><b>Moyenne générale :</b> {{ number_format($moyenneGenerale, 2) }}</p>
                <p>
                    <b>Rang :</b>
                    @if ($rank == 1)
                        @if ($student->gender === 'Féminin')
                            1ère
                        @else
                            1er
                        @endif
                    @else
                        {{ $rank }}ème
                    @endif

                    sur {{ count($student->classroom->students) }}
                </p>
                <p>
                    <b>Mention :</b>
                    @if ($moyenneGenerale < 5)
                        Médiocre
                    @elseif($moyenneGenerale < 10)
                        Insuffisant
                    @elseif($moyenneGenerale < 12)
                        Passable
                    @elseif($moyenneGenerale < 14)
                        Assez-bien
                    @elseif($moyenneGenerale < 16)
                        Bien
                    @elseif($moyenneGenerale < 19)
                        Très-bien
                    @else
                        Excellent
                    @endif
                </p>
                <p><b>Plus forte moyenne :</b> {{ number_format($maxAverage, 2) }}/20</p>
                <p><b>Plus faible moyenne :</b> {{ number_format($moyenneGenerale, 2) }}/20</p>

                @if ($moyenneAnnuel)
                    <p>
                        @if ($student->classroom->level === app\Enums\ClassroomLevelEnums::LYCEE->value )
                            <b>Moyenne 1er Semestre : </b>  {{ number_format($moyenneSem1, 2) }}/20 <br />
                            <b>Moyenne 2eme Semestre : </b>  {{ number_format($moyenneGenerale, 2) }}/20
                        @endif
                    </p>
                    <p><b>Moyenne annuelle :</b> {{ number_format($moyenneAnnuel, 2) }}/20</p>
                    <p><b>Décision :</b> {{ $passage }}</p>
                @endif

            </div>

            <div class="signatures">
                <p><b>Le Professeur Principal</b></p>
                <p>{{ $student->classroom->teacher->last_name }} {{ $student->classroom->teacher->first_name }}</p>
            </div>
        </div>

        <br /><br /><br /><br /><br /><br /><br />

        <p class="page-footer">
            Fait à Lomé le {{ now()->format('d/m/Y') }}
        </p>

        <br /><br />
        <p style="text-align: center">
            Le Provieur
        </p>

    </div>
</body>

</html>

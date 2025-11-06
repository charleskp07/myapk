@extends('layouts.authchecked')

@section('title', 'Bulletin de notes')

@section('css')
    <style>
        .bulletin-container {
            background: white;
            color: black;
            padding: 30px;
            width: 794px;
            height: 1123px;
            margin: 0 auto;
            border: 1px solid #000;
            font-family: "Times New Roman", serif;
        }

        .bulletin-header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .bulletin-header h3,
        .bulletin-header h4,
        .bulletin-header p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 14px;
        }

        table th,
        table td {
            border: 1px solid #000;
            text-align: start;
            padding: 5px;
        }

        .section-title {
            font-weight: bold;
            margin-top: 10px;
            text-decoration: underline;
        }

        .appreciation {
            text-align: left;
            padding-left: 10px;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
    </style>
@endsection

@section('content')

    <div class="bulletin-container">
        <br /><br />
        <div style="text-align: center;">
            <img src="{{ asset('images/Lycee.png') }}" alt="" width="150">
        </div>
        <br />
        <div class="bulletin-header">
            <h3>RÉPUBLIQUE TOGOLAISE</h3>
            <p><b>Travail - Liberté - Patrie</b></p>
            <p><b>DIRECTION RÉGIONALE DE L'ÉDUCATION</b></p>
            <p><b>ENSEIGNEMENT SECONDAIRE</b></p>
            <h4><b>LYCEE AYIMOLOU</b></h4>
            <p>Année scolaire : {{ '2024 - 2025' }}</p>
            <h4><u>BULLETIN D'ÉVALUATION DU {{ $breakdown->type }} {{ $breakdown->value }}</u></h4>
            <br />
        </div>

        <p>
            <b>Nom :</b> {{ $student->last_name }} &nbsp;&nbsp;
            <b>Prénoms :</b> {{ $student->first_name }} &nbsp;&nbsp;
            <b>Classe :</b> {{ $student->classroom->name }} {{ $student->classroom->section }}
        </p>

        <table>
            <thead>
                <tr>
                    <th>Matières</th>
                    <th>Interrogation</th>
                    <th>Devoir</th>
                    <th>Note de classe</th>
                    <th>Composition</th>
                    <th>Note Moyenne</th>
                    <th>Coeff</th>
                    <th>Note definitive</th>
                    <th>Professeur</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($results as $item)
                    <tr>
                        <td>{{ $item['subject'] }}</td>
                        <td>{{ $item['note_interro'] ? number_format($item['note_interro'], 2) : '-' }}</td>
                        <td>{{ $item['note_devoir'] ? number_format($item['note_devoir'], 2) : '-' }}</td>
                        <td>{{ $item['note_classe'] ? number_format($item['note_classe'], 2) : '-' }}</td>
                        <td>{{ $item['note_composition'] ? number_format($item['note_composition'], 2) : '-' }}</td>
                        <td><b>{{ number_format($item['note_finale'], 2) }}</b></td>
                        <td>{{ $item['coefficient'] }}</td>
                        <td><b>{{ number_format($item['note_def'], 2) }}</b></td>
                        <td>{{ strtoupper($item['teacher']->last_name ?? '') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6">Total</th>
                    <th>{{ number_format($totalCoeff, 2) }}</th>
                    <th>{{ number_format($totalNote, 2) }}</th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan="7">Moyenne générale</th>
                    <th>{{ number_format($moyenneGenerale, 2) }}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>

        <p class="section-title">Observations du Conseil de Classe :</p>
        <p>{{ $observations ?? 'Bon travail, continuez vos efforts.' }}</p>

        <div class="footer">
            <div>
                <p><b>Moyenne générale :</b> {{ number_format($moyenneGenerale, 2) }}</p>
                <p><b>Rang :</b> {{ $rank }} / {{ count($student->classroom->students) }}</p>
                <p>
                    <b>Mention :</b>
                    @if ($moyenneGenerale >= 0 && $moyenneGenerale <= 4.99)
                        Médiocre
                    @else
                        @if ($moyenneGenerale >= 5 && $moyenneGenerale <= 9.99)
                            Insuffusant
                        @else
                            @if ($moyenneGenerale >= 10 && $moyenneGenerale <= 11.99)
                                Passable
                            @else
                                @if ($moyenneGenerale >= 12 && $moyenneGenerale <= 13.99)
                                    Assez-bien
                                @else
                                    @if ($moyenneGenerale >= 14 && $moyenneGenerale <= 15.99)
                                        Bien
                                    @else
                                        @if ($moyenneGenerale >= 16 && $moyenneGenerale <= 18.99)
                                            Très-bien
                                        @else
                                            @if ($moyenneGenerale >= 19 && $moyenneGenerale <= 20)
                                                Excellent
                                            @endif
                                        @endif
                                    @endif
                                @endif
                            @endif
                        @endif
                    @endif
                </p>

                <p><b>Meilleure moyenne :</b> {{ number_format($maxAverage, 2) }}/20</p>

                <p><b>Plus faible moyenne :</b> {{ number_format($minAverage, 2) }}/20</p>

            </div>

            <div class="signatures">
                <p><b>Le Professeur Principal</b></p>
                <p>{{ $student->classroom->teacher->last_name }} {{ $student->classroom->teacher->first_name }}</p>
                <br /><br /><br /><br /><br /><br />
            </div>

        </div>

        <p style="text-align: right; margin-top: 10px;">
            Fait à Lomé le {{ now()->format('d/m/Y') }}
        </p>
    </div>
@endsection

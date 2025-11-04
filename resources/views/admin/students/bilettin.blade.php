@extends('layouts.authchecked')

@section('title', 'Bulletin de notes')

@section('content')
    <style>
        .bulletin-container {
            background: white;
            color: black;
            padding: 30px;
            max-width: 900px;
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
            text-align: center;
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

        .signatures {
            text-align: center;
            margin-top: 20px;
        }

        .btn-print {
            background: #2b6cb0;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            float: right;
            margin-bottom: 10px;
        }

        @media print {
            .btn-print {
                display: none;
            }

            body {
                background: white;
            }
        }
    </style>

    <a href="#" onclick="window.print()" class="btn-print">
        <i class="bi bi-printer"></i> Imprimer le bulletin
    </a>

    <div class="bulletin-container">
        <div class="bulletin-header">
            <h3>RÉPUBLIQUE TOGOLAISE</h3>
            <p><b>Travail - Liberté - Patrie</b></p>
            <p><b>DIRECTION RÉGIONALE DE L’ÉDUCATION - KARA</b></p>
            <p><b>ENSEIGNEMENT SECONDAIRE</b></p>
            <h4>{{ $etablissement->name ?? 'Lycée Maria-Romano de Niamtougou' }}</h4>
            <p>Année scolaire : {{ $annee ?? '2024 - 2025' }}</p>
            <h4><u>BULLETIN D’ÉVALUATION DU {{ $semestre ?? '2ᵉ' }} TRIMESTRE</u></h4>
        </div>

        <p><b>Nom :</b> {{ $etudiant->last_name }} &nbsp;&nbsp;
            <b>Prénom :</b> {{ $etudiant->first_name }}
        </p>

        <table>
            <thead>
                <tr>
                    <th>Matières</th>
                    <th>Notes /20</th>
                    <th>Moyenne</th>
                    <th>Appréciation du Professeur</th>
                    <th>Professeur</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($notes as $note)
                    <tr>
                        <td>{{ $note->evaluation->assignation->subject->name }}</td>
                        <td>{{ number_format($note->value, 2) }}</td>
                        <td>{{ number_format($note->value, 2) }}</td>
                        <td class="appreciation">
                            {{ $note->appreciation->label ?? '---' }}
                        </td>
                        <td>{{ strtoupper($note->evaluation->assignation->teacher->last_name) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">Total</th>
                    <th>{{ number_format($moyenneTotale, 2) }}</th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        </table>

        <p class="section-title">Observations du Conseil de Classe :</p>
        <p>{{ $observations ?? 'Bon travail, continuez vos efforts.' }}</p>

        <div class="footer">
            <div>
                <p><b>Moyenne générale :</b> {{ number_format($moyenneGenerale, 2) }}</p>
                <p><b>Rang :</b> {{ $rang ?? '--' }}</p>
            </div>
            <div class="signatures">
                <p><b>Le Professeur Principal</b></p>
                <br><br>
                <p>{{ $profPrincipal ?? 'LEMOU TANANG YAKO' }}</p>
            </div>
        </div>

        <p style="text-align: right; margin-top: 10px;">
            Fait à {{ $lieu ?? 'NIAMTOUGOU' }}, le {{ now()->format('d/m/Y') }}
        </p>
    </div>
@endsection

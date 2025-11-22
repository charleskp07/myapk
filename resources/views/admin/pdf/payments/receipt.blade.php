@php
    $school = \App\Models\SchoolSetting::first();

@endphp

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reçu de Paiement - {{ mb_strtoupper($school?->name) }}</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 11px;
            margin-right: 50px;
            padding: 0;
        }

        .receipt {
            width: 100%;
            min-height: 430px;
            padding: 25px;
            position: relative;
            box-sizing: border-box;
        }

        .pay-info {
            position: absolute;
            top: 30;
            right: 40;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            /* padding-right: 50px; */
            font-size: 10px;
        }

        .table th,
        .table td {
            border: 1px solid #ccc;
            padding: 5px;
        }

        .table th {
            background: #f5f5f5;
        }


        .cut-line {
            border-top: 1px dashed #000;
            text-align: center;
            position: relative;
        }

        .cut-line span {
            position: absolute;
            top: -8px;
            left: 5px;
            font-size: 10px;
            background: white;
            padding: 0 3px;

        }
    </style>
</head>

<body>
    @foreach (['COPIE CLIENT', 'COPIE ADMINISTRATION'] as $copyType)
        <div class="receipt">

            <div class="header"
                style="display: grid;
                grid-template-columns: 1fr 1fr;
                align-items: start;
                gap: 10px; ">
                <div class="school-info">
                    <img src="{{ public_path('storage/' . $school?->logo) }}" alt="" width="120">
                    <h3>{{ mb_strtoupper($school?->name) }}</h3>
                    <p><strong>Année scolaire:</strong> {{ $school?->academic_year }}</p>
                    <p> <b>Adresse : </b>{{ $school?->address }} <b>Tel : </b> {{ $school?->phone }}</p>
                </div>


                <div class="pay-info">
                    <p><strong>Reçu N°:</strong> {{ $payment->reference }} <br>
                    </p>
                    <p><strong>Date:</strong> {{ now()->format('d/m/Y H:i') }}</p>
                    <p><strong>Élève:</strong> {{ $payment->student->last_name }} {{ $payment->student->first_name }}
                    </p>
                    <p><strong>Classe:</strong>
                        {{ $payment->student->classroom->name ?? '' }}
                        {{ $payment->student->classroom->section ?? '' }}
                    </p>
                </div>
            </div>

            <h3 style="text-align: center">REÇU DE PAIEMENT DE {{ mb_strtoupper($payment->fee->name) }} </h3>

            <table class="table">
                <tr>
                    <th>Date</th>
                    <th>Mode de paiement</th>
                    <th>Référence</th>
                    <th>Montant</th>
                </tr>
                @foreach ($allPayments as $p)
                    <tr @if ($p->id == $payment->id) style="background:#e7f7ff; text-align: center;" @endif>
                        <td>{{ \Carbon\Carbon::parse($p->payment_date)->format('d/m/Y') }}</td>
                        <td>{{ ucfirst($p->payment_method) }}</td>
                        <td>{{ $p->reference }}</td>
                        <td>{{ number_format($p->amount, 0, ',', ' ') }} XOF</td>
                    </tr>
                @endforeach
            </table>

            <br />
            <p><strong>Frais:</strong> {{ number_format($payment->fee->amount, 0, ',', ' ') }} XOF</p>
            <p><strong>Total payé:</strong> {{ number_format($totalPaid, 0, ',', ' ') }} XOF</p>

            @if ($remaining > 0)
                <p><strong>Restant:</strong> {{ number_format($remaining, 0, ',', ' ') }} XOF</p>
            @endif


            <p style="text-align:right; margin-top: 20px;">
                <strong>Le Proviseur</strong><br>
                {{ $school?->principal }}
            </p>


            <div style="text-align: center">
                <p><em>Ce reçu est généré le {{ now()->format('d/m/Y à H:i') }}</em></p>
                <p><em>Merci pour votre confiance</em></p>
                <p style="font-size: 10px">&copy; {{ date('Y') }} - {{ mb_strtoupper($school?->name) }}</p>
            </div>

        </div>

        @if (!$loop->last)
            <div class="cut-line">
                <span> Découper ici</span>
            </div>
        @endif
    @endforeach

</body>

</html>

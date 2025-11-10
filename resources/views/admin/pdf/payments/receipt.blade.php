@php
    $school = \App\Models\SchoolSetting::first();
@endphp

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reçu de Paiement - {{ mb_strtoupper($school->name) }}</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
        }

        .school-info {
            margin-bottom: 15px;
        }

        .receipt-info {
            margin: 20px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .total {
            font-weight: bold;
            font-size: 14px;
        }

        .footer {
            text-align: center;
        }

        .signature {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('storage/' . $school->logo) }}" alt="Logo" class="logo" width="150">
        <h2>{{ mb_strtoupper($school->name) }}</h2>
        <p>{{ $school->address }} - {{ $school->phone }}</p>
        <h3>REÇU DE PAIEMENT DE {{ mb_strtoupper($payment->fee->name) }} </h3>
    </div>
    <hr style="height: 1px; background-color: #333; border: none;">

    <div class="school-info">
        <p><strong>Année Scolaire:</strong> {{ $school->academic_year }}</p>
        <p><strong>Date d'émission:</strong> {{ now()->format('d/m/Y H:i') }}</p>
        <p><strong>N° Reçu:</strong> {{ $payment->reference }}</p>
    </div>

    <div class="student-info">
        <h4>INFORMATIONS ÉLÈVE</h4>
        <p><strong>Nom & Prénom:</strong> {{ $payment->student->last_name }} {{ $payment->student->first_name }}</p>
        <p><strong>Niveau:</strong> {{ $payment->student->classroom->level ?? '---' }}</p>
        <p><strong>Classe:</strong> {{ $payment->student->classroom->name ?? '---' }}
            {{ $payment->student->classroom->section ?? '---' }}</p>
    </div>

    <div class="payment-info">
        <h4>DÉTAILS DES PAIEMENTS</h4>
        <table class="table">
            <tr>
                <th>Date Paiement</th>
                <th>Mode Paiement</th>
                <th>Référence</th>
                <th>Montant</th>
            </tr>
            @foreach ($allPayments as $p)
                <tr @if ($p->id == $payment->id) style="background-color:#e0f7fa;" @endif>
                    <td>{{ \Carbon\Carbon::parse($p->payment_date)->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($p->payment_method) }}</td>
                    <td>{{ $p->reference }}</td>
                    <td>{{ number_format($p->amount, 0, ',', ' ') }} XOF</td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="total">
        <p>MONTANT DU FRAIS: <strong>{{ number_format($payment->fee->amount, 0, ',', ' ') }} XOF</strong></p>
        <p>TOTAL PAYÉ: <strong>{{ number_format($totalPaid, 0, ',', ' ') }} XOF</strong></p>
        @if ($remaining > 0)
            <p>RESTANT À PAYER: <strong>{{ number_format($remaining, 0, ',', ' ') }} XOF</strong></p>
        @endif
    </div>

    <div class="signature">
        <br /><br /><br /><br /><br /><br /><br /><br /><br />
        <p><b>Le Proviseur</b></p>
        {{ $school->principal }}
    </div>
    <br /><br /><br /><br />
    <div class="footer">
        <p><em>Ce reçu est généré le {{ now()->format('d/m/Y à H:i') }}</em></p>
        <p><em>Merci pour votre confiance</em></p>
        <p style="font-size: 10px">&copy; {{ date('Y') }} - {{ mb_strtoupper($school->name) }}</p>
    </div>
</body>

</html>

<!-- resources/views/receipts/template.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reçu de Paiement - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
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
            margin-top: 50px;
            border-top: 1px solid #333;
            padding-top: 10px;
            text-align: center;
        }

        .signature {
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2></h2>
        <p>BP 1234, Ville - Tél: +225 01 02 03 04</p>
        <h3>REÇU DE PAIEMENT DE SCOLARITÉ</h3>
    </div>

    <div class="school-info">
        <p><strong>Année Scolaire:</strong>{{ config('school.school_year') }}</p>
        <p><strong>Date d'émission:</strong> {{ now()->format('d/m/Y H:i') }}</p>
        <p><strong>N° Reçu:</strong> {{ $payment->reference }}</p>
    </div>

    <div class="student-info">
        <h4>INFORMATIONS ÉLÈVE</h4>
        <p><strong>Matricule:</strong> {{ $payment->student->identifier ?? 'N/A' }}</p>
        <p><strong>Nom & Prénom:</strong> {{ $payment->student->last_name }} {{ $payment->student->first_name }}</p>
        <p><strong>Classe:</strong> {{ $payment->student->classroom->name ?? '---' }}</p>
        <p><strong>Niveau:</strong> {{ $payment->student->classroom->level->name ?? '---' }}</p>
    </div>

    <div class="payment-info">
        <h4>DÉTAILS DU PAIEMENT</h4>
        <table class="table">
            <tr>
                <th>Date Paiement</th>
                <th>Mode Paiement</th>
                <th>Référence</th>
                <th>Montant</th>
            </tr>
            <tr>
                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                <td>{{ ucfirst($payment->payment_method) }}</td>
                <td>{{ $payment->reference }}</td>
                <td>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
            </tr>
        </table>
    </div>

    <div class="total">
        <p>MONTANT TOTAL: <strong>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</strong></p>
        <p>Arrêté la présente somme à: <strong>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</strong></p>
    </div>

    <div class="signature">
        <p>Le Receveur</p>
        <p>_________________________</p>
        <p>Nom & Signature</p>
    </div>

    <div class="footer">
        <p><em>Ce reçu est généré automatiquement le {{ now()->format('d/m/Y à H:i') }}</em></p>
        <p><em>Merci pour votre confiance</em></p>
    </div>
</body>

</html>

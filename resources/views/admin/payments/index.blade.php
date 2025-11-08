@extends('layouts.authchecked')

@section('title', 'Payements')

@section('content')
    <div>
        <h1>Paiements reçu</h1>
    </div>

    @if ($message = Session::get('success'))
        <p class="alert-success">{{ $message }}</p>
    @endif

    @if ($payments->isEmpty())
        <div style="text-align: center;">
            <p>--Aucun paiement n'a été enregistré--</p><br />
            <img src="{{ asset('images/icons/trash-empty-svgrepo-com.png') }}" alt="" width="50">
        </div>
    @else
        <table style="width:100%; border-collapse: collapse; text-align: center;" id="datatables">
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Élève</th>
                    <th>Frais</th>
                    <th>Date</th>
                    <th>Montant</th>
                    <th>Méthode</th>
                    <th>

                    </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalCollected = 0;
                @endphp

                @foreach ($payments as $payment)
                    @php
                        $totalCollected += $payment->amount;
                    @endphp
                    <tr>
                        <td>{{ $payment->reference }}</td>
                        <td>{{ $payment->student->last_name }} {{ $payment->student->first_name }}</td>
                        <td>{{ $payment->fee->name ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                        <td>{{ number_format($payment->amount, 0, ',', ' ') }} XOF</td>
                        <td>{{ ucfirst($payment->payment_method) }}</td>
                        <td>
                            <a href="{{ route('admin.payments.receipt', $payment->id) }}" target="_bank">
                                Voir le reçu
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                @php
                    $totalCollected = App\Models\Payment::sum('amount');

                    $totalExpected = 0;

                    $classrooms = App\Models\Classroom::with('fees', 'students')->get();

                    foreach ($classrooms as $classroom) {
                        $studentCount = $classroom->students->count(); // nombre d'élèves dans la classe

                        foreach ($classroom->fees as $fee) {
                            $totalExpected += $fee->amount * $studentCount;
                        }
                    }
                @endphp
                
                <tr>
                    <th colspan="4" style="text-align:right;">Total Collecté :</th>
                    <th colspan="3" class="{{ ($totalExpected - $totalCollected) > $totalCollected ? 'form-errors-list' : 'alert-success' }}">{{ number_format($totalCollected, 0, ',', ' ') }} FCFA
                    </th>
                </tr>
            </tfoot>
        </table>
    @endif


@endsection

@section('js')

    <script>
        new DataTable('#datatables', {
            responsive: true,
            info: false,
            columnDefs: [{
                orderable: false,
                targets: [6],
            }]
        })
    </script>

@endsection

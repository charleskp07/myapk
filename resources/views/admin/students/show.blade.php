@extends('layouts.authchecked')

@section('title', 'Details d\'un(e) apprenant(e)')


@section('content')
    <div>
        <div class="back-btn">
            <a href="{{ url()->previous() }}">
                <i class="bi bi-arrow-left"></i>
                Retour
            </a>
        </div>

        <h1>{{ $student->last_name }} {{ $student->first_name }}</h1>

        <br />
        <div style="display: flex; gap: 20px;">
            <img src="{{ $student->photo_url }}" alt="{{ $student->full_name }}" width="200"
                style="border-radius: 20px; aspect-ratio: 1/1;">

            <div>
                <p>
                    <strong>Classe:</strong>
                    {{ $student->classroom->name }} {{ $student->classroom->section }}<br>
                </p>

                <p>
                    <strong>Email:</strong>
                    {{ $student->email }}<br>
                </p>

                <p>
                    <strong>Téléphone:</strong>
                    {{ $student->phone }}
                </p>

                <p>
                    <strong>Age:</strong>
                    {{ $student->age }} ans
                </p>

                <p>
                    <strong>Date et lieu de naissance:</strong>
                    Né le {{ \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') }} à
                    {{ $student->place_of_birth }}
                </p>

                <p>
                    <strong>Genre:</strong>
                    {{ $student->gender }}
                </p>

                <p>
                    <strong>Nationalité:</strong>
                    {{ $student->nationality }}
                </p>
                <br />

                <p>
                    <a href="{{ route('students.edit', $student->id) }}">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Modifier
                    </a>
                </p>

                <p>
                    @foreach ($breakdowns as $breakdown)
                        {{-- <a href="{{ route('admin.bulletin.view', ['student_id' => $student->id, 'breakdown_id' => $breakdown->id]) }}">
                            Voir bulletin du {{ $breakdown->name }}
                            <i class="bi bi-arrow-right"></i>
                        </a> --}}
                        <a href="{{ route('admin.bulletin.pdf', ['student_id' => $student->id, 'breakdown_id' => $breakdown->id]) }}"
                            target="_blank">
                            Télécharger le Bulletin du {{ $breakdown->type }} {{ $breakdown->value }} ( Version PDF)
                        </a>
                        <br />
                    @endforeach
                </p>

            </div>
        </div>

        <br />
        <br />

        <br />
        <div>
            <h2>Historique des paiements</h2>
            @php
                $hasPendingPayment = $student->classroom->fees
                    ->filter(function ($fee) use ($student) {
                        $totalPaid = $student->payments->where('fee_id', $fee->id)->sum('amount');
                        return $totalPaid < $fee->amount;
                    })
                    ->isNotEmpty();
            @endphp

            @if ($hasPendingPayment)
                <a href="{{ route('payments.create', ['student_id' => $student->id]) }}">
                    Ajouter un paiement
                </a>
            @endif
        </div>
        <br />



        @if ($student->payments->isEmpty())
            <div style="text-align: center">
                <p>--Aucune paiement enregistrée pour cet apprenant--</p>
                <img src="{{ asset('images/icons/trash-empty-svgrepo-com.png') }}" alt="" width="50px">
            </div>
        @else
            <table id="paymentDatatables" style="width:100%; border-collapse: collapse;text-align: center;">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Date Paiement</th>
                        <th>Frais</th>
                        <th>Montant</th>
                        <th>Méthode de paiement</th>
                        <th>

                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($student->payments as $payment)
                        <tr>
                            <td>{{ $payment->reference }}</td>
                            <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                            <td>{{ $payment->fee->name ?? '-' }}</td>
                            <td>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
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
            </table>
        @endif

        <br />
        <br />
        <hr>

        <br />
        <h2>Liste des notes</h2>
        <br />

        @if (!$student->notes->isEmpty())
            <table id="datatables">
                <thead>
                    <tr>
                        <th>
                            {{ $student->classroom->level == 'Lycée' ? 'Semestre' : 'Trimestre' }}
                        </th>

                        <th>
                            Matière
                        </th>

                        <th>
                            Type
                        </th>

                        <th>
                            Note
                        </th>

                        <th>
                            Appreciation
                        </th>

                        <th>
                            statut
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($student->notes as $note)
                        <tr>
                            <td>
                                {{ $note->evaluation->breakdown->type }}
                                {{ $note->evaluation->breakdown->value }}
                            </td>
                            <td>
                                {{ $note->evaluation->assignation->subject->name }}
                            </td>
                            <td>
                                {{ $note->evaluation->type }}
                            </td>
                            <td>
                                {{ $note->value }} / {{ $note->evaluation->bareme->value }}
                            </td>
                            <td>
                                {{ $note->appreciation?->appreciation }}
                            </td>

                            <td>
                                @if ($note->evaluation->bareme->value = 20)
                                    @if ($note->value >= 10)
                                        <p style="color: green;">Validé</p>
                                    @else
                                        <p style="color: red;">Non-validé</p>
                                    @endif
                                @else
                                    @if ($note->evaluation->bareme->value = 10)
                                        @if ($note->value >= 5)
                                            <p style="color: green;">Validé</p>
                                        @else
                                            <p style="color: red;">Non-validé</p>
                                        @endif
                                    @else
                                        @if ($note->evaluation->bareme->value = 5)
                                            @if ($note->value >= 2.5)
                                                <p style="color: green;">Validé</p>
                                            @else
                                                <p style="color: red;">Non-validé</p>
                                            @endif
                                        @endif
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center">
                <p>--Aucune note enregistrée pour cet apprenant--</p>
                <img src="{{ asset('images/icons/trash-empty-svgrepo-com.png') }}" alt="" width="50px">
            </div>
        @endif
    </div>

@endsection

@section('js')
    <script>
        new DataTable('#datatables', {
            responsive: true,
            info: false,
        });

        new DataTable('#paymentDatatables', {
            responsive: true,
            info: false,
            columnDefs: [{
                orderable: false,
                targets: [5],
            }]
        })
    </script>
@endsection

@extends('layouts.authchecked')

@section('title', 'Modifier un paiement')

@section('content')
    <div>
        <div>
            <h2>Modification du Paiement</h2>
            <p>Modifiez les informations ci-dessous.</p>
            <br />

            @if ($errors->any())
                <ul class="form-errors-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <br />
            @endif

            @if ($message = Session::get('success'))
                <p class="alert-success">{{ $message }}</p>
                <br />
            @endif

            <form action="{{ route('payments.update', $payment->id) }}" method="POST">
                @csrf
                @method('PUT')


                <div class="input-cover">
                    <label for="student_id">Élève</label>
                    <select name="student_id" id="student_id">
                        <option value="">Sélectionnez un élève</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}"
                                {{ old('student_id', $payment->student_id) == $student->id ? 'selected' : '' }}>
                                {{ $student->first_name }} {{ $student->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="input-cover">
                    <label for="fee_id">Frais</label>
                    <select name="fee_id" id="fee_id" required>
                        <option value="">Sélectionnez un frais</option>
                        @foreach ($fees as $fee)
                            <option value="{{ $fee->id }}"
                                {{ old('fee_id', $payment->fee_id) == $fee->id ? 'selected' : '' }}>
                                {{ $fee->name }} - {{ number_format($fee->amount, 0, ',', ' ') }} XOF
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="input-cover">
                    <label for="amount">Montant</label>
                    <input type="number" name="amount" id="amount" step="100"
                        value="{{ old('amount', $payment->amount) }}" placeholder="Ex: 10000">
                </div>


                <div class="input-cover">
                    <label for="payment_method">Méthode de paiement</label>
                    <select name="payment_method" id="payment_method">
                        <option value="">Sélectionnez une méthode</option>

                        @foreach (\App\Enums\PaymentTypeEnums::cases() as $method)
                            <option value="{{ $method->value }}"
                                {{ old('payment_method', $payment->payment_method) === $method->value ? 'selected' : '' }}>
                                {{ $method->value }}
                            </option>
                        @endforeach

                    </select>
                </div>


                <div class="input-cover">
                    <label for="payment_date">Date de paiement</label>
                    <input type="date" name="payment_date" id="payment_date"
                        value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}">
                </div>


                <div class="input-cover">
                    <label for="note">Note / Observation</label>
                    <textarea name="note" id="note" rows="3">{{ old('note', $payment->note) }}</textarea>
                </div>

                <div>
                    <button type="submit">Mettre à jour</button>
                    <a href="{{ url()->previous() }}">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection

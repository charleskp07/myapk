<?php

namespace App\Http\Requests;

use App\Enums\PaymentTypeEnums;
use App\Models\Fee;
use App\Models\Payment;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'fee_id' => 'required|exists:fees,id',
            'amount' => 'required|numeric|min:100',
            'payment_method' => [
                'nullable',
                'string',
                'in:' . implode(',', array_map(fn($m) => $m->value, PaymentTypeEnums::cases()))
            ],
            'payment_date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'Veuillez sélectionner un élève.',
            'student_id.exists' => "L'élève sélectionné est invalide.",
            'fee_id.required' => 'Veuillez sélectionner un frais.',
            'fee_id.exists' => 'Le frais sélectionné est invalide.',
            'amount.required' => 'Veuillez indiquer un montant.',
            'amount.numeric' => 'Le montant doit être un nombre.',
            'amount.min' => 'Le montant doit être au moins de 100 XOF.',
            'payment_method.in' => 'La méthode de paiement sélectionnée est invalide.',
            'payment_date.required' => 'Veuillez indiquer une date de paiement.',
            'payment_date.date' => 'La date de paiement n\'est pas valide.',
        ];
    }


    /**
     * Validation personnalisée : empêche de dépasser le montant du frais.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $fee = Fee::find($this->fee_id);

            if ($fee) {
                $totalPaid = Payment::where('student_id', $this->student_id)
                    ->where('fee_id', $this->fee_id)
                    ->sum('amount');

                $newTotal = $totalPaid + $this->amount;

                if ($newTotal > $fee->amount) {
                    $validator->errors()->add('amount', "Le montant total payé dépasse le montant du frais ({$fee->amount} XOF). Paiement refusé, veuillez réessayer !");
                }
            }
        });
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FeeRequest extends FormRequest
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
        $fee_id = $this->route('fee') ;

        return [
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:100'],
            'type' => ['required', 'string'],

            Rule::unique('fees')
                ->where(fn($query) => $query
                    ->where('classroom_id', $this->classroom_id)
                    ->where('name', $this->name)
                    ->where('amount', $this->amount)
                    ->where('type', $this->type)
                )
                ->ignore($fee_id),
        ];
    }

    public function messages(): array
    {
        return [
            'classroom_id.required' => 'La salle de classe est obligatoire.',
            'classroom_id.exists' => 'La salle de classe sélectionnée n\'existe pas.',
            'name.required' => 'Le nom du frais est obligatoire.',
            'amount.required' => 'Le montant est obligatoire.',
            'amount.numeric' => 'Le montant doit être un nombre.',
            'amount.min' => 'Le montant doit être au moins 100 XOF.',
            'type.required' => 'Le type de frais est obligatoire.',
            'fees.unique' => 'Un frais identique existe déjà pour cette classe.',
        ];
    }
}

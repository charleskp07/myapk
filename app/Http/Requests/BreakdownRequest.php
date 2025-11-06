<?php

namespace App\Http\Requests;

use App\Enums\BreakdownNameEnums;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BreakdownRequest extends FormRequest
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
            'type' => [
                'required',
                Rule::in([
                    BreakdownNameEnums::TRIMESTRE->value,
                    BreakdownNameEnums::SEMESTRE->value,
                ]),
            ],
            'value' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $type = request()->input('type');
                    if ($type === BreakdownNameEnums::TRIMESTRE->value && $value > 3) {
                        $fail('Le trimestre ne peut pas dépasser 3.');
                    }
                    if ($type === BreakdownNameEnums::SEMESTRE->value && $value > 2) {
                        $fail('Le semestre ne peut pas dépasser 2.');
                    }
                },
                Rule::unique('breakdowns')->where(function ($query) {
                    return $query->where('type', request()->input('type'));
                })->ignore($this->breakdown),
            ],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ];
    }


    public function messages(): array
    {
        return [
            'type.required' => 'Le type du découpage est obligatoire.',
            'type.in' => 'Le type du découpage est incorrecte.',
            'value.required' => 'La valeur du découpage est obligatoire.',
            'value.unique' => 'Cette combinaison nom + valeur existe déjà.',
            'start_date.required' => 'Date de debut est obligatoire',
            'end_date.required' => 'Date de fin est obligatoire',
            'start_date.after' => 'La date de fin doit être postérieure à la date de début.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Enums\NoteAppreciationEnums;
use App\Models\Bareme;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NoteAppreciationRequest extends FormRequest
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
        $enumValues = array_column(NoteAppreciationEnums::cases(), 'value');

        return [
            'bareme_id' => 'required|exists:baremes,id',
            'appreciation' => ['required', 'string', 'in:' . implode(',', $enumValues)],
            'min_value' => 'required|numeric|min:0',
            'max_value' => [
                'required',
                'numeric',
                'min:0',
                Rule::unique('note_appreciations')->where(function ($query) {
                    return $query->where('bareme_id', $this->bareme_id)
                        ->where('appreciation', $this->appreciation)
                        ->where('min_value', $this->min_value)
                        ->where('max_value', $this->max_value);
                }),
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $bareme = Bareme::find($this->bareme_id);

            if (!$bareme) {
                $validator->errors()->add('bareme_id', 'Le barème sélectionné est invalide.');
                return;
            }

            $maxValue = $this->input('max_value');
            $minValue = $this->input('min_value');

            if ($maxValue > $bareme->value) {
                $validator->errors()->add(
                    'max_value',
                    "La valeur maximale ne peut pas dépasser la note maximale du barème ({$bareme->value})."
                );
            }

            if ($minValue >= $maxValue) {
                $validator->errors()->add(
                    'min_value',
                    'La valeur minimale doit être inférieure à la valeur maximale.'
                );
            }

            if ($minValue == $maxValue) {
                $validator->errors()->add(
                    'min_value',
                    'La valeur minimale doit être différente de la valeur maximale.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'bareme_id.required' => 'Veuillez sélectionner un barème.',
            'appreciation.required' => "L'appréciation est obligatoire.",
            'min_value.required' => 'Entrez une valeur minimale.',
            'max_value.required' => 'Entrez une valeur maximale.',
            'appreciation.in' => "L'appréciation choisie n'est pas valide.",
            'max_value.unique' => 'Cette combinaison (barème + appréciation + valeurs) existe déjà.',
        ];
    }
}

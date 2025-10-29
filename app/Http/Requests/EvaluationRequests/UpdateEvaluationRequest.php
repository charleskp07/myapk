<?php

namespace App\Http\Requests\EvaluationRequests;

use App\Enums\EvaluationTypeEnums;
use App\Enums\NoteMaxEnums;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEvaluationRequest extends FormRequest
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
        $evaluation_id = $this->route('evaluation')->id;

        return [
            'assignation_id' => 'sometimes|exists:assignations,id',
            'title' => 'sometimes|string|max:255',
            'date' => [
                'sometimes',
                'required',
                'date',
                'after:today',
            ],
            'type' => [
                'sometimes',
                'required',
                Rule::in([
                    EvaluationTypeEnums::INTERROGATION->value,
                    EvaluationTypeEnums::DEVOIR->value,
                    EvaluationTypeEnums::COMPOSITION->value,

                ])
            ],

            'bareme_id' => 'required|exists:baremes,id',
        ];
    }

    public function messages(): array
    {
        return [
            'assignation_id.exists' => 'L\'assignation sélectionnée n\'existe pas.',
            'title.string' => 'Le titre doit être une chaîne de caractères.',
            'date.required' => 'La date est obligatoire.',
            'date.date' => 'Le format de la date est invalide.',
            'date.after:today' => 'La date doit être une date future.',
            'type.required' => 'Le type est obligatoire.',
            'type.in' => 'Le type sélectionné est invalide',
            'bareme_id.exists' => 'La note maximale sélectionnée n\'existe pas.',
            'bareme.required' => 'La note maximale est obligatoire.',
        ];
    }
}

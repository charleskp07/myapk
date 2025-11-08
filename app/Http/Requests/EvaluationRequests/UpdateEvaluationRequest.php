<?php

namespace App\Http\Requests\EvaluationRequests;

use App\Enums\BreakdownNameEnums;
use App\Enums\ClassroomLevelEnums;
use App\Enums\EvaluationTypeEnums;
use App\Enums\NoteMaxEnums;
use App\Models\Assignation;
use App\Models\Breakdown;
use Carbon\Carbon;
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
        $evaluation_id = $this->route('evaluation');

        $today = now()->format('Y-m-d');

        return [
            'assignation_id' => 'sometimes|exists:assignations,id',
            'title' => 'sometimes|string|max:255',
            'date' => [
                'sometimes',
                'required',
                'date',
                'after_or_equal:' . $today,
                function ($attribute, $value, $fail) {
                    $dayOfWeek = Carbon::parse($value)->dayOfWeek; // 0 = dimanche, 6 = samedi
                    if (in_array($dayOfWeek, [0])) {
                        $fail("La date de l'évaluation ne peut pas être programé sur  un dimanche.");
                    }
                    if (in_array($dayOfWeek, [6])) {
                        $fail("La date de l'évaluation ne peut pas être programé sur un samedi.");
                    }
                },
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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $assignation = $this->assignation_id
                ? Assignation::with('classroom')->find($this->assignation_id)
                : null;

            $breakdown = $this->breakdown_id
                ? Breakdown::find($this->breakdown_id)
                : null;

            if (!$assignation || !$breakdown || !$assignation->classroom) {
                return;
            }

            $level = $assignation->classroom->level;
            $type = $breakdown->type;

            if ($level === ClassroomLevelEnums::LYCEE->value && $type === BreakdownNameEnums::TRIMESTRE->value) {
                $validator->errors()->add('breakdown_id', 'Vous ne pouvez pas choisir un Trimestre pour une classe de lycée.');
            }

            if ($level === ClassroomLevelEnums::COLLEGE->value && $type === BreakdownNameEnums::SEMESTRE->value) {
                $validator->errors()->add('breakdown_id', 'Vous ne pouvez pas choisir un Semestre pour une classe de collège.');
            }
        });
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
            'bareme_id.required' => 'La note maximale est obligatoire.',
        ];
    }
}

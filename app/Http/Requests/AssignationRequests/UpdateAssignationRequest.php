<?php

namespace App\Http\Requests\AssignationRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAssignationRequest extends FormRequest
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
        $assignation_id = $this->route('assignation'); // récupère l’ID dans la route

        return [
            'coefficient' => 'required|integer|min:1|max:10',
            'teacher_id' => [
                'required',
                'exists:teachers,id',
            ],
            'subject_id' => [
                'required',
                'exists:subjects,id',
            ],
            'classroom_id' => [
                'required',
                'exists:classrooms,id',
                Rule::unique('assignations')
                    ->ignore($assignation_id)
                    ->where(function ($query) {
                        return $query->where('teacher_id', $this->teacher_id)
                            ->where('subject_id', $this->subject_id);
                    }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'coefficient.required' => 'Le coefficient est obligatoire.',
            'coefficient.integer' => 'Le coefficient doit être un entier.',
            'coefficient.min' => 'Le coefficient ne peut pas être inférieur à 1.',
            'coefficient.max' => 'Le coefficient ne peut pas être supérieur à 10.',

            'teacher_id.required' => 'L\'enseignant est obligatoire.',
            'teacher_id.exists' => 'L\'enseignant sélectionné n\'existe pas.',

            'subject_id.required' => 'La matière est obligatoire.',
            'subject_id.exists' => 'La matière sélectionnée n\'existe pas.',

            'classroom_id.required' => 'La classe est obligatoire.',
            'classroom_id.exists' => 'La classe sélectionnée n\'existe pas.',
            'classroom_id.unique' => 'Cette assignation existe déjà.',
        ];
    }
}

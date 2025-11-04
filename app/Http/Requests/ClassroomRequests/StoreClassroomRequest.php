<?php

namespace App\Http\Requests\ClassroomRequests;

use App\Enums\ClassroomLevelEnums;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClassroomRequest extends FormRequest
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
            'level' => ['required', Rule::in([
                ClassroomLevelEnums::COLLEGE->value,
                ClassroomLevelEnums::LYCEE->value,
            ])],
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('classrooms')->where(function ($query) {
                    return $query->where('level', $this->level)->where('section', $this->section);
                })
            ],
            'section' => 'nullable|string|max:50',
            'teacher_id' => 'nullable|exists:teachers,id|unique:classrooms,teacher_id',
        ];
    }

    public function messages()
    {
        return [
            'level.required' => 'Le niveau est requis',
            'level.in' => 'Le niveau sélectionné n\'est pas valide.',
            'name.required' => 'Le nom de la classe est requis',
            'name.max' => 'Le nom ne doit pas dépasser 50 caractères',
            'name.unique' => 'Cette classe existe déjà pour ce niveau',
            'section.max' => 'La section ne doit pas dépasser 50 caractères',
            'teacher_id.exists' => 'l\'enseignant sélectionné est invalide.',
            'teacher_id.unique' => 'Cet enseignant est déjà assigné à cette classe.',

        ];
    }

    public function attributes()
    {
        return [
            'level' => 'niveau',
            'name' => 'nom de la classe',
            'section' => 'section',
        ];
    }
}

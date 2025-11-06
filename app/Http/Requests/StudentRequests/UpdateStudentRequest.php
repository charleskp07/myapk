<?php

namespace App\Http\Requests\StudentRequests;

use App\Enums\GenderEnums;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
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
        $student_id = $this->route('student');
        return [
            'user_id' => [
                'sometimes',
                'required',
                'exists:users,id',
                Rule::unique('students', 'user_id')->ignore($student_id)
            ],
            'classroom_id' => 'sometimes|required|exists:classrooms,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'place_of_birth' => 'sometimes|required|string|max:255',
            'gender' => ['sometimes','required', Rule::in([
               GenderEnums::FEMININ->value,
               GenderEnums::MASCULIN->value,
            ])],
            'email' => [
                'sometimes',
                'email',
                Rule::unique('students', 'email')->ignore($student_id)
            ],
            'phone' => 'sometimes|required|string|max:20',
            'nationality' => 'sometimes|required|string|max:100',
        ];
    }

    public function messages()
    {
        return [
            'user_id.exists' => 'L\'utilisateur sélectionné n\'existe pas',
            'user_id.unique' => 'Cet utilisateur est déjà associé à un étudiant',
            'classroom_id.exists' => 'La classe sélectionnée n\'existe pas',
            'photo.image' => 'La photo doit être une image',
            'photo.mimes' => 'La photo doit être au format JPEG, PNG, JPG ou GIF',
            'photo.max' => 'La photo ne doit pas dépasser 2MB',
            'email.email' => 'L\'email doit être une adresse valide',
            'email.unique' => 'Cet email est déjà utilisé',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->has('date_of_birth')) {
                $dateOfBirth = Carbon::parse($this->date_of_birth);
                $ageMinimum = Carbon::now()->subYears(10);
                
                if ($dateOfBirth->greaterThan($ageMinimum)) {
                    $validator->errors()->add('date_of_birth', 'L\'apprenant doit avoir au moins 10 ans');
                }
            }
        });
    }
}

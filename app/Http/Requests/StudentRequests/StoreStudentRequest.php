<?php

namespace App\Http\Requests\StudentRequests;

use App\Enums\GenderEnums;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
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
            'user_id' => 'exists:users,id|unique:students,user_id',
            'classroom_id' => 'required|exists:classrooms,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'place_of_birth' => 'required|string|max:255',
            'gender' => ['required', Rule::in([
               GenderEnums::FEMININ->value,
               GenderEnums::MASCULIN->value,
            ])],
            'email' => 'nullable|email|unique:students,email',
        ];
    }

    public function messages()
    {
        return [
            'user_id.exists' => 'L\'utilisateur sélectionné n\'existe pas',
            'user_id.unique' => 'Cet utilisateur est déjà associé à un étudiant',
            'classroom_id.required' => 'La classe est requise',
            'classroom_id.exists' => 'La classe sélectionnée n\'existe pas',
            'photo.image' => 'La photo doit être une image',
            'photo.mimes' => 'La photo doit être au format JPEG, PNG, JPG ou GIF',
            'photo.max' => 'La photo ne doit pas dépasser 2MB',
            'first_name.required' => 'Le prénom est requis',
            'last_name.required' => 'Le nom est requis',
            'date_of_birth.required' => 'La date de naissance est requise',
            'place_of_birth.required' => 'Le lieu de naissance est requis',
            'gender.required' => 'Le genre est requis',
            'gender.in' => 'Le genre sélectionné n\'est pas valide.',
            'email.nullable' => 'L\'email est requis',
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

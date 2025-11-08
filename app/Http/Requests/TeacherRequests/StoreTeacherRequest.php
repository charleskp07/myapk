<?php

namespace App\Http\Requests\TeacherRequests;

use App\Enums\GenderEnums;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTeacherRequest extends FormRequest
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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'place_of_birth' => 'required|string|max:255',
            'gender' => ['required', Rule::in([
                GenderEnums::FEMININ->value,
                GenderEnums::MASCULIN->value,
            ])],
            'email' => 'required|email|unique:teachers,email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'speciality' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'L\'utilisateur est requis',
            'user_id.exists' => 'L\'utilisateur sélectionné n\'existe pas',
            'user_id.unique' => 'Cet utilisateur est déjà associé à un enseignant',
            'first_name.required' => 'Le prénom est obligatoire',
            'last_name.required' => 'Le nom est obligatoire',
            'date_of_birth.required' => 'La date de naissance est obligatoire',
            'place_of_birth.required' => 'Le lieu de naissance est obligatoire',
            'gender.required' => 'Le genre est obligatoire',
            'email.required' => 'L\'email est obligatoire',
            'email.unique' => 'Cet email est déjà utilisé',
            'speciality.required' => 'La spécialité est obligatoire',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->has('date_of_birth')) {
                $dateOfBirth = Carbon::parse($this->date_of_birth);
                $ageMinimum = Carbon::now()->subYears(20);

                if ($dateOfBirth->greaterThan($ageMinimum)) {
                    $validator->errors()->add('date_of_birth', 'L\'enseignant doit avoir au moins 20 ans');
                }
            }
        });
    }
}

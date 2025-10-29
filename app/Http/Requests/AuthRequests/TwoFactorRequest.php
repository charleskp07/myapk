<?php

namespace App\Http\Requests\AuthRequests;

use Illuminate\Foundation\Http\FormRequest;

class TwoFactorRequest extends FormRequest
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
            'code' => ['required','regex:/^[0-9]+$/', 'size:6'],
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'Le code de vérification est obligatoire.',
            'code.regex' => 'Le code doit contenir uniquement des chiffres.',
            'code.size' => 'Le code doit contenir exactement 6 chiffres.',
        ];
    }
}

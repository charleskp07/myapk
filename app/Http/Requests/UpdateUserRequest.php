<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|email',
            'image' => 'nullable|mimes:jpg,png,jpeg|max:5120',
            'password' => [
                'nullable',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => "Le nom complet est requis.",
            'name.string' => "Le nom complet doit être une chaîne de caractères.",
            'email.required' => "L'adresse e-mail ou l'identifiant est requis.",
            'email.email' => "L'adresse e-mail est invalide.",
            'password.min' => "Le mot de passe doit contenir au moins 8 caractères.",
            'password.mixed' => 'Le mot de passe doit contenir des lettres majuscules et minuscules.',
            'password.numbers' => 'Le mot de passe doit contenir au moins un chiffre.',
            'password.symbols' => 'Le mot de passe doit contenir au moins un caractère spécial.',
        ];
    }
}

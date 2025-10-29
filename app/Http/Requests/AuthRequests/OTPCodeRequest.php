<?php

namespace App\Http\Requests\AuthRequests;

use Illuminate\Foundation\Http\FormRequest;

class OTPCodeRequest extends FormRequest
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
            'code' => 'required|min:6|max:6'
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Le code de confirmation est requis.',
            'code.min' => 'Le code de confirmation doit contenir 6 caractères.',
            'code.max' => 'Le code de confirmation doit contenir 6 caractères.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Models\Evaluation;
use App\Models\Note;
use Illuminate\Foundation\Http\FormRequest;

class NoteRequest extends FormRequest
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

        $evaluation = Evaluation::find($this->evaluation_id);
        $bareme = $evaluation->bareme->value;

        $isUpdate = $this->routeIs('notes.update');

        return [
            'evaluation_id' => 'required|exists:evaluations,id',
            'students' => 'required|array',
            'students.*.student_id' => [
                'required',
                'exists:students,id',

                function ($attribute, $value, $fail) use ($isUpdate) {
                    if (!$isUpdate) {
                        $evaluationId = $this->evaluation_id;
                        if (Note::where('evaluation_id', $evaluationId)
                            ->where('student_id', $value)
                            ->exists()) {
                            $fail('Cet apprenant a déjà une note pour cette évaluation.');
                        }
                    }
                },
                
            ],
            'students.*.value' => [
                'nullable',
                'numeric',
                'min:0',
                "max:{$bareme}",
            ],
            'students.*.comment' => 'nullable|string|max:200',        
        ];
    }


    public function messages(): array
    {
        return [
            'evaluation_id.required' => 'L\'évaluation est requise.',
            'evaluation_id.exists' => 'L\'évaluation sélectionnée n\'existe pas.',
            'students.required' => 'Au moins un étudiant doit être noté.',
            'students.*.student_id.required' => 'L\'identifiant de l\'étudiant est requis.',
            'students.*.student_id.exists' => 'L\'étudiant sélectionné n\'existe pas.',
            'students.*.value.numeric' => 'La note doit être un nombre.',
            'students.*.value.min' => 'La note ne peut pas être négative.',
            'students.*.value.max' => 'La note ne peut pas dépasser le barème de l\'évaluation.',
            'students.*.comment.max' => 'Le commentaire ne peut pas dépasser 200 caractères.',
        ];
    }

}

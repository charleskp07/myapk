<?php

namespace App\Repositories;

use App\Interfaces\NoteInterface;
use App\Models\Note;
use App\Models\NoteAppreciation;

class NoteRepository implements NoteInterface
{

    public function index()
    {
        return Note::all();
    }

    public function store(array $data)
    {

        $evaluation_id = $data['evaluation_id'];

        foreach ($data['students'] as $studentData) {
            $noteValue = $studentData['value'];
            $student_id = $studentData['student_id'];
            $comment = $studentData['comment'] ?? null;
            $appreciation_id = null;


            if ($noteValue !== null && $noteValue !== '') {

                $appreciation = NoteAppreciation::where('min_value', '<=', $noteValue)->where('max_value', '>=', $noteValue)->first();

                if ($appreciation) {
                    $appreciation_id = $appreciation->id;
                }

                Note::updateOrCreate(
                    [
                        'evaluation_id' => $evaluation_id,
                        'student_id' => $student_id,
                    ],
                    [
                        'value' => $noteValue,
                        'comment' => $comment,
                        'note_appreciation_id' => $appreciation_id,
                    ]
                );
            }
        }
    }

    public function show(string $id)
    {
        return Note::find($id);
    }

    public function update(array $data)
    {

        $evaluation_id = $data['evaluation_id']; 

        foreach ($data['students'] as $studentData) {
            
            $noteValue = $studentData['value'];
            $student_id = $studentData['student_id'];
            $comment = $studentData['comment'] ?? null;
            $appreciation_id = null; 

            if ($noteValue !== null && $noteValue !== '') {
            
                $appreciation = NoteAppreciation::where('min_value', '<=', $noteValue)
                    ->where('max_value', '>=', $noteValue)
                    ->first();

                if ($appreciation) {
                    $appreciation_id = $appreciation->id;
                }
            }
            
            $note = Note::where('evaluation_id', $evaluation_id)
                ->where('student_id', $student_id)
                ->first();

            if ($note) {

                $note->update([
                    'note_appreciation_id' => $appreciation_id,
                    'value' => $noteValue,
                    'comment' => $comment,
                ]);
            }
        }
    }

    public function destroy(string $id)
    {
        Note::find($id)->delete();
    }
}

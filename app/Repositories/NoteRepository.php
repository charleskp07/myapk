<?php

namespace App\Repositories;

use App\Interfaces\NoteInterface;
use App\Models\Note;

class NoteRepository implements NoteInterface
{

    public function index()
    {
        return Note::all();
    }


    public function store(array $data)
    {


        foreach ($data['students'] as $studentData) {
            Note::create(
                [
                    'evaluation_id' => $data['evaluation_id'],
                    'note_appreciation_id' => $data['note_appreciation_id'],
                    'student_id' => $studentData['student_id'],
                ],
                [
                    'value' => $studentData['value'] ?? 0,
                    'comment' => $studentData['comment'] ?? null,
                ]
            );
        }
    }

    public function show(string $id)
    {
        return Note::find($id);
    }
    

    public function update(array $data)
    {
        foreach ($data['students'] as $studentData) {
            $note = Note::where('evaluation_id', $data['evaluation_id'])
                ->where('student_id', $studentData['student_id'])
                ->first();

            if ($note) {
                $note->update([
                    'note_appreciation_id' => $data['note_appreciation_id'],
                    'value' => $studentData['value'],
                    'comment' => $studentData['comment'] ?? null,
                ]);
            }
        }
        
    }

    public function destroy(string $id)
    {
        Note::find($id)->delete();
    }
}

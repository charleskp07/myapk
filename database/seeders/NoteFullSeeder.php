<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    Student,
    Note,
    NoteAppreciation,
    Bareme,
    Breakdown,
    Subject,
    Teacher,
    Classroom,
    Assignation,
    Evaluation
};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NoteFullSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Note::truncate();
        Evaluation::truncate();
        Assignation::truncate();
        NoteAppreciation::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // === 1. Création du barème ===
        $bareme = Bareme::firstOrCreate(['value' => 20]);

        // === 2. Création des appréciations ===
        $appreciations = [
            ['appreciation' => 'Médiocre', 'min_value' => 0, 'max_value' => 4],
            ['appreciation' => 'Insuffisant', 'min_value' => 5, 'max_value' => 9],
            ['appreciation' => 'Passable', 'min_value' => 10, 'max_value' => 11],
            ['appreciation' => 'Assez Bien', 'min_value' => 12, 'max_value' => 13],
            ['appreciation' => 'Bien', 'min_value' => 14, 'max_value' => 15],
            ['appreciation' => 'Très Bien', 'min_value' => 16, 'max_value' => 17],
            ['appreciation' => 'Excellent', 'min_value' => 18, 'max_value' => 20],
        ];

        foreach ($appreciations as $a) {
            NoteAppreciation::create([
                'bareme_id' => $bareme->id,
                'appreciation' => $a['appreciation'],
                'min_value' => $a['min_value'],
                'max_value' => $a['max_value'],
            ]);
        }

        // === 3. Création de quelques assignations ===
        $classroom = Classroom::first() ?? Classroom::factory()->create();
        $teachers = Teacher::count() ? Teacher::all() : Teacher::factory(3)->create();
        $subjects = Subject::count() ? Subject::all() : Subject::factory(5)->create();

        foreach ($subjects as $subject) {
            Assignation::create([
                'coefficient' => rand(1, 3),
                'classroom_id' => $classroom->id,
                'subject_id' => $subject->id,
                'teacher_id' => $teachers->random()->id,
            ]);
        }

        $assignations = Assignation::all();
        $breakdown = Breakdown::first() ?? Breakdown::factory()->create(['name' => 'Trimestre 1']);

        // === 4. Création des évaluations ===
        foreach ($assignations as $assignation) {
            for ($i = 1; $i <= 2; $i++) {
                Evaluation::create([
                    'assignation_id' => $assignation->id,
                    'bareme_id' => $bareme->id,
                    'breakdown_id' => $breakdown->id,
                    'title' => 'Évaluation ' . $i . ' - ' . $assignation->subject->name,
                    'date' => Carbon::now()->subDays(rand(1, 60)),
                    'type' => ['interrogation', 'devoir', 'composition'][rand(0, 2)],
                ]);
            }
        }

        $evaluations = Evaluation::all();
        $students = Student::all();

        // === 5. Création de 5 notes par étudiant ===
        foreach ($students as $student) {
            foreach ($evaluations->random(5) as $evaluation) {
                $value = rand(0, 20);
                $appreciation = NoteAppreciation::where('min_value', '<=', $value)
                    ->where('max_value', '>=', $value)
                    ->first();

                Note::create([
                    'evaluation_id' => $evaluation->id,
                    'student_id' => $student->id,
                    'note_appreciation_id' => $appreciation?->id,
                    'value' => $value,
                    'comment' => 'Note simulée automatiquement.',
                ]);
            }
        }
    }
}

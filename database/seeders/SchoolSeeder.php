<?php

namespace Database\Seeders;

use App\Enums\BreakdownNameEnums;
use App\Enums\ClassroomLevelEnums;
use App\Enums\EvaluationTypeEnums;
use App\Enums\GenderEnums;
use App\Enums\NoteAppreciationEnums;
use App\Enums\RoleEnums;
use App\Models\Assignation;
use App\Models\Bareme;
use App\Models\Breakdown;
use App\Models\Classroom;
use App\Models\Evaluation;
use App\Models\Note;
use App\Models\NoteAppreciation;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bareme = Bareme::create(['value' => 20]);

        $appreciations = [
            ['appreciation' => NoteAppreciationEnums::EXCELLENT->value, 'min' => 18, 'max' => 20],
            ['appreciation' => NoteAppreciationEnums::TRES_BIEN->value, 'min' => 16, 'max' => 17.99],
            ['appreciation' => NoteAppreciationEnums::BIEN->value, 'min' => 14, 'max' => 15.99],
            ['appreciation' => NoteAppreciationEnums::ASSEZ_BIEN->value, 'min' => 12, 'max' => 13.99],
            ['appreciation' => NoteAppreciationEnums::PASSABLE->value, 'min' => 10, 'max' => 11.99],
            ['appreciation' => NoteAppreciationEnums::INSUFFISANT->value, 'min' => 8, 'max' => 9.99],
            ['appreciation' => NoteAppreciationEnums::MEDIOCRE->value, 'min' => 0, 'max' => 7.99],
        ];


        foreach ($appreciations as $app) {
            NoteAppreciation::create([
                'bareme_id' => $bareme->id,
                'appreciation' => $app['appreciation'],
                'min_value' => $app['min'],
                'max_value' => $app['max'],
            ]);
        }

        $subjects = collect(['Mathématiques', 'Physique', 'SVT', 'Français', 'Anglais', 'Histoire', 'Géographie', 'Philosophie', 'Informatique', 'EPS'])
            ->map(fn($name) => Subject::create(['name' => $name, 'description' => "Cours de $name"]));




        //Création de 20 enseignants
        $teachers = collect(range(1, 20))->map(function ($i) {
            $user = User::create([
                'name' => "teacher$i",
                'email' => "teacher$i@example.com",
                'password' => Hash::make('password'),
                'phone' => "+228 XX XX XX XX",
                'role' => RoleEnums::TEACHER->value,
            ]);

            return Teacher::create([
                'user_id' => $user->id,
                'first_name' => "Prof$i",
                'last_name' => "ENSEIGNANT",
                'date_of_birth' => now()->subYears(rand(20, 60)),
                'place_of_birth' => "Ville",
                'email' => $user->email,
                'gender' => [GenderEnums::MASCULIN->value, GenderEnums::FEMININ->value][rand(0, 1)],
                'speciality' => "Discipline $i",
            ]);
        });



        // // Création des classes
        // $classrooms = collect(range(1, 10))->map(function ($i) use ($teachers) {

        //     // Choix du niveau d'étude
        //     $level = rand(0, 1) ? ClassroomLevelEnums::COLLEGE->value : ClassroomLevelEnums::LYCEE->value;

        //     // Détermination du nom et de la section selon le niveau
        //     if ($level === ClassroomLevelEnums::COLLEGE->value) {
        //         $className = collect(['6ème', '5ème', '4ème', '3ème'])->random();
        //         $section = collect(['Groupe A', 'Groupe B', 'Groupe C'])->random();
        //     } else {
        //         $className = collect(['2nde', '1ère', 'Terminale'])->random();
        //         $section = collect(['Série A4', 'Série D', 'Série C'])->random();
        //     }

        //     return Classroom::create([
        //         'teacher_id' => $teachers[$i - 1]->id,
        //         'level' => $level,
        //         'name' => $className,
        //         'section' => $section,
        //     ]);
        // });



        // //Création des élèves
        // $students = collect();
        // foreach ($classrooms as $classroom) {
        //     foreach (range(1, 20) as $j) {
        //         $user = User::create([
        //             'name' => Str::slug("student{$classroom->id}_$j"),
        //             'email' => "student{$classroom->id}_$j@example.com",
        //             'password' => Hash::make('password'),
        //             'phone' => "+228 XX XX XX XX",
        //             'role' => RoleEnums::STUDENT->value,
        //         ]);

        //         $students->push(Student::create([
        //             'user_id' => $user->id,
        //             'classroom_id' => $classroom->id,
        //             'first_name' => "Élève$j",
        //             'last_name' => "Classe{$classroom->id}",
        //             'date_of_birth' => now()->subYears(rand(10, 18)),
        //             'place_of_birth' => "Ville",
        //             'gender' => [GenderEnums::MASCULIN->value, GenderEnums::FEMININ->value][rand(0, 1)],
        //             'email' => $user->email,
        //             'phone' => '69000000',
        //             'nationality' => 'Togolaise',
        //         ]));
        //     }
        // }



        //  Création des assignation
        // $assignations = collect();
        // foreach ($classrooms as $classroom) {
        //     foreach ($subjects->shuffle()->take(5) as $subject) {
        //         $assignations->push(Assignation::create([
        //             'classroom_id' => $classroom->id,
        //             'subject_id' => $subject->id,
        //             'teacher_id' => $teachers->random()->id,
        //             'coefficient' => rand(1, 5),
        //             'number_of_hours' => rand(1, 5),
        //         ]));
        //     }
        // }


        //  Création ou ajout de découpages (Breakdowns)

        // Vérifie les découpages existants
        $existingBreakdowns = Breakdown::all();
        $existingTrimestres = $existingBreakdowns->where('type', BreakdownNameEnums::TRIMESTRE->value)->count();
        $existingSemestres = $existingBreakdowns->where('type', BreakdownNameEnums::SEMESTRE->value)->count();

        // Génération automatique des trimestres (jusqu’à 3)
        if ($existingTrimestres < 3) {
            $startDate = now()->startOfYear();

            for ($i = $existingTrimestres + 1; $i <= 3; $i++) {
                Breakdown::create([
                    'type' => BreakdownNameEnums::TRIMESTRE->value,
                    'value' => $i,
                    'start_date' => $startDate->copy()->addMonths(($i - 1) * 3),
                    'end_date' => $startDate->copy()->addMonths($i * 3)->subDay(),
                ]);
            }
        }

        // Génération automatique des semestres (jusqu’à 2)
        if ($existingSemestres < 2) {
            $startDate = now()->startOfYear();

            for ($i = $existingSemestres + 1; $i <= 2; $i++) {
                Breakdown::create([
                    'type' => BreakdownNameEnums::SEMESTRE->value,
                    'value' => $i,
                    'start_date' => $startDate->copy()->addMonths(($i - 1) * 6),
                    'end_date' => $startDate->copy()->addMonths($i * 6)->subDay(),
                ]);
            }
        }

        // // Récupère le premier découpage pour les évaluations
        // $breakdown = Breakdown::where('type', BreakdownNameEnums::TRIMESTRE->value)->first()
        //     ?? Breakdown::where('type', BreakdownNameEnums::SEMESTRE->value)->first();



        // //Création des évaluations et notes 
        // foreach ($assignations as $assignation) {
        //     $classroom = $assignation->classroom;

        //     //On choisit le découpage en fonction du niveau
        //     $decoupages = $classroom->level === ClassroomLevelEnums::LYCEE->value
        //         ? Breakdown::where('type', BreakdownNameEnums::SEMESTRE->value)->get()
        //         : Breakdown::where('type', BreakdownNameEnums::TRIMESTRE->value)->get();

        //     foreach ($decoupages as $breakdown) {
        //         foreach (EvaluationTypeEnums::cases() as $type) {
        //             $evaluation = Evaluation::create([
        //                 'assignation_id' => $assignation->id,
        //                 'bareme_id' => $bareme->id,
        //                 'title' => "{$type->value} - {$assignation->subject->name} ({$breakdown->type} {$breakdown->value})",
        //                 'date' => now(),
        //                 'type' => $type->value,
        //                 'breakdown_id' => $breakdown->id,
        //             ]);

        //             //Noter chaque élève de la classe
        //             $classStudents = $students->where('classroom_id', $assignation->classroom_id);

        //             foreach ($classStudents as $student) {
        //                 $value = rand(0, 20);

        //                 $app = NoteAppreciation::where('bareme_id', $bareme->id)
        //                     ->where('min_value', '<=', $value)
        //                     ->where('max_value', '>=', $value)
        //                     ->first();

        //                 Note::create([
        //                     'evaluation_id' => $evaluation->id,
        //                     'student_id' => $student->id,
        //                     'note_appreciation_id' => $app->id,
        //                     'value' => $value,
        //                     'comment' => $app->appreciation,
        //                 ]);
        //             }
        //         }
        //     }
        // }

        echo "✅ Données scolaires générées avec succès !";
    }
}

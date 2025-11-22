<?php

namespace Database\Seeders;

use App\Enums\BreakdownNameEnums;
use App\Enums\ClassroomLevelEnums;
use App\Enums\EvaluationTypeEnums;
use App\Enums\FeeTypeEnums;
use App\Enums\GenderEnums;
use App\Enums\NoteAppreciationEnums;
use App\Enums\PaymentTypeEnums;
use App\Enums\RoleEnums;
use App\Enums\TimePreference;
use App\Models\Assignation;
use App\Models\Bareme;
use App\Models\Breakdown;
use App\Models\Classroom;
use App\Models\Evaluation;
use App\Models\Fee;
use App\Models\Note;
use App\Models\NoteAppreciation;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'KPALIKA Charles',
            'email' => 'charleskpalika1@gmail.com',
            'password' => 'Coucou2025@@',
            'phone' => '12345678',
            'role' => RoleEnums::ADMIN->value,
        ]);


        $classrooms = [
            // Collège
            ['level' => ClassroomLevelEnums::COLLEGE->value, 'name' => '6ème', 'section' => 'Groupe A'],
            ['level' => ClassroomLevelEnums::COLLEGE->value, 'name' => '6ème', 'section' => 'Groupe B'],
            ['level' => ClassroomLevelEnums::COLLEGE->value, 'name' => '5ème', 'section' => 'Groupe A'],
            ['level' => ClassroomLevelEnums::COLLEGE->value, 'name' => '5ème', 'section' => 'Groupe B'],
            ['level' => ClassroomLevelEnums::COLLEGE->value, 'name' => '4ème', 'section' => 'Groupe A'],
            ['level' => ClassroomLevelEnums::COLLEGE->value, 'name' => '4ème', 'section' => 'Groupe B'],
            ['level' => ClassroomLevelEnums::COLLEGE->value, 'name' => '3ème', 'section' => 'Groupe A'],
            ['level' => ClassroomLevelEnums::COLLEGE->value, 'name' => '3ème', 'section' => 'Groupe B'],

            // Lycée
            ['level' => ClassroomLevelEnums::LYCEE->value, 'name' => '2nde', 'section' => 'Serie A4'],
            ['level' => ClassroomLevelEnums::LYCEE->value, 'name' => '2nde', 'section' => 'Serie CD'],
            ['level' => ClassroomLevelEnums::LYCEE->value, 'name' => '1ère', 'section' => 'Serie A4'],
            ['level' => ClassroomLevelEnums::LYCEE->value, 'name' => '1ère', 'section' => 'Serie D'],
            ['level' => ClassroomLevelEnums::LYCEE->value, 'name' => 'Terminale', 'section' => 'Serie A4'],
            ['level' => ClassroomLevelEnums::LYCEE->value, 'name' => 'Terminale', 'section' => 'Serie D'],
        ];

        foreach ($classrooms as $classroom) {
            Classroom::create([
                'level' => $classroom['level'],
                'name' => $classroom['name'],
                'section' => $classroom['section'],
                'teacher_id' => !empty($teacherIds) ? array_shift($teacherIds) : null, // associe un enseignant unique si dispo
            ]);
        }

        $classrooms = Classroom::all();

        // Exemple de frais types
        $feesTemplate = [
            ['name' => 'Inscription', 'amount' => 5000, 'type' => FeeTypeEnums::OBLIGATOIRE->value],
            ['name' => 'Frais de scolarité', 'amount' => 50000, 'type' => FeeTypeEnums::OBLIGATOIRE->value],
            ['name' => 'Transport', 'amount' => 20000, 'type' => FeeTypeEnums::OPTIONNEL->value],
            ['name' => 'Cantine', 'amount' => 15000, 'type' => FeeTypeEnums::OPTIONNEL->value],
            ['name' => 'Uniforme', 'amount' => 10000, 'type' => FeeTypeEnums::OBLIGATOIRE->value],
        ];

        foreach ($classrooms as $classroom) {
            foreach ($feesTemplate as $fee) {
                Fee::create([
                    'classroom_id' => $classroom->id,
                    'name' => $fee['name'],
                    'amount' => $fee['amount'],
                    'type' => $fee['type'],
                    'deadline' => Carbon::now()->addMonths(2),
                ]);
            }
        }


        $bareme = Bareme::create(['value' => 20]);

        $appreciations = [
            ['appreciation' => NoteAppreciationEnums::EXCELLENT->value, 'min' => 19, 'max' => 20],
            ['appreciation' => NoteAppreciationEnums::TRES_BIEN->value, 'min' => 16, 'max' => 18.99],
            ['appreciation' => NoteAppreciationEnums::BIEN->value, 'min' => 14, 'max' => 15.99],
            ['appreciation' => NoteAppreciationEnums::ASSEZ_BIEN->value, 'min' => 12, 'max' => 13.99],
            ['appreciation' => NoteAppreciationEnums::PASSABLE->value, 'min' => 10, 'max' => 11.99],
            ['appreciation' => NoteAppreciationEnums::INSUFFISANT->value, 'min' => 7, 'max' => 9.99],
            ['appreciation' => NoteAppreciationEnums::MEDIOCRE->value, 'min' => 0, 'max' => 6.99],
        ];


        foreach ($appreciations as $app) {
            NoteAppreciation::create([
                'bareme_id' => $bareme->id,
                'appreciation' => $app['appreciation'],
                'min_value' => $app['min'],
                'max_value' => $app['max'],
            ]);
        }

        $subjects = [
            'Mathématiques' => TimePreference::MATIN,
            'Physique'      => TimePreference::MATIN,
            'SVT'           => TimePreference::MATIN,
            'Français'      => TimePreference::MATIN,
            'Anglais'       => TimePreference::FLEXIBLE,
            'Histoire'      => TimePreference::AVANT_PAUSE,
            'Géographie'    => TimePreference::AVANT_PAUSE,
            'Philosophie'   => TimePreference::MATIN,
            'Informatique'  => TimePreference::FLEXIBLE,
            'EPS'           => TimePreference::APRES_MIDI,
        ];

        collect($subjects)->map(
            fn($preference, $name) =>
            Subject::create([
                'name'            => $name,
                'description'     => "Cours de $name",
                'time_preference' => $preference->value,
            ])
        );


        $existingBreakdowns = Breakdown::all();
        $existingTrimestres = $existingBreakdowns->where('type', BreakdownNameEnums::TRIMESTRE->value)->count();
        $existingSemestres = $existingBreakdowns->where('type', BreakdownNameEnums::SEMESTRE->value)->count();

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

        $faker = Faker::create('fr_FR');

        $classrooms = Classroom::all();

        foreach ($classrooms as $classroom) {

            $studentsCount = rand(30, 50);

            for ($i = 0; $i < $studentsCount; $i++) {
                $firstName = $faker->firstName();
                $lastName = $faker->lastName();

                $dateOfBirth = $faker->dateTimeBetween("-{18} years", "-{10} years")->format('Y-m-d');

                $email = $faker->unique()->safeEmail();
                $gender = $faker->randomElement([
                    GenderEnums::MASCULIN->value,
                    GenderEnums::FEMININ->value,
                ]);

                // créer l'élève
                Student::create([
                    'classroom_id' => $classroom->id,
                    'photo' => null,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'date_of_birth' => $dateOfBirth,
                    'place_of_birth' => $faker->city(),
                    'gender' => $gender,
                    'email' => $email,
                    'phone' => $faker->phoneNumber(),
                    'nationality' => 'Togolaise',
                ]);
            }
        }



        $teachers = collect(range(1, 20))->map(function ($i) {

            $faker = Faker::create('fr_FR');

            $firstName = $faker->firstName();
            $lastName = $faker->lastName();


            $email = $faker->unique()->safeEmail();
            $gender = $faker->randomElement([
                GenderEnums::MASCULIN->value,
                GenderEnums::FEMININ->value,
            ]);



            $user = User::create([
                'name' => "teacher$i",
                'email' => "teacher$i@example.com",
                'password' => Hash::make('password'),
                'phone' => $faker->phoneNumber(),
                'role' => RoleEnums::TEACHER->value,
            ]);

            return Teacher::create([
                'user_id' => $user->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'date_of_birth' => now()->subYears(rand(20, 60)),
                'place_of_birth' => $faker->city(),
                'phone' => $faker->phoneNumber(),
                'email' => $email,
                'gender' => $gender,
                'speciality' => "Discipline $i",
            ]);
        });



        $subjects = Subject::all();
        $teachers = Teacher::all();
        $classrooms = Classroom::all();

        foreach ($classrooms as $classroom) {

            // choisir 7-8 matières aléatoires pour la classe
            $subjectsForClass = $subjects->random(rand(7, 8));

            foreach ($subjectsForClass as $subject) {

                Assignation::create([
                    'classroom_id'  => $classroom->id,
                    'subject_id'    => $subject->id,
                    'teacher_id'    => $teachers->random()->id,
                    'coefficient'   => rand(1, 5),
                    'weekly_hours'  => rand(1, 4),
                ]);
            }
        }


        $assignations = Assignation::all();
        $bareme = Bareme::first();

        $trimestres = Breakdown::where('type', BreakdownNameEnums::TRIMESTRE->value)->get();
        $semestres  = Breakdown::where('type', BreakdownNameEnums::SEMESTRE->value)->get();

        foreach ($assignations as $assignation) {

            // vérifier si la classe est collège ou lycée
            $classroom = $assignation->classroom;

            if ($classroom->level === ClassroomLevelEnums::COLLEGE->value) {
                $periods = $trimestres;   // collège = trimestres
            } else {
                $periods = $semestres;    // lycée = semestres
            }

            // 3 évaluations par matière
            for ($i = 1; $i <= 3; $i++) {

                Evaluation::create([
                    'assignation_id' => $assignation->id,
                    'bareme_id'       => $bareme->id,
                    'breakdown_id'    => $periods->random()->id,  // trimestre ou semestre
                    'title'           => "Évaluation $i",
                    'date'            => now()->subDays(rand(10, 60)),
                    'type'            => collect([
                        EvaluationTypeEnums::INTERROGATION->value,
                        EvaluationTypeEnums::DEVOIR->value,
                        EvaluationTypeEnums::COMPOSITION->value,
                    ])->random(),
                ]);
            }
        }



        $evaluations = Evaluation::all();
        $students = Student::all();
        $appreciations = NoteAppreciation::all();

        foreach ($students as $student) {
            // Toutes les assignations de sa classe
            $classAssignations = $assignations->where('classroom_id', $student->classroom_id);

            foreach ($classAssignations as $assignation) {

                // récupérer les 3 évaluations liées à la matière
                $evals = $evaluations->where('assignation_id', $assignation->id);

                foreach ($evals as $evaluation) {

                    // note entre 0 et 20
                    $value = rand(0, 200) / 10; // ex: 14.5

                    // trouver l'appréciation correspondante
                    $app = $appreciations->first(function ($a) use ($value) {
                        return $value >= $a->min_value && $value <= $a->max_value;
                    });

                    Note::create([
                        'evaluation_id'       => $evaluation->id,
                        'student_id'          => $student->id,
                        'note_appreciation_id' => $app->id,
                        'value'               => $value,
                        'comment'             => $faker->sentence(),
                    ]);
                }
            }
        }



        $fees = Fee::all();

        foreach ($students as $student) {

            // prendre 1 à 3 frais au hasard
            $feesToPay = $fees->where('classroom_id', $student->classroom_id)->random(rand(1, 3));

            foreach ($feesToPay as $fee) {
                Payment::create([
                    'student_id'     => $student->id,
                    'fee_id'         => $fee->id,
                    'amount'         => $fee->amount,
                    'payment_method' => collect([
                        PaymentTypeEnums::ESPECES->value,
                        PaymentTypeEnums::DEPOT->value,
                        PaymentTypeEnums::AUTRE->value,
                    ])->random(),
                    'payment_date'   => now()->subDays(rand(1, 90)),
                    'reference'      => strtoupper('PAY-' . uniqid()),
                    'note'           => 'Paiement enregistré automatiquement.',
                ]);
            }
        }
    }
}

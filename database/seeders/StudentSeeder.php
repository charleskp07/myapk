<?php

namespace Database\Seeders;

use App\Enums\GenderEnums;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

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

                // créer le user associé
                $user = User::create([
                    'name' => "{$firstName} {$lastName}",
                    'email' => $email,
                    'phone' => $faker->phoneNumber(),
                    'password' => Hash::make('password'),
                ]);

                // créer l'élève
                Student::create([
                    'user_id' => $user->id,
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
    }
}

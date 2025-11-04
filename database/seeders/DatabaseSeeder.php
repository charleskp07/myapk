<?php

namespace Database\Seeders;

use App\Enums\ClassroomLevelEnums;
use App\Enums\GenderEnums;
use App\Enums\RoleEnums;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // $this->call(StudentSeeder::class);

        User::factory()->create([
            'name' => 'KPALIKA Charles',
            'email' => 'charleskpalika1@gmail.com',
            'password' => 'Coucou2025@@',
            'phone' => '12345678',
            'role' => RoleEnums::ADMIN->value,
        ]);


        // $faker = Faker::create();

        // $levels = [
        //     ClassroomLevelEnums::COLLEGE->value,
        //     ClassroomLevelEnums::LYCEE->value,

        // ];

        // for ($i = 1; $i <= 15; $i++) {

        //     $level = $levels[array_rand($levels)];

        //     $classroom = Classroom::create([
        //         'level' => $level,
        //         'name' => 'Classe ' . $i,
        //         'section' => 'Section ' . chr(64 + ($i % 26 ?: 1)), // Section A, B, ...
        //     ]);

        //     for ($j = 1; $j <= 30; $j++) {
        //         $user = User::create([
        //             'name' => $faker->name,
        //             'email' => $faker->unique()->safeEmail,
        //             'password' => Hash::make('password123'),
        //             'phone' => $faker->phoneNumber,
        //             'role' => RoleEnums::STUDENT->value,
        //         ]);

        //         Student::create([
        //             'user_id' => $user->id,
        //             'classroom_id' => $classroom->id,
        //             'photo' => null,
        //             'first_name' => explode(' ', $user->name)[0],
        //             'last_name' => explode(' ', $user->name)[1] ?? '',
        //             'date_of_birth' => $faker->date('Y-m-d', '2008-01-01'),
        //             'place_of_birth' => $faker->city,
        //             'gender' => $faker->randomElement([GenderEnums::MASCULIN->value, GenderEnums::FEMININ->value]),
        //             'email' => $user->email,
        //             'phone' => $user->phone,
        //             'nationality' => 'Togolaise',
        //         ]);
        //     }
        // }
    }
}

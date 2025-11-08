<?php

namespace Database\Seeders;

use App\Enums\GenderEnums;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR'); 
        $specialities = [
            'Mathématiques',
            'Physique',
            'Chimie',
            'Biologie',
            'Français',
            'Histoire-Géographie',
            'Anglais',
            'Informatique',
            'Éducation Physique',
            'Philosophie',
        ];

        
        $count = 30;

        for ($i = 0; $i < $count; $i++) {
            
            $firstName = $faker->firstName();
            
            $lastName = $faker->lastName();

            $dateOfBirth = $faker->dateTimeBetween('-55 years', '-22 years')->format('Y-m-d');

            $phone = $faker->phoneNumber();

            $email = $faker->unique()->safeEmail();

            $user = User::create([
                'name' => "{$firstName} {$lastName}",
                'email' => $email,
                'phone' => $phone,
                'password' => Hash::make('password'), 
            ]);
           
            $gender = $faker->randomElement([
                GenderEnums::MASCULIN->value,
                GenderEnums::FEMININ->value,
            ]);

            $speciality = $faker->randomElement($specialities); 

            Teacher::create([
                'user_id' => $user->id,
                'photo' => null,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'date_of_birth' => $dateOfBirth,
                'place_of_birth' => $faker->city(),
                'gender' => $gender,
                'email' => $email,
                'phone' => $phone,
                'nationality' => 'Togolaise',
                'speciality' => $speciality,
            ]);

        }
    }
}

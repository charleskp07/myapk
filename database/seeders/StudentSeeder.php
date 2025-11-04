<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::count() < 12) {
            User::factory(12)->create();
        }

        $users = User::all();

        $students = [
            ['first_name' => 'Amivi', 'last_name' => 'Kossi', 'gender' => 'Féminin'],
            ['first_name' => 'Koffi', 'last_name' => 'Agbeko', 'gender' => 'Masculin'],
            ['first_name' => 'Adjowa', 'last_name' => 'Tchalla', 'gender' => 'Féminin'],
            ['first_name' => 'Kodjo', 'last_name' => 'Mensah', 'gender' => 'Masculin'],
            ['first_name' => 'Elom', 'last_name' => 'Doe-Bla', 'gender' => 'Masculin'],
            ['first_name' => 'Abla', 'last_name' => 'Awesso', 'gender' => 'Féminin'],
            ['first_name' => 'Sena', 'last_name' => 'Atayi', 'gender' => 'Masculin'],
            ['first_name' => 'Mawuli', 'last_name' => 'Azanleko', 'gender' => 'Masculin'],
            ['first_name' => 'Afi', 'last_name' => 'Dede', 'gender' => 'Féminin'],
            ['first_name' => 'Kwami', 'last_name' => 'Eklu', 'gender' => 'Masculin'],
            ['first_name' => 'Akou', 'last_name' => 'Gaba', 'gender' => 'Féminin'],
            ['first_name' => 'Yao', 'last_name' => 'Adjonou', 'gender' => 'Masculin'],
        ];

        foreach ($students as $i => $data) {
            Student::create([
                'user_id'        => $users[$i]->id,
                'classroom_id'   => 1,
                'photo'          => null,
                'first_name'     => $data['first_name'],
                'last_name'      => $data['last_name'],
                'date_of_birth'  => fake()->dateTimeBetween('-25 years', '-18 years')->format('Y-m-d'),
                'place_of_birth' => fake()->city(),
                'gender'         => $data['gender'],
                'email'          => strtolower($data['first_name'] . '.' . $data['last_name']) . '@example.com',
                'phone'          => fake()->phoneNumber(),
                'nationality'    => 'Togolaise',
            ]);
        }
    }
}

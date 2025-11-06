<?php

namespace Database\Seeders;

use App\Enums\RoleEnums;
use App\Models\User;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        // User::factory()->create([
        //     'name' => 'KPALIKA Charles',
        //     'email' => 'charleskpalika1@gmail.com',
        //     'password' => 'Coucou2025@@',
        //     'phone' => '12345678',
        //     'role' => RoleEnums::ADMIN->value,
        // ]);
    }
}

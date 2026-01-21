<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
            'firstname' => 'Victoria',
            'lastname' => 'Fleury',
            'authorization' => 'admin',
            'active' => true,
            'registration_number' => '123456',
            'password' => 'Password123!',
            ],
            [
            'firstname' => 'Jean',
            'lastname' => 'Dupont',
            'authorization' => 'operator',
            'active' => true,
            'registration_number' => '234567',
            'password' => 'Password123!',
            ],
            [
            'firstname' => 'Marie',
            'lastname' => 'Martin',
            'authorization' => 'operator',
            'active' => true, // Utilisateur inactif pour tester
            'registration_number' => '345678',
            'password' => 'Password123!',
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['registration_number' => $user['registration_number']],
                $user
            );
        }
    }
}

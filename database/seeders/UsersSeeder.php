<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'firstname' => 'Victoria',
            'lastname' => 'Fleury',
            'authorization' => 'Admin',
            'active' => True,
            'registration_number' => '123456',
            'password' => bcrypt('1234'),
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Machine;

class MachinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
      {
       $machines= [
            [
            'machine_name' => 'Nagema 1',
            'short_name' => 'Nag1',
            'theoritical_industrial_pace' => '12',
            'measurement_unit' => 'barquesttes/min',
            'max_capacity' => null,
            'status' => 'stop',
            ],
            [
            'machine_name' => 'Nagema 2',
            'short_name' => 'Nag2',
            'theoritical_industrial_pace' => '12',
            'measurement_unit' => 'barquesttes/min',
            'max_capacity' => null,
            'status' => 'stop',
            ],
            [
            'machine_name' => 'Nagema 3',
            'short_name' => 'Nag3',
            'theoritical_industrial_pace' => '24',
            'measurement_unit' => 'barquesttes/min',
            'max_capacity' => null,
            'status' => 'stop',
            ],
        ];

        foreach ($machines as $machine) {
            Machine::firstOrCreate(
                ['machine_name' => $machine['machine_name']],
                $machine
            );
        }
    }
}
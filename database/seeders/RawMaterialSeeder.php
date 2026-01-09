<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RawMaterial;

class RawMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rawMaterials= [
            [
            'name' => 'Carottes rapées',
            'type' => 'légumes',
            'measurement_unit' => 'kg',
            'reference' => 'RMCR123',
            'theoritical_industrial_pace' => null,
            'active' => true,
            ],
            [
            'name' => 'Choux rapées',
            'type' => 'légumes',
            'measurement_unit' => 'kg',
            'reference' => 'RMCHR124',
            'theoritical_industrial_pace' => null,
            'active' => true,
            ],
            [
            'name' => 'Choux rouge rapées',
            'type' => 'légumes',
            'measurement_unit' => 'kg',
            'reference' => 'RMCRR125',
            'theoritical_industrial_pace' => null,
            'active' => true,
            ],
        ];

        foreach ($rawMaterials as $rawMaterial) {
            RawMaterial::firstOrCreate(
                ['name' => $rawMaterial['name']],
                $rawMaterial
            );
        }
    }
}


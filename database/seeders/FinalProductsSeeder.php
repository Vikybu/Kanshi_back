<?php

namespace Database\Seeders;

use App\Models\FinalProduct;
use Illuminate\Database\Seeder;

class FinalProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $finalProducts= [
            [
            'name' => 'Carottes rapées 300g',
            'type' => 'légumes',
            'measurement_unit' => 'barquettes',
            'reference' => 'FPCR456',
            'theoritical_industrial_pace' => null,
            'active' => true,
            ],
            [
            'name' => 'Choux rapées 250g',
            'type' => 'légumes',
            'measurement_unit' => 'barquettes',
            'reference' => 'FPCHR455',
            'theoritical_industrial_pace' => null,
            'active' => true,
            ],
            [
            'name' => 'Choux rouge rapées 250g',
            'type' => 'légumes',
            'measurement_unit' => 'barquettes',
            'reference' => 'FPCRR478',
            'theoritical_industrial_pace' => null,
            'active' => true,
            ],
        ];

        foreach ($finalProducts as $finalProduct) {
            FinalProduct::firstOrCreate(
                ['name' => $finalProduct['name']],
                $finalProduct
            );
        }
    }
}

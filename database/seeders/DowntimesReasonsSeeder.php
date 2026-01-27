<?php

namespace Database\Seeders;

use App\Models\DowntimeReason;
use Illuminate\Database\Seeder;

class DowntimesReasonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $downtimesReasons= [
            [
            'name' => 'pause',
            'type' => 'planned',
            ],
            [
            'name' => 'Intervention maintenance',
            'type' => 'planned',
            ],
            [
            'name' => 'Manque de matière première',
            'type' => 'unplanned',
            ],
            [
            'name' => 'Problème dépilleur',
            'type' => 'unplanned',
            ],
            [
            'name' => 'Problème operculeuse',
            'type' => 'unplanned',
            ],
            [
            'name' => 'Manque de personnel',
            'type' => 'unplanned',
            ],
            [
            'name' => 'Problème convoyeur',
            'type' => 'unplanned',
            ],
            [
            'name' => 'Problème balance',
            'type' => 'unplanned',
            ],
        ];

        foreach ($downtimesReasons as $downtimesReason) {
            DowntimeReason::firstOrCreate(
                ['name' => $downtimesReason['name']],
                $downtimesReason
            );
        }
    }
}

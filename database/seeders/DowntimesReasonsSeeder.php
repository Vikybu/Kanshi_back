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
            'type' => 'arrêt planifié',
            ],
            [
            'name' => 'Intervention maintenance',
            'type' => 'arrêt planifié',
            ],
            [
            'name' => 'Manque de matière première',
            'type' => 'arrêt non planifié',
            ],
            [
            'name' => 'Problème dépilleur',
            'type' => 'arrêt non planifié',
            ],
            [
            'name' => 'Problème operculeuse',
            'type' => 'arrêt non planifié',
            ],
            [
            'name' => 'Manque de personnel',
            'type' => 'arrêt non planifié',
            ],
            [
            'name' => 'Problème convoyeur',
            'type' => 'arrêt non planifié',
            ],
            [
            'name' => 'Problème balance',
            'type' => 'arrêt non planifié',
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

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Allergie;

class AllergieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allergieen = [
            [
                'id' => 1,
                'naam' => 'Gluten',
                'omschrijving' => 'Allergisch voor gluten',
                'anafylactisch_risico' => 'zeerlaag'
            ],
            [
                'id' => 2,
                'naam' => 'Pindas',
                'omschrijving' => 'Allergisch voor pindas',
                'anafylactisch_risico' => 'hoog'
            ],
            [
                'id' => 3,
                'naam' => 'Schaaldieren',
                'omschrijving' => 'Allergisch voor schaaldieren',
                'anafylactisch_risico' => 'redelijk_hoog'
            ],
            [
                'id' => 4,
                'naam' => 'Hazelnoten',
                'omschrijving' => 'Allergisch voor hazelnoten',
                'anafylactisch_risico' => 'laag'
            ],
            [
                'id' => 5,
                'naam' => 'Lactose',
                'omschrijving' => 'Allergisch voor lactose',
                'anafylactisch_risico' => 'zeerlaag'
            ],
            [
                'id' => 6,
                'naam' => 'Soja',
                'omschrijving' => 'Allergisch voor soja',
                'anafylactisch_risico' => 'zeerlaag'
            ]
        ];

        foreach ($allergieen as $allergie) {
            Allergie::create($allergie);
        }
    }
}

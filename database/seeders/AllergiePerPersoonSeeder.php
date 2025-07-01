<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AllergiePerPersoon;

class AllergiePerPersoonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allergiePerPersoon = [
            ['id' => 1, 'persoon_id' => 4, 'allergie_id' => 1],  // Johan - Gluten
            ['id' => 2, 'persoon_id' => 5, 'allergie_id' => 2],  // Sarah - Pindas
            ['id' => 3, 'persoon_id' => 6, 'allergie_id' => 3],  // Theo - Schaaldieren
            ['id' => 4, 'persoon_id' => 7, 'allergie_id' => 4],  // Jantien - Hazelnoten
            ['id' => 5, 'persoon_id' => 8, 'allergie_id' => 3],  // Arjan - Schaaldieren
            ['id' => 6, 'persoon_id' => 9, 'allergie_id' => 2],  // Janneke - Pindas
            ['id' => 7, 'persoon_id' => 10, 'allergie_id' => 5], // Stein - Lactose
            ['id' => 8, 'persoon_id' => 12, 'allergie_id' => 2], // Mazin - Pindas
            ['id' => 9, 'persoon_id' => 13, 'allergie_id' => 4], // Selma - Hazelnoten
            ['id' => 10, 'persoon_id' => 14, 'allergie_id' => 1], // Eva - Gluten
            ['id' => 11, 'persoon_id' => 15, 'allergie_id' => 3], // Felicia - Schaaldieren
            ['id' => 12, 'persoon_id' => 16, 'allergie_id' => 5], // Devin - Lactose
            ['id' => 13, 'persoon_id' => 17, 'allergie_id' => 1], // Frieda - Gluten
            ['id' => 14, 'persoon_id' => 17, 'allergie_id' => 2], // Frieda - Pindas
            ['id' => 15, 'persoon_id' => 18, 'allergie_id' => 4], // Simeon - Hazelnoten
            ['id' => 16, 'persoon_id' => 19, 'allergie_id' => 4]  // Hanna - Hazelnoten
        ];

        foreach ($allergiePerPersoon as $relatie) {
            AllergiePerPersoon::create($relatie);
        }
    }
}

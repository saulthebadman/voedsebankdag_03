<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gezin;

class GezinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gezinnen = [
            [
                'id' => 1,
                'naam' => 'ZevenhuizenGezin',
                'code' => 'G0001',
                'omschrijving' => 'Bijstandsgezin',
                'aantal_volwassenen' => 2,
                'aantal_kinderen' => 2,
                'aantal_babys' => 0,
                'totaal_aantal_personen' => 4
            ],
            [
                'id' => 2,
                'naam' => 'BergkampGezin',
                'code' => 'G0002',
                'omschrijving' => 'Bijstandsgezin',
                'aantal_volwassenen' => 2,
                'aantal_kinderen' => 1,
                'aantal_babys' => 1,
                'totaal_aantal_personen' => 4
            ],
            [
                'id' => 3,
                'naam' => 'HeuvelGezin',
                'code' => 'G0003',
                'omschrijving' => 'Bijstandsgezin',
                'aantal_volwassenen' => 2,
                'aantal_kinderen' => 0,
                'aantal_babys' => 0,
                'totaal_aantal_personen' => 2
            ],
            [
                'id' => 4,
                'naam' => 'ScherderGezin',
                'code' => 'G0004',
                'omschrijving' => 'Bijstandsgezin',
                'aantal_volwassenen' => 1,
                'aantal_kinderen' => 0,
                'aantal_babys' => 2,
                'totaal_aantal_personen' => 3
            ],
            [
                'id' => 5,
                'naam' => 'DeJongGezin',
                'code' => 'G0005',
                'omschrijving' => 'Bijstandsgezin',
                'aantal_volwassenen' => 1,
                'aantal_kinderen' => 1,
                'aantal_babys' => 0,
                'totaal_aantal_personen' => 2
            ],
            [
                'id' => 6,
                'naam' => 'VanderBergGezin',
                'code' => 'G0006',
                'omschrijving' => 'AlleenGaande',
                'aantal_volwassenen' => 1,
                'aantal_kinderen' => 0,
                'aantal_babys' => 0,
                'totaal_aantal_personen' => 1
            ]
        ];

        foreach ($gezinnen as $gezin) {
            Gezin::create($gezin);
        }
    }
}

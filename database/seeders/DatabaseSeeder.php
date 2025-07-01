<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Maak eerst een test manager user aan
        User::factory()->create([
            'name' => 'Manager Hans',
            'email' => 'hans@maaskantje.nl',
        ]);

        // Voer alle seeders uit in de juiste volgorde
        $this->call([
            AllergieSeeder::class,
            GezinSeeder::class,
            PersoonSeeder::class,
            AllergiePerPersoonSeeder::class,
        ]);
    }
}

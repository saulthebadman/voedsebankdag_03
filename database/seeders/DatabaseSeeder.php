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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $leverancier = \App\Models\Leverancier::factory()->create([
            'naam' => 'kagan',
            'leverancier_type' => 'Particulier',
            'leveranciernummer' => 4,
        ]);

        \App\Models\Product::factory()->create([
            'leverancier_id' => $leverancier->id,
            'naam' => 'Test Product',
            'soort_allergie' => 'Gluten',
            'barcode' => '1234567890123',
            'houdbaarheidsdatum' => now()->addMonth()->toDateString(),
        ]);
    }
}

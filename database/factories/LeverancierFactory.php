<?php
namespace Database\Factories;

use App\Models\Leverancier;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeverancierFactory extends Factory
{
    protected $model = Leverancier::class;

    public function definition(): array
    {
        return [
            'naam' => $this->faker->name(),
            'leverancier_type' => 'Particulier',
            'leveranciernummer' => $this->faker->unique()->randomNumber(3),
        ];
    }
}

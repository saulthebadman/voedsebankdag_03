<?php
namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'leverancier_id' => 4, // voorbeeld leverancier_id
            'naam' => $this->faker->word(),
            'soort_allergie' => $this->faker->word(),
            'barcode' => $this->faker->ean13(),
            'houdbaarheidsdatum' => $this->faker->date(),
        ];
    }
}

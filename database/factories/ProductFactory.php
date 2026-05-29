<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $drinks = ['Coca-cola 150cl', 'Pepsi 200cl', 'Fanta 100cl', 'Malt 100cl', 'Chivita 100cl', 'Biggie Apple 200cl'];
        $piecesPerCrate = $this->faker->numberBetween(6, 24);
        $cratesAvailable = $this->faker->numberBetween(1, 100);
        
        return [
            'name' => $this->faker->randomElement($drinks),
            'category_id' => Category::inRandomOrder()->first()->id,
            // 'cost_per_unit' => $this
        ];
    }
}

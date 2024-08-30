<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'house_id' => fake()->numberBetween(1, 10),
            'house_description' => fake()->realTextBetween(10, 25),
            'quantity' => fake()->numberBetween(1, 100),
            'uom_id' => fake()->numberBetween(1, 5),
            'uom_abbreviation' => fake()->randomElement(['kg', 'g', 'm', 'cm', 'l']),
            'purchase_date' => fake()->dateTimeBetween('last year', 'today'),
            'expiration_date' => fake()->dateTimeBetween('next Monday', 'next Monday +7 days'),
            'catalog_id' => fake()->numberBetween(1, 20),
            'catalog_description' => fake()->sentence(),
            'brand_id' => fake()->numberBetween(1, 10),
            'brand_name' => fake()->realTextBetween(10, 25),
            'category_id' => fake()->numberBetween(1, 5),
            'category_name' => fake()->randomElement(['Fruits', 'Vegetables', 'Meat', 'Dairy', 'Beverages']),
        ];
    }
}

<?php

namespace ShopLocal\Core\Database\Factories;

use ShopLocal\Core\Models\Retailer;
use Winter\Storm\Database\Factories\Factory;

/**
 * ProductFactory Factory
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\ShopLocal\Core\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'retailer_id' => Retailer::factory(), // Automatically associates with a retailer
            'name' => $this->faker->unique()->words(3, true), // Generates a product name
            'description' => $this->faker->sentence(10), // Short product description
            'price' => $this->faker->randomFloat(2, 5, 500), // Price between $5 and $500
            'currency' => 'CAD', // Default to Canadian dollars
            'is_available' => $this->faker->boolean(90), // 90% chance product is available
            'metadata' => json_encode([
                'sku' => strtoupper($this->faker->bothify('PROD-###??')), // Random SKU format
                'weight' => $this->faker->randomFloat(2, 0.1, 10) . ' kg', // Random weight
                'dimensions' => [
                    'width' => $this->faker->randomFloat(2, 5, 50) . ' cm',
                    'height' => $this->faker->randomFloat(2, 5, 50) . ' cm',
                    'depth' => $this->faker->randomFloat(2, 1, 20) . ' cm',
                ],
            ]),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null
        ];
    }
}

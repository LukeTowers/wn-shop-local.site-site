<?php

namespace ShopLocal\Core\Database\Factories;

use Winter\Storm\Database\Factories\Factory;

/**
 * RetailerCategoryFactory Factory
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\ShopLocal\Core\Models\RetailerCategory>
 */
class RetailerCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $categories = [
            'Grocery', 'Clothing', 'Electronics', 'Furniture', 'Automotive',
            'Pharmacy', 'Hardware', 'Books', 'Toys', 'Jewelry', 'Restaurants',
            'Pet Supplies', 'Sporting Goods', 'Beauty & Personal Care', 'Home & Garden',
            'Office Supplies', 'Outdoor & Camping', 'Baby & Kids', 'Health & Wellness',
            'Music & Instruments', 'Art & Craft Supplies'
        ];

        return [
            'name' => $this->faker->unique()->randomElement($categories),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

<?php

namespace ShopLocal\Core\Database\Factories;

use ShopLocal\Core\Models\Retailer;
use ShopLocal\Core\Models\RetailerContact;
use Winter\Storm\Database\Factories\Factory;

/**
 * RetailerFactory Factory
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\ShopLocal\Core\Models\Retailer>
 */
class RetailerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // List of Canadian provinces (two-letter lowercase codes)
        $provinces = [
            'ab', 'bc', 'mb', 'nb', 'nl', 'ns', 'nt', 'nu', 'on', 'pe', 'qc', 'sk', 'yt'
        ];

        $province = $this->faker->randomElement($provinces);
        $city = $this->faker->city;

        // Generate lat/lng within Canada
        $latitude = $this->faker->randomFloat(6, 42.0, 83.0);  // Canada latitude range
        $longitude = $this->faker->randomFloat(6, -141.0, -52.0); // Canada longitude range

        return [
            'category_id' => $this->faker->numberBetween(1, 10),
            'name' => $this->faker->company,
            'lat' => $latitude,
            'lng' => $longitude,
            'city' => $city,
            'province' => $province,
            'metadata' => json_encode([
                'website' => $this->faker->url,
                'phone' => $this->faker->phoneNumber,
                'hours' => [
                    'mon-fri' => '9 AM - 6 PM',
                    'sat' => '10 AM - 4 PM',
                    'sun' => 'Closed'
                ]
            ]),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ];
    }

    /**
     * After creating a retailer, generate 5 contacts.
     */
    public function configure()
    {
        return $this->afterCreating(function (Retailer $retailer) {
            RetailerContact::factory()->count(5)->create([
                'retailer_id' => $retailer->id,
            ]);
        });
    }
}

<?php

namespace ShopLocal\Core\Database\Factories;

use ShopLocal\Core\Models\Retailer;
use ShopLocal\Core\Models\RetailerContact;
use Winter\Storm\Database\Factories\Factory;

/**
 * RetailerContactFactory Factory
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\RetailerContact>
 */
class RetailerContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Supported contact types
        $contactTypes = [
            'address', 'email', 'phone', 'website',
            'facebook', 'instagram', 'twitter', 'youtube',
            'tiktok', 'other'
        ];

        $type = $this->faker->randomElement($contactTypes);

        // Generate value based on contact type
        switch ($type) {
            case 'address':
                $value = $this->faker->address;
                break;
            case 'email':
                $value = $this->faker->safeEmail;
                break;
            case 'phone':
                $value = $this->faker->phoneNumber;
                break;
            case 'website':
                $value = $this->faker->url;
                break;
            case 'facebook':
                $value = 'https://facebook.com/' . $this->faker->userName;
                break;
            case 'instagram':
                $value = 'https://instagram.com/' . $this->faker->userName;
                break;
            case 'twitter':
                $value = 'https://twitter.com/' . $this->faker->userName;
                break;
            case 'youtube':
                $value = 'https://youtube.com/c/' . $this->faker->word;
                break;
            case 'tiktok':
                $value = 'https://www.tiktok.com/@' . $this->faker->userName;
                break;
            default: // 'other'
                $value = $this->faker->sentence(3);
                break;
        }

        return [
            'retailer_id' => Retailer::factory(), // Creates and links a Retailer
            'type' => $type,
            'value' => $value,
            'is_public' => $this->faker->boolean(80), // 80% chance to be public
            'metadata' => json_encode([
                'notes' => $this->faker->sentence,
                'verified' => $this->faker->boolean(50), // 50% chance verified
            ]),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null
        ];
    }
}

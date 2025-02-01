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
     * List of top 10 populated cities per province/territory in Canada.
     */
    protected static array $topCitiesByProvince = [
        'AB' => ['Calgary', 'Edmonton', 'Red Deer', 'Lethbridge', 'St. Albert', 'Medicine Hat', 'Grande Prairie', 'Airdrie', 'Spruce Grove', 'Leduc'],
        'BC' => ['Vancouver', 'Surrey', 'Burnaby', 'Richmond', 'Abbotsford', 'Coquitlam', 'Kelowna', 'Langley', 'Saanich', 'Nanaimo'],
        'MB' => ['Winnipeg', 'Brandon', 'Steinbach', 'Portage la Prairie', 'Thompson', 'Selkirk', 'Winkler', 'Dauphin', 'Morden', 'The Pas'],
        'NB' => ['Moncton', 'Saint John', 'Fredericton', 'Dieppe', 'Miramichi', 'Edmundston', 'Bathurst', 'Campbellton', 'Oromocto', 'Woodstock'],
        'NL' => ['St. John\'s', 'Conception Bay South', 'Mount Pearl', 'Paradise', 'Corner Brook', 'Grand Falls-Windsor', 'Gander', 'Portugal Cove-St. Philip\'s', 'Happy Valley-Goose Bay', 'Torbay'],
        'NS' => ['Halifax', 'Sydney', 'Truro', 'New Glasgow', 'Glace Bay', 'Kentville', 'Amherst', 'Bridgewater', 'Yarmouth', 'Greenwood'],
        'NT' => ['Yellowknife', 'Hay River', 'Inuvik', 'Fort Smith', 'Behchokǫ̀', 'Norman Wells', 'Fort Simpson', 'Tuktoyaktuk', 'Fort McPherson', 'Aklavik'],
        'NU' => ['Iqaluit', 'Arviat', 'Rankin Inlet', 'Baker Lake', 'Cambridge Bay', 'Pond Inlet', 'Igloolik', 'Kugluktuk', 'Gjoa Haven', 'Cape Dorset'],
        'ON' => ['Toronto', 'Ottawa', 'Mississauga', 'Brampton', 'Hamilton', 'London', 'Markham', 'Vaughan', 'Kitchener', 'Windsor'],
        'PE' => ['Charlottetown', 'Summerside', 'Stratford', 'Cornwall', 'Montague', 'Kensington', 'Souris', 'Alberton', 'Tignish', 'Georgetown'],
        'QC' => ['Montreal', 'Quebec City', 'Laval', 'Gatineau', 'Longueuil', 'Sherbrooke', 'Saguenay', 'Trois-Rivières', 'Lévis', 'Terrebonne'],
        'SK' => ['Saskatoon', 'Regina', 'Prince Albert', 'Moose Jaw', 'Yorkton', 'Swift Current', 'North Battleford', 'Estevan', 'Weyburn', 'Lloydminster'],
        'YT' => ['Whitehorse', 'Dawson City', 'Watson Lake', 'Haines Junction', 'Carcross', 'Teslin', 'Mayo', 'Faro', 'Carmacks', 'Burwash Landing']
    ];

    protected static array $canadianAreaCodes = [
        '204', '226', '236', '249', '250', '263', '289', '306', '343', '354',
        '365', '367', '368', '403', '416', '418', '431', '437', '438', '450',
        '468', '474', '506', '514', '519', '548', '579', '581', '584', '587',
        '604', '613', '639', '647', '672', '683', '705', '709', '742', '753',
        '778', '780', '782', '800', '807', '819', '825', '867', '873', '902', '905'
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Select a random province
        $province = $this->faker->randomElement(array_keys(self::$topCitiesByProvince));

        // Select a random city from that province
        $city = $this->faker->randomElement(self::$topCitiesByProvince[$province]);

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
     * Generate a random Canadian phone number with a valid area code.
     *
     * @return string
     */
    protected function generateCanadianPhoneNumber(): string
    {
        $areaCode = $this->faker->randomElement(self::$canadianAreaCodes);
        $prefix = $this->faker->numberBetween(200, 999);  // Ensures no leading 0 or 1
        $lineNumber = $this->faker->numberBetween(1000, 9999);

        return "1-{$areaCode}-{$prefix}-{$lineNumber}";
    }

    /**
     * After creating a retailer, generate 5 contacts.
     */
    public function configure()
    {
        return $this->afterCreating(function (Retailer $retailer) {
            // Required contact types (1 of each)
            $requiredContacts = [
                'address' => $this->faker->address,
                'email' => $this->faker->safeEmail,
                'phone' => $this->generateCanadianPhoneNumber(),
            ];

            foreach ($requiredContacts as $type => $value) {
                RetailerContact::factory()->create([
                    'retailer_id' => $retailer->id,
                    'type' => $type,
                    'value' => $value,
                ]);
            }

            // Optional contact types (1 to all)
            $optionalContactTypes = [
                'website', 'facebook', 'instagram', 'twitter', 'youtube', 'tiktok', 'other'
            ];

            // Randomly select how many optional contacts to create
            $numOptionalContacts = rand(1, count($optionalContactTypes));
            $selectedTypes = $this->faker->randomElements($optionalContactTypes, $numOptionalContacts);

            foreach ($selectedTypes as $type) {
                RetailerContact::factory()->create([
                    'retailer_id' => $retailer->id,
                    'type' => $type,
                    'value' => match ($type) {
                        'website' => $this->faker->url,
                        'facebook' => 'https://facebook.com/' . $this->faker->userName,
                        'instagram' => 'https://instagram.com/' . $this->faker->userName,
                        'twitter' => 'https://twitter.com/' . $this->faker->userName,
                        'youtube' => 'https://youtube.com/c/' . $this->faker->word,
                        'tiktok' => 'https://www.tiktok.com/@' . $this->faker->userName,
                        default => $this->faker->sentence(3),
                    },
                ]);
            }
        });
    }
}

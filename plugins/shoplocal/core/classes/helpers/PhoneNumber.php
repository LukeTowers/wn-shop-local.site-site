<?php

namespace ShopLocal\Core\Classes\Helpers;

use Propaganistas\LaravelPhone\PhoneNumber as PhoneNumberLib;
use Winter\Storm\Support\Str;

/**
 * Helper class to parse phone numbers
 *
 * @see https://stackoverflow.com/a/67810744/6652884
 * @see https://github.com/google/libphonenumber/blob/master/FALSEHOODS.md
 */
class PhoneNumber
{
    protected const EXTENSION_SEPARATOR = ';';

    /**
     * @see https://www.allareacodes.com/canadian_area_codes.htm
     */
    protected const CANADIAN_AREA_CODES = [
        '204' => 'Manitoba',
        '226' => 'London, ON',
        '236' => 'Vancouver, BC',
        '249' => 'Sudbury, ON',
        '250' => 'Kelowna, BC',
        '263' => 'Montreal, QC',
        '289' => 'Hamilton, ON',
        '306' => 'Saskatchewan',
        '343' => 'Ottawa, ON',
        '354' => 'Granby, QC',
        '365' => 'Hamilton, ON',
        '367' => 'Quebec, QC',
        '368' => 'Calgary, AB',
        '403' => 'Calgary, AB',
        '416' => 'Toronto, ON',
        '418' => 'Quebec, QC',
        '431' => 'Manitoba',
        '437' => 'Toronto, ON',
        '438' => 'Montreal, QC',
        '450' => 'Granby, QC',
        '468' => 'Sherbrooke, QC',
        '474' => 'Saskatchewan',
        '506' => 'New Brunswick',
        '514' => 'Montreal, QC',
        '519' => 'London, ON',
        '548' => 'London, ON',
        '579' => 'Granby, QC',
        '581' => 'Quebec, QC',
        '584' => 'Manitoba',
        '587' => 'Calgary, AB',
        '604' => 'Vancouver, BC',
        '613' => 'Ottawa, ON',
        '639' => 'Saskatchewan',
        '647' => 'Toronto, ON',
        '672' => 'Vancouver, BC',
        '683' => 'Sudbury, ON',
        '705' => 'Sudbury, ON',
        '709' => 'Newfoundland/Labrador',
        '742' => 'Hamilton, ON',
        '753' => 'Ottawa, ON',
        '778' => 'Vancouver, BC',
        '780' => 'Edmonton, AB',
        '782' => 'Nova Scotia/PE Island',
        '800' => 'Commercial',
        '807' => 'Kenora, ON',
        '819' => 'Sherbrooke, QC',
        '825' => 'Calgary, AB',
        '867' => 'Northern Canada',
        '873' => 'Sherbrooke, QC',
        '902' => 'Nova Scotia/PE Island',
        '905' => 'Hamilton, ON',
    ];

    /**
     * Attempt to parse the provided phone number as a Canadian phone number
     */
    public static function parse(string $number): ?string
    {
        if (Str::contains($number, ['@'])) {
            return null;
        }

        // Normalize by removing non-numeric characters
        $digitsOnly = static::onlyDigits($number);

        // Return null if no digits are present
        if (empty($digitsOnly)) {
            return null;
        }

        // Remove leading '1' if present
        if (substr($digitsOnly, 0, 1) === '1') {
            $digitsOnly = substr($digitsOnly, 1);
        }

        // Check for valid Canadian area code
        $areaCode = substr($digitsOnly, 0, 3);
        if (!array_key_exists($areaCode, self::CANADIAN_AREA_CODES)) {
            return null;
        }

        $number = self::parseExtension($number);

        try {
            $parts = explode(static::EXTENSION_SEPARATOR, $number);
            $number = (new PhoneNumberLib($parts[0], 'CA'))->formatE164();
            $number .= !empty($parts[1]) ? static::EXTENSION_SEPARATOR . $parts[1] : '';
        } catch (\Exception $e) {
            // return null;
            throw $e;
        }

        return $number;
    }

    /**
     * Split the provided number into the number and extension parts
     */
    public static function parseExtension(string $number): ?string
    {
        // Remove non-numeric characters, except for extension identifiers and +
        $normalizedCase = preg_replace('/[^\d+xextnsionp#]/i', '', $number);

        // Regex to extract extension
        $extRegex = '/(?:extension|ext|x|poste|#|opt)\s*(\d+)/i';
        $extension = '';
        if (preg_match($extRegex, $normalizedCase, $extMatches)) {
            $extension = static::onlyDigits($extMatches[1]);

            // Check for "123-456-7890 or 987-654-3210"
            if (strlen($extension) === 10) {
                $extension = false;
            }

            // Remove extension part from the number
            $normalizedCase = preg_replace($extRegex, '', $normalizedCase);
        }

        $normalizedCase = static::onlyDigits($normalizedCase);

        // Remove leading '1' if present
        if (substr($normalizedCase, 0, 1) === '1') {
            $normalizedCase = substr($normalizedCase, 1);
        }

        // Check for remaining surplus digits and use as extension
        if (strlen($normalizedCase) > 10) {
            $number = substr($normalizedCase, 0, 10);
            $extension = substr($normalizedCase, 10);
            $normalizedCase = $number;
        }

        return $normalizedCase . (!empty($extension) ? static::EXTENSION_SEPARATOR . $extension : '');
    }

    protected static function onlyDigits(string $number): string
    {
        return preg_replace('/[^\d]/', '', $number);
    }
}

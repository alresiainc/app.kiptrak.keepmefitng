<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class PhoneHelper
{
    /**
     * Format a phone number to international format.
     * Ensures it has the correct local length first, then adds the country code.
     *
     * @param string $phoneNumber The phone number to check.
     * @param string $defaultCountryCode The default country code (e.g., +234 for Nigeria).
     * @return string The formatted phone number without the '+'.
     */
    public static function formatToInternational($phoneNumber, $defaultCountryCode)
    {
        // Remove any whitespace from the phone number
        $phoneNumber = preg_replace('/\s+/', '', $phoneNumber);

        // Get supported countries from config
        $supportedCountries = Config::get('site.supported_countries');
        $defaultCountryCode = ltrim($defaultCountryCode ?: Config::get('site.default_country_code'), '+');

        // Remove any leading '+' and country code if present
        if (preg_match('/^\+' . preg_quote($defaultCountryCode) . '/', $phoneNumber)) {
            $phoneNumber = preg_replace('/^\+' . preg_quote($defaultCountryCode) . '/', '', $phoneNumber);
        } elseif (preg_match('/^' . preg_quote($defaultCountryCode) . '/', $phoneNumber)) {
            $phoneNumber = preg_replace('/^' . preg_quote($defaultCountryCode) . '/', '', $phoneNumber);
        }

        // Remove leading '0' and take the last 10 digits if the number starts with '0'
        if (preg_match('/^0\d+$/', $phoneNumber)) {
            $phoneNumber = substr($phoneNumber, -10); // Take the last 10 digits
        }

        // Validate the final number to ensure it is exactly 10 digits for Nigeria
        if (!preg_match('/^\d{10}$/', $phoneNumber)) {
            Log::warning('Invalid phone number length after processing.', ['phone' => $phoneNumber]);
            // Even if invalid, return the phone with the country code attached
            return $defaultCountryCode . $phoneNumber;
        }

        // Convert the local number to international format by adding the country code
        return $defaultCountryCode . $phoneNumber;
    }
}

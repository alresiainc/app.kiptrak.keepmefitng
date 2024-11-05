<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            ["name" => "Nigeria", "currency" => "Naira", "symbol" => "₦"],
            ["name" => "Ghana", "currency" => "Cedis", "symbol" => "GH₵"],
            ["name" => "Kenya", "currency" => "Shilling", "symbol" => "KES"],
            ["name" => "US", "currency" => "Dollar", "symbol" => "$"],
            ["name" => "UK", "currency" => "Pound", "symbol" => "£"],
            ["name" => "Canada", "currency" => "Canadian Dollar", "symbol" => "CAD$"],
            ["name" => "Australia", "currency" => "Australian Dollar", "symbol" => "A$"],
            ["name" => "India", "currency" => "Rupee", "symbol" => "₹"],
            ["name" => "Japan", "currency" => "Yen", "symbol" => "¥"],
            ["name" => "South Africa", "currency" => "Rand", "symbol" => "R"],
            ["name" => "Brazil", "currency" => "Real", "symbol" => "R$"],
            ["name" => "Mexico", "currency" => "Peso", "symbol" => "MX$"],
            ["name" => "China", "currency" => "Yuan", "symbol" => "¥"],
            ["name" => "Switzerland", "currency" => "Swiss Franc", "symbol" => "CHF"],
            ["name" => "Russia", "currency" => "Ruble", "symbol" => "₽"],
            ["name" => "South Korea", "currency" => "Won", "symbol" => "₩"],
            ["name" => "Saudi Arabia", "currency" => "Riyal", "symbol" => "SAR"],
            ["name" => "Turkey", "currency" => "Lira", "symbol" => "₺"],
            ["name" => "Singapore", "currency" => "Singapore Dollar", "symbol" => "S$"],
            ["name" => "United Arab Emirates", "currency" => "Dirham", "symbol" => "AED"],
            ["name" => "Eurozone", "currency" => "Euro", "symbol" => "€"],
        ];

        foreach ($countries as $countryData) {
            $country = new Country();
            $country->name = $countryData['name'];
            $country->currency = $countryData['currency'];
            $country->symbol = $countryData['symbol'];
            $country->created_by = 1;
            $country->status = 'true';
            $country->save();
        }
    }
}

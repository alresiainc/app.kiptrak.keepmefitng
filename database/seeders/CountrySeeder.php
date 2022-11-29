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
        $countries = array(
            array("name" => "Nigeria", "currency" => "Naira", "symbol" => "₦",),
            array("name" => "Ghana", "currency" => "Cedis", "symbol" => "GH₵",),
            array("name" => "Kenya", "currency" => "Shilling", "symbol" => "KES",),
            array("name" => "US", "currency" => "Dollar", "symbol" => "$",),
            array("name" => "UK", "currency" => "Pound", "symbol" => "£",),
        );
        
        //Nigeria
        $country = new Country();
        $country->name = 'Nigeria';
        $country->currency = 'Naira';
        $country->symbol = '₦';
        $country->created_by = 1;
        $country->status = 'true';
        $country->save();

        //Ghana
        $country = new Country();
        $country->name = 'Ghana';
        $country->currency = 'Cedis';
        $country->symbol = 'GH₵';
        $country->created_by = 1;
        $country->status = 'true';
        $country->save();
        
        //Kenya
        $country = new Country();
        $country->name = 'Kenya';
        $country->currency = 'Shilling';
        $country->symbol = 'KES';
        $country->created_by = 1;
        $country->status = 'true';
        $country->save();

        //US
        $country = new Country();
        $country->name = 'US';
        $country->currency = 'Dollar';
        $country->symbol = '$';
        $country->created_by = 1;
        $country->status = 'true';
        $country->save();

        //UK
        $country = new Country();
        $country->name = 'UK';
        $country->currency = 'Pound';
        $country->symbol = '£';
        $country->created_by = 1;
        $country->status = 'true';
        $country->save();

    }
}

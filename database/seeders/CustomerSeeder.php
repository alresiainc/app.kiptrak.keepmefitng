<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customer = new Customer();
        // $customer->order_id = $order->id;
        // $customer->form_holder_id = $formHolder->id;
        $customer->firstname = 'Jona';
        $customer->lastname = 'Jones';
        $customer->phone_number = '098765445678';
        $customer->whatsapp_phone_number = '09876456789';
        $customer->email = 'jona@email.com';
        $customer->password = Hash::make('password');
        $customer->city = 'Ikeja';
        $customer->state = 'Lagos';
        $customer->country_id = 1;
        $customer->delivery_address = '19 Jona Street, Adebayo Lane, Lagos';
        $customer->created_by = 1;
        $customer->status = 'true';
        $customer->save();
    }
}

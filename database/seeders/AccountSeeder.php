<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $account_no = 'kpa-' . date("Ymd") . '-'. date("his");
        $account = new Account();
        // $account->account_no = $account_no;
        $account->name = 'Sales Account';
        $account->initial_balance = 1000;
        $account->amount_added = 500;
        $account->total_balance = 1500;
        $account->note = 'For Sales';
        $account->created_by = 1;
        $account->status = 'true';
        $account->save();

        // $account_no = 'kpa-' . date("Ymd") . '-'. date("his");
        $account = new Account();
        // $account->account_no = $account_no;
        $account->name = 'Purchase Account';
        $account->initial_balance = 2000;
        $account->amount_added = 500;
        $account->total_balance = 2500;
        $account->note = 'For Purchases';
        $account->created_by = 1;
        $account->status = 'true';
        $account->save();
    }
}

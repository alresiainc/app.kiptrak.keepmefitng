<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Log::alert('running seeder');

        $this->call([
            UserSeeder::class,
            // ProductSeeder::class, //comment later
            // IncomingStockSeeder::class, //comment later
            // WareHouseSeeder::class, //comment later
            CountrySeeder::class,
            // SupplierSeeder::class,
            // CustomerSeeder::class,
            // AccountSeeder::class,
            // DepartmentSeeder::class,
            // EmployeeSeeder::class,
            CategorySeeder::class, //comment later
            GeneralSettingSeeder::class,
            //RoleSeeder::class,
            PermissionSeeder::class,
            MessageTemplateSeeder::class
        ]);
    }
}

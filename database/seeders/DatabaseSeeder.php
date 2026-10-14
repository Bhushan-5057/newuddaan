<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
      public function run()
    {
        $this->call(AdvancedInfoSeeder::class);
        $this->call(CompanyInfoSeeder::class);
        $this->call(PinDetailsSeeder::class);
        $this->call(PlanSeeder::class);
        $this->call(RolePermissionsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(WalletTypesSeeder::class);

    }
}
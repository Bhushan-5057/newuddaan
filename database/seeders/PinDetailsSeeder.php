<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PinDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pin_details')->insert([
            [
                'id' => 1,
                'pin_type' => 'Package',
                'package_name' => 'Basic',
                'vip' => 0,
                'pin_rate' => '100',
                'pin_rate2' => 99999999,
                'roi' => 0.5,
                'business_volumn' => 0,
                'pin_value' => 1,
                'status' => 1,
                'added_on' => '2020-04-02 10:00:32',
                'updated_on' => '2025-05-06 03:19:09',
            ],
            [
                'id' => 2,
                'pin_type' => 'subscription',
                'package_name' => 'Silver',
                'vip' => 0,
                'pin_rate' => '50',
                'pin_rate2' => 50,
                'roi' => 0,
                'business_volumn' => 0,
                'pin_value' => 1,
                'status' => 1,
                'added_on' => '2020-04-02 10:00:32',
                'updated_on' => '2024-12-24 11:17:20',
            ],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'username'=> 'qw121212',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin@123'), // hashed password
                'role' => 'super admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Regular User',
                'username'=> 'wq12121',
                'email' => 'user@gmail.com',
                'password' => Hash::make('123456'),
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
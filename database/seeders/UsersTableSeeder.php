<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Test User 1',
                'phone' => "0987654321",
                'password' => Hash::make('password1'),
                'role' => 'Admin',
                'warehouse_id' => 1
            ],
            [
                'name' => 'Test User 2',
                'phone' => "0987154321",
                'password' => Hash::make('password2'),
                'role' => 'Pharmacist',
                'warehouse_id' => 1,
            ],

        ]);
    }
}


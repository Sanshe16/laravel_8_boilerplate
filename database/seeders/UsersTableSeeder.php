<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'id' => '00678adf-4f47-473a-8ed2-514e621c71b1',
            'username' => 'Admin',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'status' => 'active',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make(123456),
            'remember_token' => Str::random(10),
        ]);

        \App\Models\User::create([
            'id' => '02f7d0b2-4014-4644-bd45-cf19de309c5e',
            'username' => 'Example',
            'first_name' => 'Example',
            'last_name' => 'Vendor',
            'status' => 'active',
            'email' => 'vendor@example.com',
            'company_name' => 'Bata',
            'job_title' => 'Shoes',
            'company_url' => 'vender@example.com',
            'address' => '356-M, Lahore',
            'fax' => '555-123-4567',
            'business_type_id' => 1,
            'email_verified_at' => now(),
            'password' => Hash::make(123456),
            'remember_token' => Str::random(10),
        ]);
        \App\Models\User::create([
            'id' => '092a782d-17a9-4d0e-bbef-94c0826875e5',
            'username' => 'haziq',
            'first_name' => 'Mr.',
            'last_name' => 'HaZiQ',
            'status' => 'active',
            'email' => 'haziq12@gmail.com',
            'company_name' => 'Services',
            'job_title' => 'Clothes',
            'company_url' => 'massmcqs.com',
            'address' => '355-M, Lahore',
            'fax' => '555-123-4567',
            'business_type_id' => 1,
            'email_verified_at' => now(),
            'password' => Hash::make(123456),
            'remember_token' => Str::random(10),
        ]);
       
    }
}

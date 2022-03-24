<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'phone' => '01742195643',
            'password' => Hash::make('password')
        ]);
    }
}

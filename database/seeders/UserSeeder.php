<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'username' => 'administrator1',
            'password' => Hash::make('12345678'), // Enkripsi password
            'type' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

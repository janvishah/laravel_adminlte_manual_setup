<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
        DB::table('users')->insert([
            'name' => 'abc',
            'email' => 'abc@vardaam.com',
            'role' => 'employee',
            'password' => Hash::make('abc@123'),
        ]);

        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => 'team@vardaam.com',
            'role' => 'admin',
            'password' => Hash::make('Vardaam@123'),
        ]);
    }
}

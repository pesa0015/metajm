<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name' => 'Tess',
            'email' => 'peters945@hotmail.com',
            'password' => '$2y$10$jjuwArwnBlBlk7dWmEJxQeFi8JGfE7roxMoqSvQEF64arVQIx1jaS',
            'last_name' => 'T Persson',
            'address' => 'Testgatan 1',
            'postal_code' => '99999',
            'city' => 'Stan',
            'country' => 'Sverige',
            'phone_number' => '0704903063'
        ]);
    }
}

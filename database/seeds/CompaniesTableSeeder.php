<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\companies;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            'name' => 'Ciceros hårvårdsateljé',
            'address' => 'Borgaregatan 15',
            'postal_code' => '41666',
            'lat' => '57.708956',
            'lng' => '11.973102',
            'hair' => 1,
            'nails' => 0,
            'dental' => 0,
            'tattoo' => 0,
            'city' => 'Göteborg',
            'tel' => '0704903063',
            'mail' => 'peters945@hotmail.com',
            'show_stylists' => 1,
            'password' => '$2y$10$jjuwArwnBlBlk7dWmEJxQeFi8JGfE7roxMoqSvQEF64arVQIx1jaS'
        ]);
    }
}

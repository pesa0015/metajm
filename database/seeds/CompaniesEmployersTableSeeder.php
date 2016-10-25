<?php

use Illuminate\Database\Seeder;

class CompaniesEmployersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies_employers')->insert(
        	[
            'first_name' => 'Peter',
            'last_name' => 'Sall',
            'email' => 'peters945@hotmail.com',
            'password' => '$2y$10$jjuwArwnBlBlk7dWmEJxQeFi8JGfE7roxMoqSvQEF64arVQIx1jaS',
            'admin_role' => '1',
            'company_id' => '1',
        	],
        	[
            'first_name' => 'Jakob',
            'last_name' => 'Andersson',
            'email' => 'j@gmail.com',
            'password' => '$2y$10$jjuwArwnBlBlk7dWmEJxQeFi8JGfE7roxMoqSvQEF64arVQIx1jaS',
            'admin_role' => '0',
            'company_id' => '1',
        	]
        );
    }
}

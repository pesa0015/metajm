<?php

use Illuminate\Database\Seeder;

class CompaniesEmployersServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies_employers_services')->insert([
            'employer_id' => '1',
            'service_id' => '1'
        ]);
    }
}

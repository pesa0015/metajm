<?php

use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->insert([
            'name' => 'Man',
            'price' => '150',
            'time' => '0.5',
            'category_id' => '1',
            'company_id' => '1'
        ]);
    }
}

<?php

use Illuminate\Database\Seeder;

class StylistServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stylist_services')->insert([
            'stylist_id' => '1',
            'service_id' => '1'
        ]);
    }
}

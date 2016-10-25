<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostalCodeAndSettingsToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('postal_code');
            $table->string('city');
            $table->float('lat', 10, 6);
            $table->float('lng', 10, 6);
            $table->boolean('hair');
            $table->boolean('nails');
            $table->boolean('dental');
            $table->boolean('tattoo');
            $table->string('tel');
            $table->string('mail');
            $table->string('password');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            //
        });
    }
}

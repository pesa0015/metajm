<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('address');
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
            $table->boolean('show_employers')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('companies');
    }
}

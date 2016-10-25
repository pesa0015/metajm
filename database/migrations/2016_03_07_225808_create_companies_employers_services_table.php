<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesEmployersServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies_employers_services', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employer_id')->unsigned()->nullable();
            $table->integer('service_id')->unsigned()->nullable();
            $table->foreign('employer_id')->references('id')->on('companies_employers');
            $table->foreign('service_id')->references('id')->on('services');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('companies_employers_services');
    }
}

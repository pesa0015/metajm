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
            $table->integer('company_id')->unsigned()->nullable();
            $table->foreign('employer_id')->references('id')->on('companies_employers');
            $table->foreign('service_id')->references('id')->on('services');
            $table->foreign('company_id')->references('id')->on('companies');
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
        Schema::table('companies_employers_services', function (Blueprint $table) {
            $table->dropForeign('companies_employers_services_employer_id_foreign');
            $table->dropForeign('companies_employers_services_service_id_foreign');
            $table->dropForeign('companies_employers_services_company_id_foreign');
        });

        Schema::drop('companies_employers_services');
    }
}

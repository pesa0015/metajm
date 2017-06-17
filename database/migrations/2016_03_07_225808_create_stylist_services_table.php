<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStylistServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stylist_services', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stylist_id')->unsigned()->nullable();
            $table->integer('service_id')->unsigned()->nullable();
            $table->integer('company_id')->unsigned()->nullable();
            $table->foreign('stylist_id')->references('id')->on('stylists');
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
        Schema::table('stylist_services', function (Blueprint $table) {
            $table->dropForeign('stylist_services_stylist_id_foreign');
            $table->dropForeign('stylist_services_service_id_foreign');
            $table->dropForeign('stylist_services_company_id_foreign');
        });

        Schema::drop('stylist_services');
    }
}

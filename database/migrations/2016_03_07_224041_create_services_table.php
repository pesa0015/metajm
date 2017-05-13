<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('price')->unsigned()->nullable();
            $table->integer('time')->unsigned()->nullable();
            $table->integer('category_id')->unsigned()->nullable();
            $table->integer('company_id')->unsigned()->nullable();
            $table->foreign('category_id')->references('id')->on('categories');
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
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign('services_category_id_foreign');
            $table->dropForeign('services_company_id_foreign');
        });

        Schema::drop('services');
    }
}

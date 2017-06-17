<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeLeftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_left', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('start')->nullable();
            $table->timestamp('close')->nullable();
            $table->integer('max_available_minutes')->nullable();
            $table->integer('company_id')->unsigned()->nullable();
            $table->integer('stylist_id')->unsigned()->nullable();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('stylist_id')->references('id')->on('stylists');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('time_left', function (Blueprint $table) {
            $table->dropForeign('time_left_company_id_foreign');
            $table->dropForeign('time_left_stylist_id_foreign');
        });

        Schema::drop('time_left');
    }
}

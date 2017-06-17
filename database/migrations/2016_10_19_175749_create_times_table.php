<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('times', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('timestamp');
            $table->integer('booking_id')->unsigned()->nullable();
            $table->integer('stylist_id')->unsigned()->nullable();
            $table->integer('company_id')->unsigned()->nullable();

            $table->foreign('booking_id')->references('id')->on('bookings');
            $table->foreign('stylist_id')->references('id')->on('stylists');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('times', function (Blueprint $table) {
            $table->dropForeign('times_booking_id_foreign');
            $table->dropForeign('times_stylist_id_foreign');
            $table->dropForeign('times_company_id_foreign');
        });

        Schema::drop('times');
    }
}

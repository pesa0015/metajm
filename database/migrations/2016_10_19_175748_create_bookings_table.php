<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('booked_at');
            $table->timestamp('start');
            $table->timestamp('end');
            $table->integer('payment');
            $table->integer('booked_by_user')->unsigned()->nullable();
            $table->integer('service_id')->unsigned()->nullable();
            $table->integer('company_id')->unsigned()->nullable();
            $table->integer('stylist_id')->unsigned()->nullable();

            $table->foreign('booked_by_user')->references('id')->on('users');
            $table->foreign('service_id')->references('id')->on('services');
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
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign('bookings_booked_by_user_foreign');
            $table->dropForeign('bookings_service_id_foreign');
            $table->dropForeign('bookings_company_id_foreign');
            $table->dropForeign('bookings_stylist_id_foreign');
        });
        
        Schema::drop('bookings');
    }
}

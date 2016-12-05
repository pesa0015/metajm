<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->timestamp('booked_at');
            $table->timestamp('start');
            $table->timestamp('end');
            $table->integer('payment');
            $table->integer('booked_by_user')->unsigned()->nullable();
            $table->integer('service_id')->unsigned()->nullable();
            $table->integer('company_id')->unsigned()->nullable();
            $table->integer('employer_id')->unsigned()->nullable();
            
            $table->foreign('booked_by_user')->references('id')->on('users');
            $table->foreign('service_id')->references('id')->on('services');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('employer_id')->references('id')->on('companies_employers');
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
            //
        });
    }
}

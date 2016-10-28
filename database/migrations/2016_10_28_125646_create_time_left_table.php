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
            $table->integer('employer_id')->unsigned()->nullable();

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
        Schema::drop('time_left');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesEmployersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies_employers', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('mail');
            $table->string('password');
            $table->boolean('admin_role');
            $table->integer('company_id')->unsigned()->nullable();
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
        Schema::drop('companies_employers');
    }
}

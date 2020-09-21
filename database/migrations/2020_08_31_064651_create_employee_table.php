<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee', function (Blueprint $table) {
            $table->id()->autoIncrement()->primary();
            $table->string('firstname');
            $table->string('middlename');
            $table->string('lastname');
            $table->string('mobileno');
            $table->string('birthdate');
            $table->string('gender');
            $table->string('profileImage');
            $table->string('street');
            $table->string('city');
            $table->string('country');
            $table->integer('roleId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('firstname');
            $table->string('middlename');
            $table->string('lastname');
            $table->string('mobileno');
            $table->date('birthdate');
            $table->string('email')->unique();
            $table->string('gender');
            $table->string('street');
            $table->string('city');
            $table->string('country');
            $table->integer('account_typeId');
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

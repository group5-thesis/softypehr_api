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
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('mobileno');
            $table->date('birthdate');
            $table->string('email', 191)->unique();
            $table->string('gender');
            $table->string('street');
            $table->string('city');
            $table->string('country');
            $table->integer('roleId');
            $table->integer('phil_health_no')->nullable();
            $table->integer('sss_no')->nullable();
            $table->integer('pag_ibig_no')->nullable();
            $table->integer('isActive')->default(1);
            $table->string('profile_img', 191)->nullable();
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

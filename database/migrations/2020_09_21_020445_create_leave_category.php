<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
<<<<<<< HEAD:database/migrations/2020_09_21_020445_create_leave_category.php
        Schema::create('leave_category', function (Blueprint $table) {
            $table->id();
            $table->string('type');
=======
        Schema::create('address', function (Blueprint $table) {
            $table->id()->autoIncrement()->primary();
            $table->string('street');
            $table->string('city');
            $table->string('country');
>>>>>>> 2f1d83608b56087f896c0c9b5935ba7d37190d2c:database/migrations/2020_09_07_140711_create_address.php
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_category');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('username');
            $table->string('password');
            $table->string('qr_code')->unique();
            $table->integer('account_type');
            $table->integer('is_deactivated')->default(0);
            $table->integer('is_password_changed')->default(0);
            $table->integer('employeeId')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}

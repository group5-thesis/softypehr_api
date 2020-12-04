<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateOfficeRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_request', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->integer('employeeId');
            $table->integer('approverId');
            $table->string('transaction_no');
            $table->string('item');
            $table->integer('quantity');
            $table->date('resolve_date')->nullable();
            $table->double('price');
            $table->double('total_price');
            $table->string('purpose');
            $table->date('date_needed');
            $table->integer('status')->default(1); // default for status 1 === Open 0 === Close
            $table->string('remarks')->nullable();
            // $table->timestamps()->default(Carbon::now());
            $table->date('created_at')->default(Carbon::now());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('office_request');
    }
}

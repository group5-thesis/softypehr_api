<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateTicketRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('transaction_no');
            $table->integer('employeeId');
            $table->string('item');
            $table->integer('quantity');
            $table->string('description');
            $table->date('resolve_date')->nullable();
            $table->integer('approverId');
            $table->integer('status')->default(1); // default for status 1 === Open 0 === Close
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('ticket');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerformanceReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('performance_review', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->date("date_reviewed")->default(Carbon::now());
            $table->string("criteria");
            $table->integer("employee_reviewed");
            $table->integer("reviewer");
            $table->double("ratings");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('performance_review');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

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
            // $table->string("criteria");
            // $table->integer("ratings");
            $table->integer("c1");
            $table->integer("c2");
            $table->integer("c3");
            $table->integer("c4");
            $table->integer("c5");
            $table->integer("employee_reviewed");
            $table->integer("reviewer");
            $table->date("date_reviewed")->default(Carbon::now());
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

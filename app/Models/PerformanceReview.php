<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceReview extends Model
{
    //
    protected $fillable = [
        "date_reviewed",
        "criteria",
        "employee_reviewed",
        "reviewer",
        "rating"
    ];

    protected $table = "performance_review";
}

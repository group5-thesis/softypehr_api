<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveCategory extends Model
{
    protected $fillable = [
        'type'
    ];

    protected $table = 'leave_category';
}

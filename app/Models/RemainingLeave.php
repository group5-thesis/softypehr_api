<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RemainingLeave extends Model
{
    //
    protected $fillable = [
        'employeeId',
        'no_of_days'
    ];

    public $timestamps = false;

    protected $table = 'remaining_leave';

}

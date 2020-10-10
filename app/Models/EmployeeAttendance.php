<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeAttendance extends Model
{
    //
    protected $fillable = [
        'employeeId',
        'time_in',
        'time_out',
        'date',
        'no_of_hours',
    ];

    public $timestamps = false;

    protected $table = 'employee_attendance';

}

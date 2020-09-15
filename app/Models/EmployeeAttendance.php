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
        'no_of_hours',
        'date'
    ];

    public $timestamps = false;

    protected $table = 'employee_attendance';

}

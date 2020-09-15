<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeProject extends Model
{
    //
    protected $fillable = [
        'employeeId',
        'projectId',
        'status'
    ];

    protected $table = 'employee_project';

}

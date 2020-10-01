<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDepartment extends Model
{
    protected $fillable = ['employeeId', 'departmentId'];

    public $timestamp = false;

    protected $table = 'employee_department';
}

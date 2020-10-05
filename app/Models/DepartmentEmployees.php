<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentEmployees extends Model
{
    protected $fillable = ['employeeId', 'department_managerId', 'departmentId'];

    public $timestamp = false;

    protected $table = 'department_employees';
}

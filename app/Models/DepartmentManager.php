<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentManager extends Model
{
    protected $fillable = ['departmentId', 'department_manager'];

    protected $table = 'department_manager';
}

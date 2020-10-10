<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentHead extends Model
{
    protected $fillable = ['departmentId', 'department_head'];

    protected $table = 'department_head';
}

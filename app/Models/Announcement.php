<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'employeeId',
        'title',
        'description'
    ];

    protected $table = 'employee_project';
}

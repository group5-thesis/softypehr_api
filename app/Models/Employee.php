<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'firstname',
        'middlename',
        'lastname' ,
        'mobileno',
        'birthdate',
        'email',
        'gender',
        'street',
        'city',
        'country',
        'profile_img',
        'phil_health_no',
        'sss_no',
        'pag_ibig_no',
        'isActive',
        'roleId'
    ];

    protected $table = 'employee';

    public $timestamps = false;
}

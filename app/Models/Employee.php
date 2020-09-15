<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    protected $fillable = [
        'firstname',
        'middlename',
        'lastname' ,
        'mobileno',
        'birthdate',
        'email',
        'gender',
        'profileImage',
        'street',
        'city',
        'country',
        'roleId'
    ];

    protected $table = 'employee';

    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    protected $fillable = ['firstname', 'middlename', 'lastname', 'birthdate', 'email', 'gender', 'image', 'address', 'street','city', 'roleID'];

    public $timestamps = false;

}

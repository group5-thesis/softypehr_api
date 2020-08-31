<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //
    protected $fillable = ['username','password' ,'email', 'employeeID'];

    protected $hidden = ['password'];

    protected $table = 'user';

    public $timestamps = false;
}

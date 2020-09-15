<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    protected $fillable = ['position'];

    public $timestamp = false;

    protected $table = 'role';

}

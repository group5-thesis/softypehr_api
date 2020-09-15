<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    //
    protected $fillable = ['street', 'city', 'countrty'];

    public $timestamps = false;

    protected $table = 'address';

}

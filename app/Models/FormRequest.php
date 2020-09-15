<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormRequest extends Model
{
    //
    protected $fillable = [
        'employeeId',
        'name',
        'item',
        'quantity',
        'status',
    ];

    protected $table = 'form_request';

}

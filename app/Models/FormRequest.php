<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormRequest extends Model
{
    //
    protected $fillable = [
        'employeeId',
        'title',
        'item',
        'quantity',
        'resolve_date',
        'approver',
        'status',
    ];

    protected $table = 'form_request';

}

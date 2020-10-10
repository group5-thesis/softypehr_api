<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    //
    protected $fillable = [
        'employeeId',
        'title',
        'item',
        'quantity',
        'resolve_date',
        'approverId',
        'status',
        'remarks'
    ];

    protected $table = 'ticket';
}

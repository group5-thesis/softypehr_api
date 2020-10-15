<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    //
    protected $fillable = [
        'transaction_no',
        'employeeId',
        'item',
        'quantity',
        'description',
        'resolve_date',
        'approverId',
        'status',
        'remarks',
        'date'
    ];

    protected $table = 'ticket';
}

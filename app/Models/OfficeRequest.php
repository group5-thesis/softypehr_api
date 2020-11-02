<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficeRequest extends Model
{
    //
    protected $fillable = [
        'employeeId',
        'approverId',
        'transaction_no',
        'item',
        'quantity',
        'resolve_date',
        'price',
        'total_price',
        'status',
        'date_needed',
        'remarks'
    ];
    protected $table = 'office_request';
}

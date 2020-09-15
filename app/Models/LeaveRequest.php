<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    //
    protected $fillable = [
        'employeeId',
        'date_from',
        'date_to',
        'reason',
        'type',
        'status',
        'approver',
        'date_approved',
        'remarks'
    ];

    protected $table = 'leave_request';

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarInvites extends Model
{
    protected $fillable = [
        'meetingId',
        'memberId'
    ];

    protected $table = 'calendar_invites';

}

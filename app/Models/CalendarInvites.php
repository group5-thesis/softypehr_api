<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarInvites extends Model
{
    protected $fillable = [
        'meetingId',
        'members'
    ];

    protected $table = 'calendar_invites';

}

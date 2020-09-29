<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'title',
        'organizer',
        'category',
        'description',
        'set_date',
        'time_start',
        'time_end',
        'status'
    ];

    protected $table = 'meeting';
}

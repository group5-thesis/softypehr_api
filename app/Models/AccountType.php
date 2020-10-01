<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    protected $fillable = ['type'];

    public $timestamp = false;

    protected $table = 'account_type';
}

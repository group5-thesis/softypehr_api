<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyRepository extends Model
{
    //
    protected $fillable = [
        'fileId',
        'url',
        'employeeId'
    ];

    protected $table = 'company_repository';

}

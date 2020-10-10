<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyRepository extends Model
{
    //
    protected $fillable = [
        'uploadedBy',
        'path',
        'type',
        'description'
    ];

    protected $table = 'company_repository';

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'civil_id',
        'passport_number',
        'gender',
        'marital_status',
        'phone',
        'civil_id_front',
        'civil_id_back',
        'residency_start',
        'residency_end',
        'job_title',
        'type',
    ];
}

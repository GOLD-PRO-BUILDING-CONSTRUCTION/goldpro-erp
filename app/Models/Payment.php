<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_number',
        'payment_date',
        'status',
        'amount',
        'percentage',
        'project_id',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

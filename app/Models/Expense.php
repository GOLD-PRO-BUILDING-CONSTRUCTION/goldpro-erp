<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'amount',
        'description',
        'receiver_name',
        'accountant_id',
        'date',
    ];

    // علاقة "ينتمي إلى" مع مشروع
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // علاقة "ينتمي إلى" مع موظف (المحاسب)
    public function user()
    {
        return $this->belongsTo(User::class, 'accountant_id');
    }
}

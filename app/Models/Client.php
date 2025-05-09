<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    // إضافة الحقول القابلة للتعبئة
    protected $fillable = [
        'name',        // إضافة حقل name
        'email',
        'phone',
        'address',
        'civil_id',    // إذا كنت قد أضفت حقل civil_id
    ];

    // إذا كنت تستخدم الحقول المحمية فقط (guarded) بدلاً من fillable، يمكنك تعديلها بهذا الشكل:
    // protected $guarded = [];
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

}

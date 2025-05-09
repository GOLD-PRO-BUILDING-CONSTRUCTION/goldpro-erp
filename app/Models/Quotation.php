<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [
        'client_id',
        'date',
        'plote_number',
        'quotation_number',
        'unit_value',
        'unit_of_measurement',
        'service_type',
        'file',
    ];

    protected static function booted(): void
    {
        static::creating(function ($quotation) {
            // إذا كان لم يُحدد رقم عرض السعر يدويًا
            if (empty($quotation->quotation_number)) {
                $year = now()->year;

                // الحصول على آخر رقم تسلسلي تم إصداره لهذه السنة
                $lastNumber = static::whereYear('created_at', $year)
                    ->where('quotation_number', 'LIKE', 'Q' . $year . '%')
                    ->orderBy('id', 'desc')
                    ->first();

                // تحديد الرقم التالي
                $nextNumber = 1;

                if ($lastNumber && preg_match('/Q' . $year . '(\d+)/', $lastNumber->quotation_number, $matches)) {
                    $nextNumber = intval($matches[1]) + 1;
                }

                // تعيين رقم عرض السعر
                $quotation->quotation_number = 'Q' . $year . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            }
        });
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}

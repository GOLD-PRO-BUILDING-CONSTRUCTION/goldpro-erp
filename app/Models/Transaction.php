<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $fillable = [
        'bank_account_id',
        'date',
        'type',
        'amount',
        'description',
        'accountant_id',
    ];

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    // في موديل Transaction.php
    public function user()
    {
        return $this->belongsTo(User::class, 'accountant_id');
    }


}

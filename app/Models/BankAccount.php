<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'account_number',
        'iban',
        'bank_name',
        'balance',
    ];
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

}

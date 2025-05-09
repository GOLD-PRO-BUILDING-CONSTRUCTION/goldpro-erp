<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    protected $fillable = [
        'client_id',
        'name',
        'address',
        'contract_number',
        'project_number',
        'contract_type',
        'contract_value',
        'description',
    ];

    protected static function booted(): void
    {
        static::creating(function (Project $project) {
            if (empty($project->contract_number)) {
                $year = date('Y');

                $lastContract = self::whereYear('created_at', $year)
                    ->whereNotNull('contract_number')
                    ->orderByDesc('id')
                    ->value('contract_number');

                $lastSerial = 0;

                if ($lastContract && preg_match('/C' . $year . '(\d{4})/', $lastContract, $matches)) {
                    $lastSerial = (int) $matches[1];
                }

                $newSerial = str_pad($lastSerial + 1, 4, '0', STR_PAD_LEFT);
                $project->contract_number = 'C' . $year . $newSerial;
            }
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}

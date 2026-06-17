<?php

namespace App\Models;

use Database\Factories\RetentionRateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetentionRate extends Model
{
    /** @use HasFactory<RetentionRateFactory> */
    use HasFactory;

    protected $fillable = [
        'team_id',
        'code',
        'name',
        'rate',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'rate' => 'decimal:4',
            'is_active' => 'boolean',
        ];
    }
}

<?php

namespace App\Models;

use Database\Factories\ServiceRateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceRate extends Model
{
    /** @use HasFactory<ServiceRateFactory> */
    use HasFactory;

    protected $fillable = [
        'team_id',
        'service_id',
        'currency_id',
        'pricing_type',
        'base_price',
        'starts_on',
        'ends_on',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'starts_on' => 'date',
            'ends_on' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id', 'Id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'Id');
    }
}

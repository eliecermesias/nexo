<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanItem extends Model
{
    protected $primaryKey = 'Id';

    protected $fillable = [
        'plans_Id',
        'services_Id',
        'quantity',
        'unit_price',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'unit_price' => 'decimal:2',
        ];
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plans_Id', 'Id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'services_Id', 'Id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationItem extends Model
{
    protected $primaryKey = 'Id';

    protected $fillable = [
        'quotations_Id',
        'services_Id',
        'plans_Id',
        'taxes_Id',
        'description',
        'quantity',
        'unit_price',
        'discount_rate',
        'tax_rate',
        'line_total',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'discount_rate' => 'decimal:4',
            'tax_rate' => 'decimal:4',
            'line_total' => 'decimal:2',
        ];
    }

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class, 'quotations_Id', 'Id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'services_Id', 'Id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plans_Id', 'Id');
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class, 'taxes_Id', 'Id');
    }
}

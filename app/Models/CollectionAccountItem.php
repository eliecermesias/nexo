<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectionAccountItem extends Model
{
    protected $primaryKey = 'Id';

    protected $fillable = [
        'collection_accounts_Id',
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

    public function collectionAccount(): BelongsTo
    {
        return $this->belongsTo(CollectionAccount::class, 'collection_accounts_Id', 'Id');
    }
}

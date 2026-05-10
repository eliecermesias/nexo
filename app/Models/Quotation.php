<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quotation extends Model
{
    protected $primaryKey = 'Id';

    protected $fillable = [
        'enterprises_Id',
        'parties_Id',
        'contacts_Id',
        'currencies_Id',
        'document_statuses_Id',
        'number',
        'issue_date',
        'valid_until',
        'subtotal',
        'discount_total',
        'tax_total',
        'total',
        'term',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'valid_until' => 'date',
            'subtotal' => 'decimal:2',
            'discount_total' => 'decimal:2',
            'tax_total' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function issuerEnterprise(): BelongsTo
    {
        return $this->belongsTo(Enterprise::class, 'enterprises_Id', 'Id');
    }

    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class, 'parties_Id', 'Id');
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contacts_Id', 'Id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currencies_Id', 'Id');
    }

    public function documentStatus(): BelongsTo
    {
        return $this->belongsTo(DocumentStatus::class, 'document_statuses_Id', 'Id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class, 'quotations_Id', 'Id');
    }
}

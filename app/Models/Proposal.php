<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proposal extends Model
{
    protected $primaryKey = 'Id';

    protected $fillable = [
        'quotations_Id',
        'enterprises_Id',
        'parties_Id',
        'contacts_Id',
        'currencies_Id',
        'document_statuses_Id',
        'number',
        'title',
        'issue_date',
        'valid_until',
        'subtotal',
        'discount_total',
        'tax_total',
        'total',
        'scope',
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

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class, 'quotations_Id', 'Id');
    }

    public function issuerEnterprise(): BelongsTo
    {
        return $this->belongsTo(Enterprise::class, 'enterprises_Id', 'Id');
    }

    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class, 'parties_Id', 'Id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProposalItem::class, 'proposals_Id', 'Id');
    }
}

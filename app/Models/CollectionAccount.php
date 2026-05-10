<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollectionAccount extends Model
{
    protected $primaryKey = 'Id';

    protected $fillable = [
        'proposals_Id',
        'enterprises_Id',
        'parties_Id',
        'contacts_Id',
        'currencies_Id',
        'document_statuses_Id',
        'number',
        'issue_date',
        'due_date',
        'subtotal',
        'discount_total',
        'tax_total',
        'total',
        'paid_total',
        'balance',
        'concept',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'due_date' => 'date',
            'subtotal' => 'decimal:2',
            'discount_total' => 'decimal:2',
            'tax_total' => 'decimal:2',
            'total' => 'decimal:2',
            'paid_total' => 'decimal:2',
            'balance' => 'decimal:2',
        ];
    }

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class, 'proposals_Id', 'Id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(CollectionAccountItem::class, 'collection_accounts_Id', 'Id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'collection_accounts_Id', 'Id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(CollectionAccountAttachment::class, 'collection_accounts_Id', 'Id');
    }
}

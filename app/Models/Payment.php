<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $primaryKey = 'Id';

    protected $fillable = [
        'payment_methods_Id',
        'payment_destinations_Id',
        'collection_accounts_Id',
        'invoices_Id',
        'currencies_Id',
        'reference',
        'paid_at',
        'amount',
        'payer_name',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
            'amount' => 'decimal:2',
        ];
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_methods_Id', 'Id');
    }

    public function paymentDestination(): BelongsTo
    {
        return $this->belongsTo(PaymentDestination::class, 'payment_destinations_Id', 'Id');
    }

    public function collectionAccount(): BelongsTo
    {
        return $this->belongsTo(CollectionAccount::class, 'collection_accounts_Id', 'Id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoices_Id', 'Id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currencies_Id', 'Id');
    }
}

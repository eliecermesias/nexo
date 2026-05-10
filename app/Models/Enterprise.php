<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enterprise extends Model
{
    protected $primaryKey = 'Id';

    protected $fillable = [
        'document_types_Id',
        'document_number',
        'legal_name',
        'trade_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'tax_regime',
    ];

    public function getNameAttribute(): ?string
    {
        return $this->legal_name;
    }

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class, 'document_types_Id', 'Id');
    }

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(BankAccount::class, 'enterprises_Id', 'Id');
    }

    public function paymentDestinations(): HasMany
    {
        return $this->hasMany(PaymentDestination::class, 'enterprises_Id', 'Id');
    }

    public function documentTemplates(): HasMany
    {
        return $this->hasMany(DocumentTemplate::class, 'enterprises_Id', 'Id');
    }
}

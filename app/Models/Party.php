<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Party extends Model
{
    protected $primaryKey = 'Id';

    protected $fillable = [
        'document_types_Id',
        'document_number',
        'party_type',
        'legal_name',
        'trade_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'is_customer',
        'is_supplier',
    ];

    protected function casts(): array
    {
        return [
            'is_customer' => 'boolean',
            'is_supplier' => 'boolean',
        ];
    }

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class, 'document_types_Id', 'Id');
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'parties_Id', 'Id');
    }
}

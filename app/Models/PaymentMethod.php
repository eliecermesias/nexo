<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    protected $primaryKey = 'Id';

    protected $fillable = [
        'code',
        'name',
        'requires_bank_account',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'requires_bank_account' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function paymentDestinations(): HasMany
    {
        return $this->hasMany(PaymentDestination::class, 'payment_methods_Id', 'Id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'payment_methods_Id', 'Id');
    }
}

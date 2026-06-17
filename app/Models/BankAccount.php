<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankAccount extends Model
{
    use HasFactory;

    protected $primaryKey = 'Id';

    protected $fillable = [
        'team_id',
        'created_by',
        'updated_by',
        'enterprises_Id',
        'banks_Id',
        'currencies_Id',
        'account_type',
        'account_number',
        'account_holder',
        'swift_code',
        'routing_number',
        'is_default',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function enterprise(): BelongsTo
    {
        return $this->belongsTo(Enterprise::class, 'enterprises_Id', 'Id');
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'banks_Id', 'Id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currencies_Id', 'Id');
    }

    public function paymentDestinations(): HasMany
    {
        return $this->hasMany(PaymentDestination::class, 'bank_accounts_Id', 'Id');
    }
}

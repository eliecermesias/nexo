<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentDestination extends Model
{
    use HasFactory;

    protected $primaryKey = 'Id';

    protected $fillable = [
        'team_id',
        'created_by',
        'updated_by',
        'enterprises_Id',
        'payment_methods_Id',
        'bank_accounts_Id',
        'name',
        'cash_location',
        'check_payee_name',
        'instruction',
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

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_methods_Id', 'Id');
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'bank_accounts_Id', 'Id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'payment_destinations_Id', 'Id');
    }
}

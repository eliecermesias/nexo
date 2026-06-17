<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    use HasFactory;

    protected $primaryKey = 'Id';

    protected $fillable = [
        'code',
        'name',
        'country',
    ];

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(BankAccount::class, 'banks_Id', 'Id');
    }
}

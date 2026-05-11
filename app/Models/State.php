<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    use HasFactory;

    protected $primaryKey = 'Id';

    protected $fillable = [
        'countries_Id',
        'code',
        'name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'countries_Id', 'Id');
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'states_Id', 'Id');
    }

    public function enterprises(): HasMany
    {
        return $this->hasMany(Enterprise::class, 'states_Id', 'Id');
    }
}

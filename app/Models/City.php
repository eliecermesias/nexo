<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    protected $primaryKey = 'Id';

    protected $fillable = [
        'states_Id',
        'code',
        'name',
        'type',
        'longitude',
        'latitude',
        'is_capital',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_capital' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'states_Id', 'Id');
    }

    public function enterprises(): HasMany
    {
        return $this->hasMany(Enterprise::class, 'cities_Id', 'Id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $primaryKey = 'Id';

    protected $fillable = [
        'code',
        'name',
        'rate',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'rate' => 'decimal:4',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return Attribute<string, never>
     */
    protected function name(): Attribute
    {
        return Attribute::get(fn (string $value): string => __($value));
    }
}

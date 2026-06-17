<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class DocumentStatus extends Model
{
    protected $primaryKey = 'Id';

    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    /**
     * @return Attribute<string, never>
     */
    protected function name(): Attribute
    {
        return Attribute::get(fn (string $value): string => __($value));
    }
}

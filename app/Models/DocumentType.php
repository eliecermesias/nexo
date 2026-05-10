<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentType extends Model
{
    protected $primaryKey = 'Id';

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function enterprises(): HasMany
    {
        return $this->hasMany(Enterprise::class, 'document_types_Id', 'Id');
    }

    public function parties(): HasMany
    {
        return $this->hasMany(Party::class, 'document_types_Id', 'Id');
    }
}

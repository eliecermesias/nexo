<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $primaryKey = 'Id';

    protected $fillable = [
        'team_id',
        'created_by',
        'updated_by',
        'code',
        'name',
        'description',
        'unit',
        'unit_price',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function planItems(): HasMany
    {
        return $this->hasMany(PlanItem::class, 'services_Id', 'Id');
    }
}

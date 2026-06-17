<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
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
        'billing_period',
        'price',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(PlanItem::class, 'plans_Id', 'Id');
    }
}

<?php

namespace App\Models;

use Database\Factories\InternalSequenceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InternalSequence extends Model
{
    /** @use HasFactory<InternalSequenceFactory> */
    use HasFactory;

    protected $fillable = [
        'team_id',
        'client_enterprise_id',
        'document_type',
        'prefix',
        'initial_value',
        'final_value',
        'current_value',
        'number_length',
        'number_format',
        'suffix',
        'padding',
        'resets_yearly',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'initial_value' => 'integer',
            'final_value' => 'integer',
            'current_value' => 'integer',
            'number_length' => 'integer',
            'padding' => 'integer',
            'resets_yearly' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function clientEnterprise(): BelongsTo
    {
        return $this->belongsTo(Enterprise::class, 'client_enterprise_id', 'Id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(SequenceHistory::class);
    }
}

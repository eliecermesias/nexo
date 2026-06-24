<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SequenceHistory extends Model
{
    protected $fillable = [
        'internal_sequence_id',
        'sequence_counter_id',
        'document_number',
        'numeric_value',
        'documentable_type',
        'documentable_id',
        'generated_by',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'numeric_value' => 'integer',
            'generated_at' => 'datetime',
        ];
    }

    public function internalSequence(): BelongsTo
    {
        return $this->belongsTo(InternalSequence::class);
    }

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }
}

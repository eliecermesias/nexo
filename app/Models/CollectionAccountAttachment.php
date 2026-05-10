<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectionAccountAttachment extends Model
{
    protected $primaryKey = 'Id';

    protected $fillable = [
        'collection_accounts_Id',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'description',
        'uploaded_at',
    ];

    protected function casts(): array
    {
        return [
            'uploaded_at' => 'datetime',
        ];
    }

    public function collectionAccount(): BelongsTo
    {
        return $this->belongsTo(CollectionAccount::class, 'collection_accounts_Id', 'Id');
    }
}

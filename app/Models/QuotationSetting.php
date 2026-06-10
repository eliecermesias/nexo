<?php

namespace App\Models;

use Database\Factories\QuotationSettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationSetting extends Model
{
    /** @use HasFactory<QuotationSettingFactory> */
    use HasFactory;

    protected $primaryKey = 'Id';

    protected $fillable = [
        'team_id',
        'created_by',
        'updated_by',
        'enterprises_Id',
        'document_template_versions_Id',
        'default_term',
        'default_note',
        'validity_days',
    ];

    public function templateVersion(): BelongsTo
    {
        return $this->belongsTo(DocumentTemplateVersion::class, 'document_template_versions_Id', 'Id');
    }

    public function enterprise(): BelongsTo
    {
        return $this->belongsTo(Enterprise::class, 'enterprises_Id', 'Id');
    }
}

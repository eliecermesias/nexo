<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $primaryKey = 'Id';

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'decimal_place',
    ];
}

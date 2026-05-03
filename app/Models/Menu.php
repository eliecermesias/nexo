<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'menu_id',
        'name',
        'icon',
        'url',
        'current',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
    public function children()
    {
        return $this->hasMany(Menu::class, 'menu_id');
    }
}

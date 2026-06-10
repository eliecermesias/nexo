<?php

namespace App\Http\ViewComposer;

use App\Models\Menu;
use Illuminate\Contracts\View\View;

class MenuComposer
{
    public function compose(View $view)
    {
        $menu = Menu::with('children')->orderBy('priority', 'asc')->whereNull('menu_id')->get();
        $view->with('menu', $menu);
    }
}

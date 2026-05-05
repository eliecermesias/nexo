<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menu = new Menu();
        $menus = [
            [
                'name' => __('Dashboard'),
                'url' => route('dashboard'),
                'icon' => 'home',
            ],
            [
                'name' => __('Enterprises'),
                'url' => route('enterprises.index', ['current_team' => 'default-team']),
                'icon' => 'building-office',
            ],
        ];
        foreach ($menus as $menu) {
            \App\Models\Menu::create($menu);
        }
    }
}

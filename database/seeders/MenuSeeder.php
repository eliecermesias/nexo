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
                'name' => 'Dashboard',
                'url' => route('dashboard', ['current_team' => 'default-team']),
                'icon' => 'fas fa-home',
            ],
            [
                'name' => 'Empresas',
                'url' => route('enterprises.index', ['current_team' => 'default-team']),
                'icon' => 'fas fa-building',
            ],
            [
                'name' => 'Usuarios',
                'url' => '/users',
                'icon' => 'fas fa-user-lock',
            ],
            [
                'name' => 'Roles',
                'url' => '/roles',
                'icon' => 'far fa-id-badge',
            ],
        ];
        foreach ($menus as $menu) {
            \App\Models\Menu::create($menu);
        }
    }
}

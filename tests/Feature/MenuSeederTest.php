<?php

namespace Tests\Feature;

use App\Models\Menu;
use Database\Seeders\MenuSeeder;
use Illuminate\Support\Facades\App;
use ReflectionClass;
use Tests\TestCase;

class MenuSeederTest extends TestCase
{
    public function test_menu_seeder_defines_the_proposed_menu_tree_with_available_heroicons(): void
    {
        $menus = $this->seederMenus();
        $flattenedMenus = $this->flattenMenus($menus);

        $this->assertSame(7, count($menus));
        $this->assertSame(32, count($flattenedMenus));
        $this->assertSame(
            ['Dashboard', 'Commercial', 'Customers', 'Catalog', 'Payments Setup', 'Documents', 'Settings'],
            array_column($menus, 'name'),
        );

        $commercial = collect($menus)->firstWhere('name', 'Commercial');

        $this->assertSame('briefcase', $commercial['icon']);
        $this->assertSame('#', $commercial['url']);
        $this->assertSame(20, $commercial['priority']);
        $this->assertSame(
            ['Quotations', 'Proposals', 'Collection Accounts', 'Invoices', 'Payments'],
            array_column($commercial['children'], 'name'),
        );

        $availableIcons = $this->availableFluxIcons();

        foreach ($flattenedMenus as $menu) {
            $this->assertContains($menu['icon'], $availableIcons);
            $this->assertSame('#', $menu['url']);
        }
    }

    public function test_menu_name_is_translated_when_read_from_the_model(): void
    {
        App::setLocale('es');

        $menu = new Menu;
        $menu->setRawAttributes(['name' => 'Commercial'], true);

        $this->assertSame('Comercial', $menu->name);
        $this->assertSame('Commercial', $menu->getRawOriginal('name'));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function seederMenus(): array
    {
        $reflection = new ReflectionClass(MenuSeeder::class);
        $method = $reflection->getMethod('menus');

        return $method->invoke(new MenuSeeder);
    }

    /**
     * @param  array<int, array<string, mixed>>  $menus
     * @return array<int, array<string, mixed>>
     */
    private function flattenMenus(array $menus): array
    {
        return collect($menus)
            ->flatMap(fn (array $menu): array => [
                collect($menu)->except('children')->all(),
                ...$this->flattenMenus($menu['children'] ?? []),
            ])
            ->all();
    }

    /**
     * @return array<int, string>
     */
    private function availableFluxIcons(): array
    {
        $iconPaths = glob(base_path('vendor/livewire/flux/stubs/resources/views/flux/icon/*.blade.php'));

        $this->assertNotFalse($iconPaths);

        return collect($iconPaths)
            ->map(fn (string $path): string => str($path)->basename('.blade.php')->toString())
            ->all();
    }
}

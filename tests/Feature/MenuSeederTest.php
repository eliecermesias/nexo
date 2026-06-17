<?php

namespace Tests\Feature;

use App\Models\Menu;
use Database\Seeders\MenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use ReflectionClass;
use Tests\TestCase;

class MenuSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_menu_seeder_defines_the_proposed_menu_tree_with_available_heroicons(): void
    {
        $menus = $this->seederMenus();
        $flattenedMenus = $this->flattenMenus($menus);

        $this->assertSame(8, count($menus));
        $this->assertSame(50, count($flattenedMenus));
        $this->assertSame(
            ['Dashboard', 'Commercial', 'Customers', 'Catalog', 'Compliance', 'Payments Setup', 'Documents', 'Settings'],
            array_column($menus, 'name'),
        );

        $commercial = collect($menus)->firstWhere('name', 'Commercial');

        $this->assertSame('briefcase', $commercial['icon']);
        $this->assertSame('#', $commercial['url']);
        $this->assertSame(20, $commercial['priority']);
        $this->assertSame(
            ['Quotations', 'Proposals', 'Collection Accounts', 'Invoices', 'Payment Methods', 'Banks', 'Bank Accounts', 'Payment Destinations'],
            array_column($commercial['children'], 'name'),
        );
        $this->assertSame(
            'route:quotations.index',
            collect($commercial['children'])->firstWhere('name', 'Quotations')['url'],
        );

        $compliance = collect($menus)->firstWhere('name', 'Compliance');

        $this->assertSame(
            ['Compliance Matrices', 'Compliance Requirements', 'Uploaded Documents', 'Validation Results'],
            array_column($compliance['children'], 'name'),
        );

        $availableIcons = $this->availableFluxIcons();

        foreach ($flattenedMenus as $menu) {
            $this->assertContains($menu['icon'], $availableIcons);
            $this->assertTrue($menu['url'] === '#' || str($menu['url'])->startsWith('route:'));
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

    public function test_menu_seeder_synchronizes_existing_records_and_removes_stale_entries(): void
    {
        $commercial = Menu::query()->create([
            'name' => 'Commercial',
            'icon' => 'home',
            'url' => '/legacy',
            'current' => 'legacy.*',
            'priority' => 999,
        ]);

        $staleChild = Menu::query()->create([
            'menu_id' => $commercial->id,
            'name' => 'Legacy Child',
            'icon' => 'home',
            'url' => '/legacy-child',
            'current' => 'legacy-child.*',
            'priority' => 999,
        ]);

        $staleRoot = Menu::query()->create([
            'name' => 'Legacy Root',
            'icon' => 'home',
            'url' => '/legacy-root',
            'current' => 'legacy-root.*',
            'priority' => 999,
        ]);

        $this->seed(MenuSeeder::class);

        $commercial->refresh();

        $this->assertSame('briefcase', $commercial->icon);
        $this->assertSame('#', $commercial->url);
        $this->assertSame('commercial.*', $commercial->current);
        $this->assertSame(20, $commercial->priority);
        $this->assertDatabaseHas('menus', [
            'menu_id' => $commercial->id,
            'name' => 'Quotations',
            'url' => 'route:quotations.index',
            'current' => 'quotations.*',
        ]);
        $this->assertDatabaseMissing('menus', ['id' => $staleChild->id]);
        $this->assertDatabaseMissing('menus', ['id' => $staleRoot->id]);
        $this->assertSame(50, Menu::query()->count());
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

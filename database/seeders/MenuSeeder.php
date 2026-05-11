<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->menus() as $menu) {
            $this->upsertMenu($menu);
        }
    }

    /**
     * @param  array{name: string, icon: string, url: string, current?: string|null, priority: int, children?: array<int, array{name: string, icon: string, url: string, current?: string|null, priority: int}>}  $menu
     */
    private function upsertMenu(array $menu, ?Menu $parent = null): Menu
    {
        $children = $menu['children'] ?? [];
        unset($menu['children']);

        $menu['menu_id'] = $parent?->id;

        $storedMenu = Menu::query()
            ->where('menu_id', $menu['menu_id'])
            ->whereIn('name', array_unique([$menu['name'], __($menu['name'])]))
            ->first();

        if ($storedMenu === null) {
            $storedMenu = Menu::query()->create($menu);
        } else {
            $storedMenu->fill($menu);
            $storedMenu->save();
        }

        foreach ($children as $child) {
            $this->upsertMenu($child, $storedMenu);
        }

        return $storedMenu;
    }

    /**
     * @return array<int, array{name: string, icon: string, url: string, current?: string|null, priority: int, children?: array<int, array{name: string, icon: string, url: string, current?: string|null, priority: int}>}>
     */
    private function menus(): array
    {
        return [
            [
                'name' => 'Dashboard',
                'icon' => 'home',
                'url' => route('dashboard'),
                'current' => 'dashboard',
                'priority' => 10,
            ],
            [
                'name' => 'Commercial',
                'icon' => 'briefcase',
                'url' => '#',
                'current' => 'commercial.*',
                'priority' => 20,
                'children' => [
                    [
                        'name' => 'Quotations',
                        'icon' => 'document-text',
                        'url' => '#',
                        'current' => 'quotations.*',
                        'priority' => 21,
                    ],
                    [
                        'name' => 'Proposals',
                        'icon' => 'clipboard-document-list',
                        'url' => '#',
                        'current' => 'proposals.*',
                        'priority' => 22,
                    ],
                    [
                        'name' => 'Collection Accounts',
                        'icon' => 'receipt-percent',
                        'url' => '#',
                        'current' => 'collection-accounts.*',
                        'priority' => 23,
                    ],
                    [
                        'name' => 'Invoices',
                        'icon' => 'document-currency-dollar',
                        'url' => '#',
                        'current' => 'invoices.*',
                        'priority' => 24,
                    ],
                    [
                        'name' => 'Payments',
                        'icon' => 'credit-card',
                        'url' => '#',
                        'current' => 'payments.*',
                        'priority' => 25,
                    ],
                ],
            ],
            [
                'name' => 'Customers',
                'icon' => 'users',
                'url' => '#',
                'current' => 'customers.*',
                'priority' => 30,
                'children' => [
                    [
                        'name' => 'Enterprises',
                        'icon' => 'building-office',
                        'url' => route('enterprises.index'),
                        'current' => 'enterprises.*',
                        'priority' => 31,
                    ],
                    [
                        'name' => 'People',
                        'icon' => 'user',
                        'url' => '#',
                        'current' => 'people.*',
                        'priority' => 32,
                    ],
                    [
                        'name' => 'Contacts',
                        'icon' => 'identification',
                        'url' => '#',
                        'current' => 'contacts.*',
                        'priority' => 33,
                    ],
                ],
            ],
            [
                'name' => 'Catalog',
                'icon' => 'squares-2x2',
                'url' => '#',
                'current' => 'catalog.*',
                'priority' => 40,
                'children' => [
                    [
                        'name' => 'Services',
                        'icon' => 'wrench-screwdriver',
                        'url' => '#',
                        'current' => 'services.*',
                        'priority' => 41,
                    ],
                    [
                        'name' => 'Plans',
                        'icon' => 'rectangle-stack',
                        'url' => '#',
                        'current' => 'plans.*',
                        'priority' => 42,
                    ],
                    [
                        'name' => 'Taxes',
                        'icon' => 'receipt-percent',
                        'url' => '#',
                        'current' => 'taxes.*',
                        'priority' => 43,
                    ],
                ],
            ],
            [
                'name' => 'Payments Setup',
                'icon' => 'banknotes',
                'url' => '#',
                'current' => 'payments-setup.*',
                'priority' => 50,
                'children' => [
                    [
                        'name' => 'Payment Methods',
                        'icon' => 'credit-card',
                        'url' => '#',
                        'current' => 'payment-methods.*',
                        'priority' => 51,
                    ],
                    [
                        'name' => 'Banks',
                        'icon' => 'building-library',
                        'url' => '#',
                        'current' => 'banks.*',
                        'priority' => 52,
                    ],
                    [
                        'name' => 'Bank Accounts',
                        'icon' => 'wallet',
                        'url' => '#',
                        'current' => 'bank-accounts.*',
                        'priority' => 53,
                    ],
                    [
                        'name' => 'Payment Destinations',
                        'icon' => 'map-pin',
                        'url' => '#',
                        'current' => 'payment-destinations.*',
                        'priority' => 54,
                    ],
                ],
            ],
            [
                'name' => 'Documents',
                'icon' => 'document-duplicate',
                'url' => '#',
                'current' => 'documents.*',
                'priority' => 60,
                'children' => [
                    [
                        'name' => 'Templates',
                        'icon' => 'document-duplicate',
                        'url' => '#',
                        'current' => 'document-templates.*',
                        'priority' => 61,
                    ],
                    [
                        'name' => 'Template Versions',
                        'icon' => 'clock',
                        'url' => '#',
                        'current' => 'document-template-versions.*',
                        'priority' => 62,
                    ],
                    [
                        'name' => 'Collection Account Attachments',
                        'icon' => 'paper-clip',
                        'url' => '#',
                        'current' => 'collection-account-attachments.*',
                        'priority' => 63,
                    ],
                    [
                        'name' => 'Invoice Attachments',
                        'icon' => 'paper-clip',
                        'url' => '#',
                        'current' => 'invoice-attachments.*',
                        'priority' => 64,
                    ],
                ],
            ],
            [
                'name' => 'Settings',
                'icon' => 'cog-6-tooth',
                'url' => '#',
                'current' => 'settings.*',
                'priority' => 90,
                'children' => [
                    [
                        'name' => 'Document Types',
                        'icon' => 'identification',
                        'url' => '#',
                        'current' => 'document-types.*',
                        'priority' => 91,
                    ],
                    [
                        'name' => 'Document Classes',
                        'icon' => 'folder',
                        'url' => '#',
                        'current' => 'document-classes.*',
                        'priority' => 92,
                    ],
                    [
                        'name' => 'Document Statuses',
                        'icon' => 'flag',
                        'url' => '#',
                        'current' => 'document-statuses.*',
                        'priority' => 93,
                    ],
                    [
                        'name' => 'Currencies',
                        'icon' => 'currency-dollar',
                        'url' => '#',
                        'current' => 'currencies.*',
                        'priority' => 94,
                    ],
                    [
                        'name' => 'Teams',
                        'icon' => 'user-group',
                        'url' => '#',
                        'current' => 'teams.*',
                        'priority' => 95,
                    ],
                    [
                        'name' => 'Menus',
                        'icon' => 'bars-3',
                        'url' => '#',
                        'current' => 'menus.*',
                        'priority' => 96,
                    ],
                ],
            ],
        ];
    }
}

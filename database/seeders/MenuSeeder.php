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
        $rootMenuIds = [];

        foreach ($this->menus() as $menu) {
            $rootMenuIds[] = $this->upsertMenu($menu)->id;
        }

        $this->deleteMissingMenus(null, $rootMenuIds);
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

        $childMenuIds = [];

        foreach ($children as $child) {
            $childMenuIds[] = $this->upsertMenu($child, $storedMenu)->id;
        }

        $this->deleteMissingMenus($storedMenu, $childMenuIds);

        return $storedMenu;
    }

    /**
     * @param  array<int, int>  $keptMenuIds
     */
    private function deleteMissingMenus(?Menu $parent, array $keptMenuIds): void
    {
        $existingMenus = Menu::query()
            ->when(
                $parent instanceof Menu,
                fn ($query) => $query->where('menu_id', $parent->id),
                fn ($query) => $query->whereNull('menu_id'),
            )
            ->get();

        foreach ($existingMenus as $existingMenu) {
            if (in_array($existingMenu->id, $keptMenuIds, true)) {
                continue;
            }

            $this->deleteMenuBranch($existingMenu);
        }
    }

    private function deleteMenuBranch(Menu $menu): void
    {
        foreach ($menu->children()->get() as $child) {
            $this->deleteMenuBranch($child);
        }

        $menu->delete();
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
                'url' => '#',
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
                        'url' => 'route:quotations.index',
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
                        'url' => '#',
                        'current' => 'enterprises.*',
                        'priority' => 31,
                    ],
                    [
                        'name' => 'Parties',
                        'icon' => 'identification',
                        'url' => '#',
                        'current' => 'parties.*',
                        'priority' => 32,
                    ],
                    [
                        'name' => 'Contacts',
                        'icon' => 'chat-bubble-left-right',
                        'url' => '#',
                        'current' => 'contacts.*',
                        'priority' => 33,
                    ],
                    [
                        'name' => 'People',
                        'icon' => 'user',
                        'url' => '#',
                        'current' => 'people.*',
                        'priority' => 34,
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
                        'name' => 'Service Rates',
                        'icon' => 'currency-dollar',
                        'url' => '#',
                        'current' => 'service-rates.*',
                        'priority' => 43,
                    ],
                    [
                        'name' => 'Taxes',
                        'icon' => 'receipt-percent',
                        'url' => '#',
                        'current' => 'taxes.*',
                        'priority' => 44,
                    ],
                    [
                        'name' => 'Retention Rates',
                        'icon' => 'scale',
                        'url' => '#',
                        'current' => 'retention-rates.*',
                        'priority' => 45,
                    ],
                ],
            ],
            [
                'name' => 'Compliance',
                'icon' => 'shield-check',
                'url' => '#',
                'current' => 'compliance.*',
                'priority' => 50,
                'children' => [
                    [
                        'name' => 'Compliance Matrices',
                        'icon' => 'table-cells',
                        'url' => '#',
                        'current' => 'compliance-matrices.*',
                        'priority' => 51,
                    ],
                    [
                        'name' => 'Compliance Requirements',
                        'icon' => 'clipboard-document-check',
                        'url' => '#',
                        'current' => 'compliance-requirements.*',
                        'priority' => 52,
                    ],
                    [
                        'name' => 'Uploaded Documents',
                        'icon' => 'document-arrow-up',
                        'url' => '#',
                        'current' => 'uploaded-documents.*',
                        'priority' => 53,
                    ],
                    [
                        'name' => 'Validation Results',
                        'icon' => 'check-badge',
                        'url' => '#',
                        'current' => 'compliance-validation-results.*',
                        'priority' => 54,
                    ],
                ],
            ],
            [
                'name' => 'Payments Setup',
                'icon' => 'banknotes',
                'url' => '#',
                'current' => 'payments-setup.*',
                'priority' => 60,
                'children' => [
                    [
                        'name' => 'Payment Methods',
                        'icon' => 'credit-card',
                        'url' => '#',
                        'current' => 'payment-methods.*',
                        'priority' => 61,
                    ],
                    [
                        'name' => 'Banks',
                        'icon' => 'building-library',
                        'url' => '#',
                        'current' => 'banks.*',
                        'priority' => 62,
                    ],
                    [
                        'name' => 'Bank Accounts',
                        'icon' => 'wallet',
                        'url' => '#',
                        'current' => 'bank-accounts.*',
                        'priority' => 63,
                    ],
                    [
                        'name' => 'Payment Destinations',
                        'icon' => 'map-pin',
                        'url' => '#',
                        'current' => 'payment-destinations.*',
                        'priority' => 64,
                    ],
                ],
            ],
            [
                'name' => 'Documents',
                'icon' => 'document-duplicate',
                'url' => '#',
                'current' => 'documents.*',
                'priority' => 70,
                'children' => [
                    [
                        'name' => 'Templates',
                        'icon' => 'document-duplicate',
                        'url' => '#',
                        'current' => 'document-templates.*',
                        'priority' => 71,
                    ],
                    [
                        'name' => 'Template Versions',
                        'icon' => 'clock',
                        'url' => '#',
                        'current' => 'document-template-versions.*',
                        'priority' => 72,
                    ],
                    [
                        'name' => 'Generated Documents',
                        'icon' => 'document-check',
                        'url' => '#',
                        'current' => 'generated-documents.*',
                        'priority' => 73,
                    ],
                    [
                        'name' => 'Document Packages',
                        'icon' => 'archive-box',
                        'url' => '#',
                        'current' => 'document-packages.*',
                        'priority' => 74,
                    ],
                    [
                        'name' => 'Collection Account Attachments',
                        'icon' => 'paper-clip',
                        'url' => '#',
                        'current' => 'collection-account-attachments.*',
                        'priority' => 75,
                    ],
                    [
                        'name' => 'Invoice Attachments',
                        'icon' => 'paper-clip',
                        'url' => '#',
                        'current' => 'invoice-attachments.*',
                        'priority' => 76,
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
                        'name' => 'Locations',
                        'icon' => 'globe-alt',
                        'url' => '#',
                        'current' => 'locations.*',
                        'priority' => 95,
                    ],
                    [
                        'name' => 'Internal Sequences',
                        'icon' => 'hashtag',
                        'url' => '#',
                        'current' => 'internal-sequences.*',
                        'priority' => 96,
                    ],
                    [
                        'name' => 'External Invoice Numbers',
                        'icon' => 'queue-list',
                        'url' => '#',
                        'current' => 'external-invoice-numbers.*',
                        'priority' => 97,
                    ],
                    [
                        'name' => 'Teams',
                        'icon' => 'user-group',
                        'url' => '#',
                        'current' => 'teams.*',
                        'priority' => 98,
                    ],
                    [
                        'name' => 'Activity Logs',
                        'icon' => 'clipboard-document-list',
                        'url' => '#',
                        'current' => 'activity-logs.*',
                        'priority' => 99,
                    ],
                    [
                        'name' => 'Audit Logs',
                        'icon' => 'archive-box',
                        'url' => '#',
                        'current' => 'audit-logs.*',
                        'priority' => 100,
                    ],
                    [
                        'name' => 'Menus',
                        'icon' => 'bars-3',
                        'url' => '#',
                        'current' => 'menus.*',
                        'priority' => 101,
                    ],
                ],
            ],
        ];
    }
}

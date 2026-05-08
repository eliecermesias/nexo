# Nexo Menu Proposal

## Objective

This proposal defines the recommended navigation structure for the Nexo system based on the definitive Laravel 13 schema in `context/nexo_laravel13_definitive_schema.sql`.

The menu is organized by business workflow, not by database table, so developers can build modules that match how users will operate the system.

## Navigation Principles

- Keep `Dashboard` as the first menu item.
- Group operational modules before configuration modules.
- Keep commercial documents in process order: quotations, proposals, collection accounts, invoices, payments.
- Keep catalog and configuration separated from daily operations.
- Use Laravel route names consistently.
- Use the existing `menus` table structure: `menu_id`, `name`, `icon`, `url`, `current`, `priority`.
- Use parent menu records with `url = '#'` or a module landing route when a section contains children.

## Menu Tree

```text
Nexo
├── Dashboard
├── Commercial
│   ├── Quotations
│   ├── Proposals
│   ├── Collection Accounts
│   ├── Invoices
│   └── Payments
├── Customers
│   ├── Enterprises
│   ├── People
│   └── Contacts
├── Catalog
│   ├── Services
│   ├── Plans
│   └── Taxes
├── Payments Setup
│   ├── Payment Methods
│   ├── Banks
│   ├── Bank Accounts
│   └── Payment Destinations
├── Documents
│   ├── Templates
│   ├── Template Versions
│   ├── Collection Account Attachments
│   └── Invoice Attachments
└── Settings
    ├── Document Types
    ├── Document Classes
    ├── Document Statuses
    ├── Currencies
    ├── Teams
    └── Menus
```

## Recommended Menu Tree

| Priority | Parent | Name | Icon | Suggested Route | Purpose |
| --- | --- | --- | --- | --- | --- |
| 10 |  | Dashboard | home | `dashboard` | Main overview and shortcuts. |
| 20 |  | Commercial | briefcase | `commercial.index` | Parent section for the sales and billing flow. |
| 21 | Commercial | Quotations | document-text | `quotations.index` | Manage price quotes. |
| 22 | Commercial | Proposals | clipboard-document-list | `proposals.index` | Manage commercial proposals. |
| 23 | Commercial | Collection Accounts | receipt-percent | `collection-accounts.index` | Manage accounts receivable documents. |
| 24 | Commercial | Invoices | document-currency-dollar | `invoices.index` | Manage invoices. |
| 25 | Commercial | Payments | credit-card | `payments.index` | Register and consult payments. |
| 30 |  | Customers | users | `customers.index` | Customer and contact management. |
| 31 | Customers | Enterprises | building-office | `enterprises.index` | Manage companies, customers, suppliers, and issuers. |
| 32 | Customers | People | user | `people.index` | Manage people records. |
| 33 | Customers | Contacts | identification | `contacts.index` | Manage enterprise contacts. |
| 40 |  | Catalog | squares-2x2 | `catalog.index` | Parent section for sellable products and pricing. |
| 41 | Catalog | Services | wrench-screwdriver | `services.index` | Manage billable services. |
| 42 | Catalog | Plans | rectangle-stack | `plans.index` | Manage commercial plans. |
| 43 | Catalog | Taxes | receipt-tax | `taxes.index` | Manage taxes and rates. |
| 50 |  | Payments Setup | banknotes | `payments-setup.index` | Parent section for payment configuration. |
| 51 | Payments Setup | Payment Methods | credit-card | `payment-methods.index` | Configure cash, check, transfer, and other methods. |
| 52 | Payments Setup | Banks | building-library | `banks.index` | Manage banks. |
| 53 | Payments Setup | Bank Accounts | wallet | `bank-accounts.index` | Manage enterprise bank accounts. |
| 54 | Payments Setup | Payment Destinations | map-pin | `payment-destinations.index` | Define where customers must pay. |
| 60 |  | Documents | document-duplicate | `documents.index` | Parent section for document files and templates. |
| 61 | Documents | Templates | document-duplicate | `document-templates.index` | Manage document templates. |
| 62 | Documents | Template Versions | clock | `document-template-versions.index` | Manage template version history. |
| 63 | Documents | Collection Account Attachments | paper-clip | `collection-account-attachments.index` | Consult account attachments. |
| 64 | Documents | Invoice Attachments | paper-clip | `invoice-attachments.index` | Consult invoice attachments. |
| 90 |  | Settings | cog-6-tooth | `settings.index` | Parent section for system configuration. |
| 91 | Settings | Document Types | identification | `document-types.index` | Manage ID document types. |
| 92 | Settings | Document Classes | folder | `document-classes.index` | Manage document class catalog. |
| 93 | Settings | Document Statuses | flag | `document-statuses.index` | Manage document lifecycle statuses. |
| 94 | Settings | Currencies | currency-dollar | `currencies.index` | Manage currencies. |
| 95 | Settings | Teams | user-group | `teams.index` | Manage teams and memberships. |
| 96 | Settings | Menus | bars-3 | `menus.index` | Manage navigation records. |

## First Implementation Phase

Build these modules first because they unblock the commercial workflow:

1. Dashboard
2. Enterprises
3. People
4. Contacts
5. Services
6. Plans
7. Taxes
8. Quotations
9. Proposals
10. Collection Accounts
11. Invoices
12. Payments

Configuration modules can start with seeders and simple CRUD screens:

- Document Types
- Document Classes
- Document Statuses
- Currencies
- Payment Methods
- Banks

## Suggested Route Names

Use resource-style route names so controllers, policies, breadcrumbs, and menu activation stay predictable.

| Module | Route Prefix | Example Routes |
| --- | --- | --- |
| Enterprises | `enterprises` | `enterprises.index`, `enterprises.create`, `enterprises.edit` |
| People | `people` | `people.index`, `people.create`, `people.edit` |
| Contacts | `contacts` | `contacts.index`, `contacts.create`, `contacts.edit` |
| Services | `services` | `services.index`, `services.create`, `services.edit` |
| Plans | `plans` | `plans.index`, `plans.create`, `plans.edit` |
| Taxes | `taxes` | `taxes.index`, `taxes.create`, `taxes.edit` |
| Quotations | `quotations` | `quotations.index`, `quotations.create`, `quotations.show`, `quotations.edit` |
| Proposals | `proposals` | `proposals.index`, `proposals.create`, `proposals.show`, `proposals.edit` |
| Collection Accounts | `collection-accounts` | `collection-accounts.index`, `collection-accounts.create`, `collection-accounts.show`, `collection-accounts.edit` |
| Invoices | `invoices` | `invoices.index`, `invoices.create`, `invoices.show`, `invoices.edit` |
| Payments | `payments` | `payments.index`, `payments.create`, `payments.show` |
| Document Templates | `document-templates` | `document-templates.index`, `document-templates.create`, `document-templates.edit` |
| Payment Methods | `payment-methods` | `payment-methods.index`, `payment-methods.create`, `payment-methods.edit` |
| Payment Destinations | `payment-destinations` | `payment-destinations.index`, `payment-destinations.create`, `payment-destinations.edit` |

## Suggested Permission Keys

Use permissions that match modules and actions. This will make policies and menu visibility easier to implement later.

| Menu | View | Create | Update | Delete | Special Actions |
| --- | --- | --- | --- | --- | --- |
| Enterprises | `enterprises.view` | `enterprises.create` | `enterprises.update` | `enterprises.delete` |  |
| People | `people.view` | `people.create` | `people.update` | `people.delete` |  |
| Contacts | `contacts.view` | `contacts.create` | `contacts.update` | `contacts.delete` |  |
| Services | `services.view` | `services.create` | `services.update` | `services.delete` |  |
| Plans | `plans.view` | `plans.create` | `plans.update` | `plans.delete` |  |
| Quotations | `quotations.view` | `quotations.create` | `quotations.update` | `quotations.delete` | `quotations.send`, `quotations.convert` |
| Proposals | `proposals.view` | `proposals.create` | `proposals.update` | `proposals.delete` | `proposals.approve`, `proposals.convert` |
| Collection Accounts | `collection_accounts.view` | `collection_accounts.create` | `collection_accounts.update` | `collection_accounts.delete` | `collection_accounts.attach`, `collection_accounts.pay` |
| Invoices | `invoices.view` | `invoices.create` | `invoices.update` | `invoices.delete` | `invoices.attach`, `invoices.pay`, `invoices.cancel` |
| Payments | `payments.view` | `payments.create` | `payments.update` | `payments.delete` | `payments.reverse` |
| Document Templates | `document_templates.view` | `document_templates.create` | `document_templates.update` | `document_templates.delete` | `document_templates.publish` |
| Settings | `settings.view` |  |  |  |  |

## Suggested Seeder Shape

The current `menus` table can represent the full tree with parent-child records.

Example structure:

```php
[
    [
        'name' => 'Commercial',
        'icon' => 'briefcase',
        'url' => '#',
        'priority' => 20,
        'children' => [
            [
                'name' => 'Quotations',
                'icon' => 'document-text',
                'url' => route('quotations.index'),
                'priority' => 21,
            ],
            [
                'name' => 'Proposals',
                'icon' => 'clipboard-document-list',
                'url' => route('proposals.index'),
                'priority' => 22,
            ],
        ],
    ],
]
```

## Menu Activation Rules

Recommended `current` values:

| Section | Current Value | Matching Route Prefixes |
| --- | --- | --- |
| Dashboard | `dashboard` | `dashboard` |
| Commercial | `commercial` | `quotations.*`, `proposals.*`, `collection-accounts.*`, `invoices.*`, `payments.*` |
| Customers | `customers` | `enterprises.*`, `people.*`, `contacts.*` |
| Catalog | `catalog` | `services.*`, `plans.*`, `taxes.*` |
| Payments Setup | `payments_setup` | `payment-methods.*`, `banks.*`, `bank-accounts.*`, `payment-destinations.*` |
| Documents | `documents` | `document-templates.*`, `document-template-versions.*`, `collection-account-attachments.*`, `invoice-attachments.*` |
| Settings | `settings` | `document-types.*`, `document-classes.*`, `document-statuses.*`, `currencies.*`, `teams.*`, `menus.*` |

## Notes For Developers

- Keep operational screens focused on workflow actions, not just CRUD.
- `Commercial` should show pending documents, expiration dates, unpaid balances, and recent activity.
- `Customers` should combine companies, people, and contacts because the schema keeps these as separate but related concepts.
- `Payment Destinations` should expose different fields depending on the selected payment method.
- Attachment menus can be read-only indexes at first; uploads should primarily happen from collection account and invoice detail screens.
- Settings menus should be restricted to administrator roles.

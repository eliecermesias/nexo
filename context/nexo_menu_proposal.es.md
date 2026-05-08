# Propuesta de Menú de Nexo

## Objetivo

Este documento define la estructura de navegación recomendada para el sistema Nexo, basada en el esquema definitivo Laravel 13 definido en `context/nexo_laravel13_definitive_schema.sql`.

El menú está organizado por flujo de negocio, no por tabla de base de datos, para que los desarrolladores construyan módulos alineados con la forma en que los usuarios operarán el sistema.

## Principios de Navegación

- Mantener `Dashboard` como primera opción del menú.
- Agrupar los módulos operativos antes de los módulos de configuración.
- Mantener los documentos comerciales en orden de proceso: `Quotations`, `Proposals`, `Collection Accounts`, `Invoices`, `Payments`.
- Separar `Catalog` y `Settings` de la operación diaria.
- Usar nombres de rutas Laravel de forma consistente.
- Usar la estructura existente de la tabla `menus`: `menu_id`, `name`, `icon`, `url`, `current`, `priority`.
- Usar registros padre con `url = '#'` o una ruta de inicio del módulo cuando una sección tenga submenús.

## Árbol de Menú

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

## Árbol de Menú Recomendado

| Prioridad | Padre | Nombre | Icono | Ruta Sugerida | Propósito |
| --- | --- | --- | --- | --- | --- |
| 10 |  | Dashboard | home | `dashboard` | Vista principal, indicadores y accesos rápidos. |
| 20 |  | Commercial | briefcase | `commercial.index` | Sección padre del flujo comercial y de facturación. |
| 21 | Commercial | Quotations | document-text | `quotations.index` | Gestionar cotizaciones. |
| 22 | Commercial | Proposals | clipboard-document-list | `proposals.index` | Gestionar propuestas comerciales. |
| 23 | Commercial | Collection Accounts | receipt-percent | `collection-accounts.index` | Gestionar cuentas de cobro. |
| 24 | Commercial | Invoices | document-currency-dollar | `invoices.index` | Gestionar facturas. |
| 25 | Commercial | Payments | credit-card | `payments.index` | Registrar y consultar pagos. |
| 30 |  | Customers | users | `customers.index` | Gestión de clientes, proveedores y contactos. |
| 31 | Customers | Enterprises | building-office | `enterprises.index` | Gestionar empresas, clientes, proveedores y emisores. |
| 32 | Customers | People | user | `people.index` | Gestionar personas. |
| 33 | Customers | Contacts | identification | `contacts.index` | Gestionar contactos asociados a empresas. |
| 40 |  | Catalog | squares-2x2 | `catalog.index` | Sección padre para servicios, planes e impuestos. |
| 41 | Catalog | Services | wrench-screwdriver | `services.index` | Gestionar servicios facturables. |
| 42 | Catalog | Plans | rectangle-stack | `plans.index` | Gestionar planes comerciales. |
| 43 | Catalog | Taxes | receipt-tax | `taxes.index` | Gestionar impuestos y tarifas. |
| 50 |  | Payments Setup | banknotes | `payments-setup.index` | Configuración de medios y destinos de pago. |
| 51 | Payments Setup | Payment Methods | credit-card | `payment-methods.index` | Configurar efectivo, cheque, transferencia y otros métodos. |
| 52 | Payments Setup | Banks | building-library | `banks.index` | Gestionar bancos. |
| 53 | Payments Setup | Bank Accounts | wallet | `bank-accounts.index` | Gestionar cuentas bancarias de la empresa. |
| 54 | Payments Setup | Payment Destinations | map-pin | `payment-destinations.index` | Definir dónde deben pagar los clientes. |
| 60 |  | Documents | document-duplicate | `documents.index` | Sección padre para plantillas y archivos adjuntos. |
| 61 | Documents | Templates | document-duplicate | `document-templates.index` | Gestionar modelos de documentos. |
| 62 | Documents | Template Versions | clock | `document-template-versions.index` | Gestionar historial de versiones de plantillas. |
| 63 | Documents | Collection Account Attachments | paper-clip | `collection-account-attachments.index` | Consultar adjuntos de cuentas de cobro. |
| 64 | Documents | Invoice Attachments | paper-clip | `invoice-attachments.index` | Consultar adjuntos de facturas. |
| 90 |  | Settings | cog-6-tooth | `settings.index` | Configuración general del sistema. |
| 91 | Settings | Document Types | identification | `document-types.index` | Gestionar tipos de documento de identificación. |
| 92 | Settings | Document Classes | folder | `document-classes.index` | Gestionar catálogo de clases de documento. |
| 93 | Settings | Document Statuses | flag | `document-statuses.index` | Gestionar estados del ciclo de vida documental. |
| 94 | Settings | Currencies | currency-dollar | `currencies.index` | Gestionar monedas. |
| 95 | Settings | Teams | user-group | `teams.index` | Gestionar equipos y membresías. |
| 96 | Settings | Menus | bars-3 | Gestionar registros de navegación. |

## Primera Fase de Implementación

Construir primero estos módulos porque desbloquean el flujo comercial principal:

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

Los módulos de configuración pueden iniciar con seeders y pantallas CRUD simples:

- Document Types
- Document Classes
- Document Statuses
- Currencies
- Payment Methods
- Banks

## Nombres de Rutas Sugeridos

Usar rutas tipo resource para que controllers, policies, breadcrumbs y activación del menú sean predecibles.

| Módulo | Prefijo de Ruta | Rutas de Ejemplo |
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

## Permisos Sugeridos

Usar permisos alineados con módulos y acciones. Esto facilita policies y visibilidad de menú.

| Menú | Ver | Crear | Actualizar | Eliminar | Acciones Especiales |
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

## Estructura Sugerida del Seeder

La tabla actual `menus` puede representar el árbol completo usando registros padre e hijo.

Ejemplo:

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

## Reglas de Activación del Menú

Valores recomendados para `current`:

| Sección | Valor de `current` | Prefijos de Ruta |
| --- | --- | --- |
| Dashboard | `dashboard` | `dashboard` |
| Commercial | `commercial` | `quotations.*`, `proposals.*`, `collection-accounts.*`, `invoices.*`, `payments.*` |
| Customers | `customers` | `enterprises.*`, `people.*`, `contacts.*` |
| Catalog | `catalog` | `services.*`, `plans.*`, `taxes.*` |
| Payments Setup | `payments_setup` | `payment-methods.*`, `banks.*`, `bank-accounts.*`, `payment-destinations.*` |
| Documents | `documents` | `document-templates.*`, `document-template-versions.*`, `collection-account-attachments.*`, `invoice-attachments.*` |
| Settings | `settings` | `document-types.*`, `document-classes.*`, `document-statuses.*`, `currencies.*`, `teams.*`, `menus.*` |

## Notas Para Desarrolladores

- Mantener las pantallas operativas enfocadas en acciones de flujo, no solo CRUD.
- `Commercial` debe mostrar documentos pendientes, fechas de vencimiento, saldos por cobrar y actividad reciente.
- `Customers` debe combinar empresas, personas y contactos, ya que el esquema conserva estos conceptos separados pero relacionados.
- `Payment Destinations` debe mostrar campos diferentes según el `Payment Method` seleccionado.
- Los menús de adjuntos pueden iniciar como índices de solo lectura; la carga de archivos debe ocurrir principalmente desde el detalle de `Collection Accounts` e `Invoices`.
- Los menús de `Settings` deben limitarse a roles administradores.

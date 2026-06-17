<?php

namespace App\Support;

use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Currency;
use App\Models\Enterprise;
use App\Models\PaymentDestination;
use App\Models\PaymentMethod;
use App\Models\Plan;
use App\Models\RetentionRate;
use App\Models\Service;
use App\Models\ServiceRate;
use App\Models\Tax;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminResourceRegistry
{
    /**
     * @return array<string, mixed>
     */
    public static function get(string $key): array
    {
        $resources = static::resources();

        abort_unless(isset($resources[$key]), 404);

        return $resources[$key] + [
            'key' => $key,
            'per_page' => 12,
            'with' => [],
            'with_count' => [],
            'dependencies' => [],
            'form_columns' => 2,
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function resources(): array
    {
        return [
            'payment-methods' => [
                'model' => PaymentMethod::class,
                'route' => 'payment-methods',
                'title' => 'Métodos de pago',
                'singular' => 'método de pago',
                'eyebrow' => 'Tesorería',
                'description' => 'Configura las formas de recaudo disponibles para pagos y destinos.',
                'icon' => 'credit-card',
                'search' => ['code', 'name'],
                'stats' => ['active' => true, 'bank_required' => 'requires_bank_account'],
                'with_count' => ['paymentDestinations', 'payments'],
                'dependencies' => ['paymentDestinations', 'payments'],
                'fields' => [
                    ['name' => 'code', 'label' => 'Código', 'type' => 'text', 'table' => true, 'required' => true],
                    ['name' => 'name', 'label' => 'Nombre', 'type' => 'text', 'table' => true, 'required' => true],
                    ['name' => 'requires_bank_account', 'label' => 'Requiere cuenta bancaria', 'type' => 'boolean', 'table' => true],
                    ['name' => 'is_active', 'label' => 'Activo', 'type' => 'boolean', 'table' => true],
                ],
            ],
            'banks' => [
                'model' => Bank::class,
                'route' => 'banks',
                'title' => 'Bancos',
                'singular' => 'banco',
                'eyebrow' => 'Tesorería',
                'description' => 'Administra entidades financieras usadas por las cuentas bancarias.',
                'icon' => 'building-library',
                'search' => ['code', 'name', 'country'],
                'stats' => ['total' => true],
                'with_count' => ['bankAccounts'],
                'dependencies' => ['bankAccounts'],
                'fields' => [
                    ['name' => 'code', 'label' => 'Código', 'type' => 'text', 'table' => true],
                    ['name' => 'name', 'label' => 'Nombre', 'type' => 'text', 'table' => true, 'required' => true],
                    ['name' => 'country', 'label' => 'País', 'type' => 'text', 'table' => true],
                ],
            ],
            'bank-accounts' => [
                'model' => BankAccount::class,
                'route' => 'bank-accounts',
                'title' => 'Cuentas bancarias',
                'singular' => 'cuenta bancaria',
                'eyebrow' => 'Tesorería',
                'description' => 'Registra cuentas de recaudo por empresa, banco y moneda.',
                'icon' => 'wallet',
                'search' => ['account_number', 'account_holder', 'swift_code', 'routing_number'],
                'with' => ['enterprise', 'bank', 'currency'],
                'with_count' => ['paymentDestinations'],
                'dependencies' => ['paymentDestinations'],
                'fields' => [
                    ['name' => 'enterprises_Id', 'label' => 'Empresa', 'type' => 'select', 'options' => 'enterprises', 'table' => true, 'display' => 'enterprise.legal_name', 'required' => true],
                    ['name' => 'banks_Id', 'label' => 'Banco', 'type' => 'select', 'options' => 'banks', 'table' => true, 'display' => 'bank.name', 'required' => true],
                    ['name' => 'currencies_Id', 'label' => 'Moneda', 'type' => 'select', 'options' => 'currencies', 'table' => true, 'display' => 'currency.code', 'required' => true],
                    ['name' => 'account_type', 'label' => 'Tipo', 'type' => 'select', 'options' => 'account_types', 'table' => true, 'required' => true],
                    ['name' => 'account_number', 'label' => 'Número de cuenta', 'type' => 'text', 'table' => true, 'required' => true],
                    ['name' => 'account_holder', 'label' => 'Titular', 'type' => 'text', 'table' => true, 'required' => true],
                    ['name' => 'swift_code', 'label' => 'SWIFT', 'type' => 'text'],
                    ['name' => 'routing_number', 'label' => 'Routing', 'type' => 'text'],
                    ['name' => 'is_default', 'label' => 'Predeterminada', 'type' => 'boolean', 'table' => true],
                    ['name' => 'is_active', 'label' => 'Activa', 'type' => 'boolean', 'table' => true],
                ],
            ],
            'payment-destinations' => [
                'model' => PaymentDestination::class,
                'route' => 'payment-destinations',
                'title' => 'Destinos de pago',
                'singular' => 'destino de pago',
                'eyebrow' => 'Tesorería',
                'description' => 'Define instrucciones de pago por método, empresa y cuenta bancaria.',
                'icon' => 'map-pin',
                'search' => ['name', 'cash_location', 'check_payee_name', 'instruction'],
                'with' => ['enterprise', 'paymentMethod', 'bankAccount.bank'],
                'with_count' => ['payments'],
                'dependencies' => ['payments'],
                'fields' => [
                    ['name' => 'enterprises_Id', 'label' => 'Empresa', 'type' => 'select', 'options' => 'enterprises', 'table' => true, 'display' => 'enterprise.legal_name', 'required' => true],
                    ['name' => 'payment_methods_Id', 'label' => 'Método de pago', 'type' => 'select', 'options' => 'payment_methods', 'table' => true, 'display' => 'paymentMethod.name', 'required' => true],
                    ['name' => 'bank_accounts_Id', 'label' => 'Cuenta bancaria', 'type' => 'select', 'options' => 'bank_accounts', 'table' => true, 'display' => 'bankAccount.account_number'],
                    ['name' => 'name', 'label' => 'Nombre', 'type' => 'text', 'table' => true, 'required' => true],
                    ['name' => 'cash_location', 'label' => 'Ubicación efectivo', 'type' => 'text'],
                    ['name' => 'check_payee_name', 'label' => 'Beneficiario cheque', 'type' => 'text'],
                    ['name' => 'instruction', 'label' => 'Instrucciones', 'type' => 'textarea', 'span' => 2],
                    ['name' => 'is_default', 'label' => 'Predeterminado', 'type' => 'boolean', 'table' => true],
                    ['name' => 'is_active', 'label' => 'Activo', 'type' => 'boolean', 'table' => true],
                ],
            ],
            'services' => [
                'model' => Service::class,
                'route' => 'services',
                'title' => 'Servicios',
                'singular' => 'servicio',
                'eyebrow' => 'Catálogo',
                'description' => 'Administra servicios ofertables, precios base, categoría y unidad comercial.',
                'icon' => 'wrench-screwdriver',
                'search' => ['code', 'name', 'description', 'category'],
                'with' => ['currency'],
                'with_count' => ['planItems', 'serviceRates'],
                'dependencies' => ['planItems', 'serviceRates'],
                'fields' => [
                    ['name' => 'code', 'label' => 'Código', 'type' => 'text', 'table' => true, 'required' => true],
                    ['name' => 'name', 'label' => 'Nombre', 'type' => 'text', 'table' => true, 'required' => true],
                    ['name' => 'category', 'label' => 'Categoría', 'type' => 'text', 'table' => true],
                    ['name' => 'pricing_type', 'label' => 'Tipo de precio', 'type' => 'select', 'options' => 'pricing_types', 'table' => true],
                    ['name' => 'unit', 'label' => 'Unidad', 'type' => 'text', 'table' => true],
                    ['name' => 'unit_price', 'label' => 'Precio unitario', 'type' => 'number', 'step' => '0.01', 'table' => true, 'format' => 'money'],
                    ['name' => 'currency_id', 'label' => 'Moneda', 'type' => 'select', 'options' => 'currencies', 'display' => 'currency.code'],
                    ['name' => 'description', 'label' => 'Descripción', 'type' => 'textarea', 'span' => 2],
                    ['name' => 'is_active', 'label' => 'Activo', 'type' => 'boolean', 'table' => true],
                ],
            ],
            'plans' => [
                'model' => Plan::class,
                'route' => 'plans',
                'title' => 'Planes',
                'singular' => 'plan',
                'eyebrow' => 'Catálogo',
                'description' => 'Agrupa servicios en planes comerciales con periodicidad y precio.',
                'icon' => 'rectangle-stack',
                'search' => ['code', 'name', 'description'],
                'with_count' => ['items'],
                'dependencies' => ['items'],
                'fields' => [
                    ['name' => 'code', 'label' => 'Código', 'type' => 'text', 'table' => true, 'required' => true],
                    ['name' => 'name', 'label' => 'Nombre', 'type' => 'text', 'table' => true, 'required' => true],
                    ['name' => 'billing_period', 'label' => 'Periodicidad', 'type' => 'select', 'options' => 'billing_periods', 'table' => true],
                    ['name' => 'price', 'label' => 'Precio', 'type' => 'number', 'step' => '0.01', 'table' => true, 'format' => 'money'],
                    ['name' => 'description', 'label' => 'Descripción', 'type' => 'textarea', 'span' => 2],
                    ['name' => 'is_active', 'label' => 'Activo', 'type' => 'boolean', 'table' => true],
                ],
            ],
            'service-rates' => [
                'model' => ServiceRate::class,
                'route' => 'service-rates',
                'title' => 'Tarifas de servicios',
                'singular' => 'tarifa de servicio',
                'eyebrow' => 'Catálogo',
                'description' => 'Controla precios por servicio, moneda y vigencia.',
                'icon' => 'currency-dollar',
                'search' => ['pricing_type'],
                'with' => ['service', 'currency'],
                'fields' => [
                    ['name' => 'service_id', 'label' => 'Servicio', 'type' => 'select', 'options' => 'services', 'table' => true, 'display' => 'service.name', 'required' => true],
                    ['name' => 'currency_id', 'label' => 'Moneda', 'type' => 'select', 'options' => 'currencies', 'table' => true, 'display' => 'currency.code', 'required' => true],
                    ['name' => 'pricing_type', 'label' => 'Tipo de precio', 'type' => 'select', 'options' => 'pricing_types', 'table' => true],
                    ['name' => 'base_price', 'label' => 'Precio base', 'type' => 'number', 'step' => '0.01', 'table' => true, 'format' => 'money'],
                    ['name' => 'starts_on', 'label' => 'Inicio', 'type' => 'date', 'table' => true],
                    ['name' => 'ends_on', 'label' => 'Fin', 'type' => 'date', 'table' => true],
                    ['name' => 'is_active', 'label' => 'Activa', 'type' => 'boolean', 'table' => true],
                ],
            ],
            'taxes' => [
                'model' => Tax::class,
                'route' => 'taxes',
                'title' => 'Impuestos',
                'singular' => 'impuesto',
                'eyebrow' => 'Catálogo',
                'description' => 'Administra impuestos aplicables a líneas comerciales.',
                'icon' => 'receipt-percent',
                'search' => ['code', 'name'],
                'fields' => [
                    ['name' => 'code', 'label' => 'Código', 'type' => 'text', 'table' => true, 'required' => true],
                    ['name' => 'name', 'label' => 'Nombre', 'type' => 'text', 'table' => true, 'required' => true],
                    ['name' => 'rate', 'label' => 'Tarifa %', 'type' => 'number', 'step' => '0.0001', 'table' => true, 'format' => 'percent'],
                    ['name' => 'is_active', 'label' => 'Activo', 'type' => 'boolean', 'table' => true],
                ],
            ],
            'retention-rates' => [
                'model' => RetentionRate::class,
                'route' => 'retention-rates',
                'title' => 'Retenciones',
                'singular' => 'retención',
                'eyebrow' => 'Catálogo',
                'description' => 'Define tarifas de retención disponibles en documentos comerciales.',
                'icon' => 'scale',
                'search' => ['code', 'name'],
                'fields' => [
                    ['name' => 'code', 'label' => 'Código', 'type' => 'text', 'table' => true, 'required' => true],
                    ['name' => 'name', 'label' => 'Nombre', 'type' => 'text', 'table' => true, 'required' => true],
                    ['name' => 'rate', 'label' => 'Tarifa %', 'type' => 'number', 'step' => '0.0001', 'table' => true, 'format' => 'percent'],
                    ['name' => 'is_active', 'label' => 'Activa', 'type' => 'boolean', 'table' => true],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function rules(string $key, ?Model $record, Request $request): array
    {
        return match ($key) {
            'payment-methods' => [
                'code' => ['required', 'string', 'max:40', Rule::unique('payment_methods', 'code')->ignore($record?->getKey(), 'Id')],
                'name' => ['required', 'string', 'max:120'],
                'requires_bank_account' => ['nullable', 'boolean'],
                'is_active' => ['nullable', 'boolean'],
            ],
            'banks' => [
                'code' => ['nullable', 'string', 'max:40', Rule::unique('banks', 'code')->ignore($record?->getKey(), 'Id')],
                'name' => ['required', 'string', 'max:180'],
                'country' => ['nullable', 'string', 'max:120'],
            ],
            'bank-accounts' => [
                'enterprises_Id' => ['required', 'exists:enterprises,Id'],
                'banks_Id' => ['required', 'exists:banks,Id'],
                'currencies_Id' => ['required', 'exists:currencies,Id'],
                'account_type' => ['required', Rule::in(['checking', 'savings', 'current', 'other'])],
                'account_number' => [
                    'required',
                    'string',
                    'max:80',
                    Rule::unique('bank_accounts', 'account_number')
                        ->where(fn ($query) => $query->where('enterprises_Id', $request->input('enterprises_Id')))
                        ->ignore($record?->getKey(), 'Id'),
                ],
                'account_holder' => ['required', 'string', 'max:180'],
                'swift_code' => ['nullable', 'string', 'max:40'],
                'routing_number' => ['nullable', 'string', 'max:40'],
                'is_default' => ['nullable', 'boolean'],
                'is_active' => ['nullable', 'boolean'],
            ],
            'payment-destinations' => [
                'enterprises_Id' => ['required', 'exists:enterprises,Id'],
                'payment_methods_Id' => ['required', 'exists:payment_methods,Id'],
                'bank_accounts_Id' => ['nullable', 'exists:bank_accounts,Id'],
                'name' => ['required', 'string', 'max:180'],
                'cash_location' => ['nullable', 'string', 'max:180'],
                'check_payee_name' => ['nullable', 'string', 'max:180'],
                'instruction' => ['nullable', 'string'],
                'is_default' => ['nullable', 'boolean'],
                'is_active' => ['nullable', 'boolean'],
            ],
            'services' => [
                'code' => ['required', 'string', 'max:50', Rule::unique('services', 'code')->ignore($record?->getKey(), 'Id')],
                'name' => ['required', 'string', 'max:180'],
                'description' => ['nullable', 'string'],
                'category' => ['nullable', 'string', 'max:120'],
                'pricing_type' => ['required', Rule::in(['fixed', 'hourly', 'monthly', 'custom'])],
                'unit' => ['required', 'string', 'max:40'],
                'unit_price' => ['required', 'numeric', 'min:0'],
                'currency_id' => ['nullable', 'exists:currencies,Id'],
                'is_active' => ['nullable', 'boolean'],
            ],
            'plans' => [
                'code' => ['required', 'string', 'max:50', Rule::unique('plans', 'code')->ignore($record?->getKey(), 'Id')],
                'name' => ['required', 'string', 'max:180'],
                'description' => ['nullable', 'string'],
                'billing_period' => ['required', Rule::in(['one_time', 'monthly', 'quarterly', 'semiannual', 'annual'])],
                'price' => ['required', 'numeric', 'min:0'],
                'is_active' => ['nullable', 'boolean'],
            ],
            'service-rates' => [
                'service_id' => ['required', 'exists:services,Id'],
                'currency_id' => ['required', 'exists:currencies,Id'],
                'pricing_type' => ['required', Rule::in(['fixed', 'hourly', 'monthly', 'custom'])],
                'base_price' => ['required', 'numeric', 'min:0'],
                'starts_on' => ['nullable', 'date'],
                'ends_on' => ['nullable', 'date', 'after_or_equal:starts_on'],
                'is_active' => ['nullable', 'boolean'],
            ],
            'taxes' => [
                'code' => ['required', 'string', 'max:40', Rule::unique('taxes', 'code')->ignore($record?->getKey(), 'Id')],
                'name' => ['required', 'string', 'max:120'],
                'rate' => ['required', 'numeric', 'min:0', 'max:100'],
                'is_active' => ['nullable', 'boolean'],
            ],
            'retention-rates' => [
                'code' => [
                    'required',
                    'string',
                    'max:40',
                    Rule::unique('retention_rates', 'code')
                        ->where(fn ($query) => $query->where('team_id', $request->user()?->currentTeam?->id))
                        ->ignore($record?->getKey()),
                ],
                'name' => ['required', 'string', 'max:120'],
                'rate' => ['required', 'numeric', 'min:0', 'max:100'],
                'is_active' => ['nullable', 'boolean'],
            ],
            default => [],
        };
    }

    /**
     * @return array<int, array{value: string, label: string}>
     */
    public static function options(string $key): array
    {
        return match ($key) {
            'enterprises' => Enterprise::query()->orderBy('legal_name')->get(['Id', 'legal_name'])
                ->map(fn (Enterprise $enterprise): array => ['value' => (string) $enterprise->Id, 'label' => $enterprise->legal_name])
                ->all(),
            'banks' => Bank::query()->orderBy('name')->get(['Id', 'name'])
                ->map(fn (Bank $bank): array => ['value' => (string) $bank->Id, 'label' => $bank->name])
                ->all(),
            'currencies' => Currency::query()->orderBy('code')->get(['Id', 'code', 'name'])
                ->map(fn (Currency $currency): array => ['value' => (string) $currency->Id, 'label' => "{$currency->code} - {$currency->name}"])
                ->all(),
            'payment_methods' => PaymentMethod::query()->orderBy('name')->get(['Id', 'name'])
                ->map(fn (PaymentMethod $paymentMethod): array => ['value' => (string) $paymentMethod->Id, 'label' => $paymentMethod->name])
                ->all(),
            'bank_accounts' => BankAccount::query()->with(['bank', 'enterprise'])->orderBy('account_holder')->get()
                ->map(fn (BankAccount $bankAccount): array => [
                    'value' => (string) $bankAccount->Id,
                    'label' => trim("{$bankAccount->account_holder} - {$bankAccount->bank?->name} {$bankAccount->account_number}"),
                ])
                ->all(),
            'services' => Service::query()->orderBy('name')->get(['Id', 'name', 'code'])
                ->map(fn (Service $service): array => ['value' => (string) $service->Id, 'label' => "{$service->code} - {$service->name}"])
                ->all(),
            'account_types' => [
                ['value' => 'checking', 'label' => 'Corriente'],
                ['value' => 'savings', 'label' => 'Ahorros'],
                ['value' => 'current', 'label' => 'Current'],
                ['value' => 'other', 'label' => 'Otra'],
            ],
            'pricing_types' => [
                ['value' => 'fixed', 'label' => 'Fijo'],
                ['value' => 'hourly', 'label' => 'Por hora'],
                ['value' => 'monthly', 'label' => 'Mensual'],
                ['value' => 'custom', 'label' => 'Personalizado'],
            ],
            'billing_periods' => [
                ['value' => 'one_time', 'label' => 'Único'],
                ['value' => 'monthly', 'label' => 'Mensual'],
                ['value' => 'quarterly', 'label' => 'Trimestral'],
                ['value' => 'semiannual', 'label' => 'Semestral'],
                ['value' => 'annual', 'label' => 'Anual'],
            ],
            default => [],
        };
    }
}

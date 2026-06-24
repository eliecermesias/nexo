<?php

namespace Tests\Feature\AdminResources;

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
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminResourceCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_indexes_render_with_the_dashboard_style(): void
    {
        $this->seedRecords();

        $user = User::factory()->create();

        foreach ([
            'payment-methods.index' => 'Métodos de pago',
            'banks.index' => 'Bancos',
            'bank-accounts.index' => 'Cuentas bancarias',
            'payment-destinations.index' => 'Destinos de pago',
            'plans.index' => 'Planes',
            'service-rates.index' => 'Tarifas de servicios',
            'taxes.index' => 'Impuestos',
            'retention-rates.index' => 'Retenciones',
        ] as $route => $title) {
            $this
                ->actingAs($user)
                ->get(route($route))
                ->assertOk()
                ->assertSee($title)
                ->assertSee('Listado')
                ->assertSee('Nuevo');
        }
    }

    public function test_show_route_opens_the_summary_modal_on_the_index(): void
    {
        $bank = Bank::factory()->create([
            'name' => 'Banco Modal',
            'code' => 'MODAL',
        ]);

        $this
            ->actingAs(User::factory()->create())
            ->followingRedirects()
            ->get(route('banks.show', $bank->getKey()))
            ->assertOk()
            ->assertSee('Resumen')
            ->assertSee('Banco Modal')
            ->assertSee('MODAL')
            ->assertSee('Cerrar');
    }

    public function test_users_can_create_update_and_delete_a_simple_resource(): void
    {
        $user = User::factory()->create();

        $createResponse = $this
            ->actingAs($user)
            ->post(route('banks.store'), [
                'code' => 'TESTBANK',
                'name' => 'Banco de Prueba',
                'country' => 'Colombia',
            ]);

        $bank = Bank::query()->where('code', 'TESTBANK')->firstOrFail();

        $createResponse
            ->assertRedirect(route('banks.index', ['show' => $bank->getKey()]))
            ->assertSessionHas('status', 'El banco fue creado correctamente.');

        $this
            ->actingAs($user)
            ->patch(route('banks.update', $bank->getKey()), [
                'code' => 'TESTBANK',
                'name' => 'Banco Actualizado',
                'country' => 'Colombia',
            ])
            ->assertRedirect(route('banks.index', ['show' => $bank->getKey()]))
            ->assertSessionHas('status', 'El banco fue actualizado correctamente.');

        $this->assertSame('Banco Actualizado', $bank->refresh()->name);

        $this
            ->actingAs($user)
            ->delete(route('banks.destroy', $bank->getKey()))
            ->assertRedirect(route('banks.index'))
            ->assertSessionHas('status', 'El banco fue eliminado correctamente.');

        $this->assertModelMissing($bank);
    }

    public function test_users_can_create_a_relational_bank_account_resource(): void
    {
        $user = User::factory()->create();
        $enterprise = Enterprise::factory()->create();
        $bank = Bank::factory()->create();
        $currency = Currency::query()->firstOrCreate(
            ['code' => 'COP'],
            ['name' => 'Peso colombiano', 'symbol' => '$', 'decimal_place' => 2],
        );

        $response = $this
            ->actingAs($user)
            ->post(route('bank-accounts.store'), [
                'enterprises_Id' => $enterprise->getKey(),
                'banks_Id' => $bank->getKey(),
                'currencies_Id' => $currency->getKey(),
                'account_type' => 'savings',
                'account_number' => '1234567890',
                'account_holder' => 'Titular de prueba',
                'is_default' => '1',
                'is_active' => '1',
            ]);

        $bankAccount = BankAccount::query()->where('account_number', '1234567890')->firstOrFail();

        $response
            ->assertRedirect(route('bank-accounts.index', ['show' => $bankAccount->getKey()]))
            ->assertSessionHas('status', 'El cuenta bancaria fue creado correctamente.');

        $this->assertSame($user->currentTeam?->id, $bankAccount->team_id);
        $this->assertTrue($bankAccount->is_default);
    }

    private function seedRecords(): void
    {
        $currency = Currency::query()->firstOrCreate(
            ['code' => 'COP'],
            ['name' => 'Peso colombiano', 'symbol' => '$', 'decimal_place' => 2],
        );

        $paymentMethod = PaymentMethod::factory()->create();
        $bank = Bank::factory()->create();
        $enterprise = Enterprise::factory()->create();
        $bankAccount = BankAccount::factory()->create([
            'enterprises_Id' => $enterprise->getKey(),
            'banks_Id' => $bank->getKey(),
            'currencies_Id' => $currency->getKey(),
        ]);
        PaymentDestination::factory()->create([
            'enterprises_Id' => $enterprise->getKey(),
            'payment_methods_Id' => $paymentMethod->getKey(),
            'bank_accounts_Id' => $bankAccount->getKey(),
        ]);

        $service = Service::factory()->create(['currency_id' => $currency->getKey()]);
        Plan::factory()->create();
        ServiceRate::factory()->create([
            'service_id' => $service->getKey(),
            'currency_id' => $currency->getKey(),
        ]);
        Tax::factory()->create();
        RetentionRate::factory()->create();
    }
}

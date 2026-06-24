<?php

namespace Tests\Feature\Services;

use App\Models\Currency;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_index_renders_without_summary(): void
    {
        $currency = $this->currency();
        Service::factory()->create([
            'currency_id' => $currency->getKey(),
            'name' => 'Auditoría documental',
            'code' => 'AUD-TEST',
        ]);

        $this
            ->actingAs(User::factory()->create())
            ->get(route('services.index'))
            ->assertOk()
            ->assertSee('Gestión de Servicios')
            ->assertSee('Listado de servicios')
            ->assertSee('Auditoría documental')
            ->assertDontSee('Resumen');
    }

    public function test_users_can_create_update_and_delete_a_service(): void
    {
        $user = User::factory()->create();
        $currency = $this->currency();

        $createResponse = $this
            ->actingAs($user)
            ->post(route('services.store'), [
                'code' => 'SRV-TEST',
                'name' => 'Servicio de prueba',
                'description' => 'Descripción comercial',
                'category' => 'Consultoría',
                'pricing_type' => 'fixed',
                'unit' => 'servicio',
                'unit_price' => '250000',
                'currency_id' => $currency->getKey(),
                'is_active' => '1',
            ]);

        $service = Service::query()->where('code', 'SRV-TEST')->firstOrFail();

        $createResponse
            ->assertRedirect(route('services.edit', $service))
            ->assertSessionHas('status', 'Servicio creado correctamente.');

        $this
            ->actingAs($user)
            ->patch(route('services.update', $service), [
                'code' => 'SRV-TEST',
                'name' => 'Servicio actualizado',
                'description' => 'Descripción actualizada',
                'category' => 'Auditoría',
                'pricing_type' => 'hourly',
                'unit' => 'hora',
                'unit_price' => '180000',
                'currency_id' => $currency->getKey(),
            ])
            ->assertRedirect(route('services.edit', $service))
            ->assertSessionHas('status', 'Servicio actualizado correctamente.');

        $service->refresh();

        $this->assertSame('Servicio actualizado', $service->name);
        $this->assertFalse($service->is_active);

        $this
            ->actingAs($user)
            ->delete(route('services.destroy', $service))
            ->assertRedirect(route('services.index'))
            ->assertSessionHas('status', 'Servicio eliminado correctamente.');

        $this->assertModelMissing($service);
    }

    public function test_service_show_redirects_to_edit_instead_of_rendering_summary(): void
    {
        $service = Service::factory()->create(['currency_id' => $this->currency()->getKey()]);

        $this
            ->actingAs(User::factory()->create())
            ->get(route('services.show', $service))
            ->assertRedirect(route('services.edit', $service));
    }

    private function currency(): Currency
    {
        return Currency::query()->firstOrCreate(
            ['code' => 'COP'],
            ['name' => 'Peso colombiano', 'symbol' => '$', 'decimal_place' => 2],
        );
    }
}

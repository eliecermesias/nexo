<?php

namespace Tests\Feature\Enterprises;

use App\Models\City;
use App\Models\Country;
use App\Models\DocumentType;
use App\Models\Enterprise;
use App\Models\State;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnterpriseCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_users_can_view_the_create_enterprise_form(): void
    {
        DocumentType::query()->create([
            'code' => 'NIT',
            'name' => 'NIT',
        ]);

        Country::query()->create([
            'code' => 'CO',
            'name' => 'Colombia',
            'is_active' => true,
        ]);

        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('enterprises.create'));

        $response
            ->assertOk()
            ->assertSee('Tipo de documento')
            ->assertSee('Número de documento')
            ->assertSee('Razón social');
    }

    public function test_authenticated_users_can_store_an_enterprise(): void
    {
        $documentType = DocumentType::query()->create([
            'code' => 'NIT',
            'name' => 'NIT',
        ]);

        $country = Country::query()->create([
            'code' => 'CO',
            'name' => 'Colombia',
            'is_active' => true,
        ]);

        $state = State::query()->create([
            'countries_Id' => $country->getKey(),
            'code' => '11',
            'name' => 'Cundinamarca',
            'is_active' => true,
        ]);

        $city = City::query()->create([
            'states_Id' => $state->getKey(),
            'code' => '11001',
            'name' => 'Bogota',
            'is_active' => true,
        ]);

        $response = $this
            ->actingAs(User::factory()->create())
            ->post(route('enterprises.store'), [
                'document_types_Id' => $documentType->getKey(),
                'document_number' => '901123456',
                'legal_name' => 'Nueva Empresa SAS',
                'trade_name' => 'Nueva Empresa',
                'email' => 'contacto@nueva.test',
                'phone' => '3001234567',
                'address' => 'Calle 1 # 2-3',
                'countries_Id' => $country->getKey(),
                'states_Id' => $state->getKey(),
                'cities_Id' => $city->getKey(),
                'tax_regime' => 'Responsable de IVA',
            ]);

        $enterprise = Enterprise::query()->where('document_number', '901123456')->firstOrFail();

        $response
            ->assertRedirect(route('enterprises.show', $enterprise))
            ->assertSessionHas('status', 'Empresa creada correctamente.');

        $this->assertSame('Colombia', $enterprise->country);
        $this->assertSame('Cundinamarca', $enterprise->state);
        $this->assertSame('Bogota', $enterprise->city);
    }

    public function test_authenticated_users_can_view_an_enterprise_detail(): void
    {
        $enterprise = Enterprise::factory()->create([
            'legal_name' => 'Detalle Empresa SAS',
            'document_number' => '901555111',
        ]);

        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('enterprises.show', $enterprise));

        $response
            ->assertOk()
            ->assertSee('Ficha de empresa')
            ->assertSee('Detalle Empresa SAS')
            ->assertSee('901555111')
            ->assertSee(route('enterprises.edit', $enterprise), false);
    }

    public function test_authenticated_users_can_view_the_edit_enterprise_form(): void
    {
        $enterprise = Enterprise::factory()->create([
            'legal_name' => 'Empresa Editable SAS',
        ]);

        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('enterprises.edit', $enterprise));

        $response
            ->assertOk()
            ->assertSee('Edición de empresa')
            ->assertSee('Empresa Editable SAS')
            ->assertSee('Número de documento');
    }

    public function test_authenticated_users_can_update_an_enterprise(): void
    {
        $documentType = DocumentType::query()->create([
            'code' => 'NIT',
            'name' => 'NIT',
        ]);

        $country = Country::query()->create([
            'code' => 'CO',
            'name' => 'Colombia',
            'is_active' => true,
        ]);

        $state = State::query()->create([
            'countries_Id' => $country->getKey(),
            'code' => '11',
            'name' => 'Cundinamarca',
            'is_active' => true,
        ]);

        $city = City::query()->create([
            'states_Id' => $state->getKey(),
            'code' => '11001',
            'name' => 'Bogota',
            'is_active' => true,
        ]);

        $enterprise = Enterprise::factory()->create([
            'document_types_Id' => $documentType->getKey(),
            'document_number' => '901123456',
            'countries_Id' => $country->getKey(),
            'states_Id' => $state->getKey(),
            'cities_Id' => $city->getKey(),
        ]);

        $response = $this
            ->actingAs(User::factory()->create())
            ->patch(route('enterprises.update', $enterprise), [
                'document_types_Id' => $documentType->getKey(),
                'document_number' => '901123456-1',
                'legal_name' => 'Empresa Actualizada SAS',
                'trade_name' => 'Actualizada',
                'email' => 'actualizada@nueva.test',
                'phone' => '3011234567',
                'address' => 'Calle 9 # 8-7',
                'countries_Id' => $country->getKey(),
                'states_Id' => $state->getKey(),
                'cities_Id' => $city->getKey(),
                'tax_regime' => 'No responsable de IVA',
            ]);

        $response
            ->assertRedirect(route('enterprises.show', $enterprise))
            ->assertSessionHas('status', 'Empresa actualizada correctamente.');

        $enterprise->refresh();

        $this->assertSame('Empresa Actualizada SAS', $enterprise->legal_name);
        $this->assertSame('901123456-1', $enterprise->document_number);
        $this->assertSame('Bogota', $enterprise->city);
    }

    public function test_enterprise_location_must_match_the_selected_country_and_state(): void
    {
        $documentType = DocumentType::query()->create([
            'code' => 'NIT',
            'name' => 'NIT',
        ]);

        $country = Country::query()->create([
            'code' => 'CO',
            'name' => 'Colombia',
            'is_active' => true,
        ]);

        $otherCountry = Country::query()->create([
            'code' => 'PE',
            'name' => 'Peru',
            'is_active' => true,
        ]);

        $state = State::query()->create([
            'countries_Id' => $otherCountry->getKey(),
            'code' => '15',
            'name' => 'Lima',
            'is_active' => true,
        ]);

        $response = $this
            ->actingAs(User::factory()->create())
            ->post(route('enterprises.store'), [
                'document_types_Id' => $documentType->getKey(),
                'document_number' => '901999999',
                'legal_name' => 'Empresa Invalida SAS',
                'countries_Id' => $country->getKey(),
                'states_Id' => $state->getKey(),
            ]);

        $response->assertSessionHasErrors('states_Id');
        $this->assertDatabaseMissing('enterprises', [
            'document_number' => '901999999',
        ]);
    }

    public function test_authenticated_users_can_delete_an_enterprise_without_commercial_dependencies(): void
    {
        $enterprise = Enterprise::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->delete(route('enterprises.destroy', $enterprise));

        $response
            ->assertRedirect(route('enterprises.index'))
            ->assertSessionHas('status', 'Empresa eliminada correctamente.');

        $this->assertModelMissing($enterprise);
    }
}

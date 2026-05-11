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
}

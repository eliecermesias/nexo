<?php

namespace Tests\Feature\Enterprises;

use App\Models\DocumentType;
use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnterpriseIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_users_can_view_the_enterprise_directory(): void
    {
        $documentType = DocumentType::query()->create([
            'code' => 'NIT',
            'name' => 'NIT',
        ]);

        $enterprise = Enterprise::query()->create([
            'document_types_Id' => $documentType->getKey(),
            'document_number' => '900123456',
            'legal_name' => 'Aurum Comercial SAS',
            'trade_name' => 'Aurum',
            'email' => 'contacto@aurum.test',
            'phone' => '3001234567',
            'city' => 'Bogota',
            'state' => 'Cundinamarca',
            'country' => 'Colombia',
        ]);

        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('enterprises.index'));

        $response
            ->assertOk()
            ->assertSee('Listado de empresas')
            ->assertSee('Nueva empresa')
            ->assertSee('Aurum Comercial SAS')
            ->assertSee('contacto@aurum.test')
            ->assertSee('Bogota, Cundinamarca')
            ->assertSee(route('enterprises.create'), false)
            ->assertSee(route('enterprises.show', $enterprise), false)
            ->assertSee(route('enterprises.edit', $enterprise), false)
            ->assertDontSee('Directorio comercial')
            ->assertDontSee('btn-yellow', false);
    }

    public function test_enterprise_directory_has_an_empty_state(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('enterprises.index'));

        $response
            ->assertOk()
            ->assertSee('No hay empresas registradas');
    }

    public function test_enterprise_directory_is_paginated(): void
    {
        Enterprise::factory()->create([
            'legal_name' => 'Empresa en segunda pagina',
            'document_number' => '900000001',
        ]);

        Enterprise::factory()->count(15)->create();

        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->get(route('enterprises.index'))
            ->assertOk()
            ->assertSee('page=2', false)
            ->assertDontSee('Empresa en segunda pagina');

        $this
            ->actingAs($user)
            ->get(route('enterprises.index', ['page' => 2]))
            ->assertOk()
            ->assertSee('Empresa en segunda pagina');
    }
}

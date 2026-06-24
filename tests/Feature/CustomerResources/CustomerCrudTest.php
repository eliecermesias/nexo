<?php

namespace Tests\Feature\CustomerResources;

use App\Models\Contact;
use App\Models\DocumentType;
use App\Models\Party;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_resource_indexes_render_with_enterprise_style(): void
    {
        $documentType = $this->documentType();
        $party = Party::factory()->create(['document_types_Id' => $documentType->getKey()]);
        Contact::factory()->create(['parties_Id' => $party->getKey()]);
        Person::factory()->create(['document_type_id' => $documentType->getKey()]);

        $user = User::factory()->create();

        foreach ([
            'parties.index' => ['Gestión de Terceros', 'Listado de terceros'],
            'contacts.index' => ['Gestión de Contactos', 'Listado de contactos'],
            'people.index' => ['Gestión de Personas', 'Listado de personas'],
        ] as $route => [$title, $listing]) {
            $this
                ->actingAs($user)
                ->get(route($route))
                ->assertOk()
                ->assertSee($title)
                ->assertSee($listing)
                ->assertSee('Actualizar');
        }
    }

    public function test_users_can_create_update_and_delete_a_party(): void
    {
        $user = User::factory()->create();
        $documentType = $this->documentType();

        $createResponse = $this
            ->actingAs($user)
            ->post(route('parties.store'), [
                'document_types_Id' => $documentType->getKey(),
                'document_number' => '900123456',
                'party_type' => 'company',
                'legal_name' => 'Cliente de Prueba SAS',
                'trade_name' => 'Cliente Prueba',
                'email' => 'cliente@example.test',
                'phone' => '3001234567',
                'city' => 'Bogotá',
                'is_customer' => '1',
            ]);

        $party = Party::query()->where('document_number', '900123456')->firstOrFail();

        $createResponse
            ->assertRedirect(route('parties.show', $party))
            ->assertSessionHas('status', 'Tercero creado correctamente.');

        $this
            ->actingAs($user)
            ->patch(route('parties.update', $party), [
                'document_types_Id' => $documentType->getKey(),
                'document_number' => '900123456',
                'party_type' => 'company',
                'legal_name' => 'Cliente Actualizado SAS',
                'email' => 'cliente@example.test',
                'is_supplier' => '1',
            ])
            ->assertRedirect(route('parties.show', $party))
            ->assertSessionHas('status', 'Tercero actualizado correctamente.');

        $this->assertSame('Cliente Actualizado SAS', $party->refresh()->legal_name);
        $this->assertFalse($party->is_customer);
        $this->assertTrue($party->is_supplier);

        $this
            ->actingAs($user)
            ->delete(route('parties.destroy', $party))
            ->assertRedirect(route('parties.index'))
            ->assertSessionHas('status', 'Tercero eliminado correctamente.');

        $this->assertModelMissing($party);
    }

    public function test_users_can_create_a_contact_and_a_person(): void
    {
        $user = User::factory()->create();
        $documentType = $this->documentType();
        $party = Party::factory()->create(['document_types_Id' => $documentType->getKey()]);

        $contactResponse = $this
            ->actingAs($user)
            ->post(route('contacts.store'), [
                'parties_Id' => $party->getKey(),
                'name' => 'Contacto Comercial',
                'position' => 'Compras',
                'email' => 'contacto@example.test',
                'phone' => '3007654321',
                'is_primary' => '1',
            ]);

        $contact = Contact::query()->where('email', 'contacto@example.test')->firstOrFail();

        $contactResponse
            ->assertRedirect(route('contacts.show', $contact))
            ->assertSessionHas('status', 'Contacto creado correctamente.');

        $personResponse = $this
            ->actingAs($user)
            ->post(route('people.store'), [
                'document_type_id' => $documentType->getKey(),
                'document_number' => '1000000001',
                'name' => 'Ana María',
                'lastname' => 'Gómez',
                'email' => 'ana.gomez@example.test',
                'phone' => '3001234567',
                'address' => 'Calle 123',
            ]);

        $person = Person::query()->where('document_number', '1000000001')->firstOrFail();

        $personResponse
            ->assertRedirect(route('people.show', $person))
            ->assertSessionHas('status', 'Persona creada correctamente.');
    }

    private function documentType(): DocumentType
    {
        return DocumentType::query()->firstOrCreate(
            ['code' => 'cc'],
            ['name' => 'Cedula de ciudadania', 'is_active' => true],
        );
    }
}

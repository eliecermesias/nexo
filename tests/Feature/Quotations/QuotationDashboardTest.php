<?php

namespace Tests\Feature\Quotations;

use App\Models\Contact;
use App\Models\Currency;
use App\Models\DocumentStatus;
use App\Models\DocumentTemplate;
use App\Models\DocumentTemplateVersion;
use App\Models\Enterprise;
use App\Models\Party;
use App\Models\Quotation;
use App\Models\QuotationSetting;
use App\Models\Service;
use App\Models\Tax;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class QuotationDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_members_can_render_the_quotations_dashboard(): void
    {
        $user = User::factory()->create();
        $context = $this->quotationContext($user);

        $quotation = Quotation::factory()->create([
            'team_id' => $user->currentTeam->id,
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'enterprises_Id' => $context['enterprise']->Id,
            'parties_Id' => $context['party']->Id,
            'contacts_Id' => $context['contact']->Id,
            'currencies_Id' => $context['currency']->Id,
            'document_statuses_Id' => $context['createdStatus']->Id,
            'document_template_versions_Id' => $context['templateVersion']->Id,
            'number' => 'EM-0001',
            'total' => 1190,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('quotations.index', ['current_team' => $user->currentTeam]));

        $response
            ->assertOk()
            ->assertSee('Cotizaciones en formato dashboard')
            ->assertSee('Nueva cotización')
            ->assertSee($quotation->number)
            ->assertSee($context['party']->legal_name)
            ->assertSee($context['enterprise']->legal_name);
    }

    public function test_quotations_dashboard_is_filterable_and_paginates_results(): void
    {
        $user = User::factory()->create();
        $context = $this->quotationContext($user);

        Quotation::factory()->create([
            'team_id' => $user->currentTeam->id,
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'enterprises_Id' => $context['enterprise']->Id,
            'parties_Id' => $context['party']->Id,
            'contacts_Id' => $context['contact']->Id,
            'currencies_Id' => $context['currency']->Id,
            'document_statuses_Id' => $context['createdStatus']->Id,
            'document_template_versions_Id' => $context['templateVersion']->Id,
            'number' => 'MATCH-0001',
        ]);

        Quotation::factory()->create([
            'team_id' => $user->currentTeam->id,
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'enterprises_Id' => $context['enterprise']->Id,
            'parties_Id' => $context['otherParty']->Id,
            'contacts_Id' => $context['otherContact']->Id,
            'currencies_Id' => $context['currency']->Id,
            'document_statuses_Id' => $context['sentStatus']->Id,
            'document_template_versions_Id' => $context['templateVersion']->Id,
            'number' => 'OTHER-9999',
        ]);

        foreach (range(1, 12) as $index) {
            Quotation::factory()->create([
                'team_id' => $user->currentTeam->id,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'enterprises_Id' => $context['enterprise']->Id,
                'parties_Id' => $context['party']->Id,
                'contacts_Id' => $context['contact']->Id,
                'currencies_Id' => $context['currency']->Id,
                'document_statuses_Id' => $context['createdStatus']->Id,
                'document_template_versions_Id' => $context['templateVersion']->Id,
                'number' => sprintf('PAGE-%04d', $index),
            ]);
        }

        Livewire::actingAs($user)
            ->test('pages::quotations.index')
            ->set('search', 'MATCH')
            ->assertSee('MATCH-0001')
            ->assertDontSee('OTHER-9999')
            ->set('statusFilter', (string) $context['createdStatus']->Id)
            ->assertSee('MATCH-0001')
            ->set('search', '')
            ->set('statusFilter', (string) $context['sentStatus']->Id)
            ->assertDontSee('MATCH-0001')
            ->assertSee('OTHER-9999')
            ->set('statusFilter', '')
            ->set('perPage', 8)
            ->assertDontSee('PAGE-0012')
            ->call('gotoPage', 2)
            ->assertSee('PAGE-0012')
            ->call('clearFilters')
            ->assertSet('search', '')
            ->assertSet('statusFilter', '')
            ->assertSet('enterpriseFilter', '')
            ->assertSet('partyFilter', '')
            ->assertSet('perPage', 12);
    }

    public function test_users_can_create_a_quotation_with_defaults_from_the_dashboard_modal(): void
    {
        $user = User::factory()->create(['name' => 'Eliecer Mesias']);
        $context = $this->quotationContext($user);

        Livewire::actingAs($user)
            ->test('pages::quotations.index')
            ->call('startCreate')
            ->assertSet('form.enterprises_Id', (string) $context['enterprise']->Id)
            ->set('form.parties_Id', (string) $context['party']->Id)
            ->assertSet('form.contacts_Id', (string) $context['contact']->Id)
            ->assertSet('form.document_template_versions_Id', (string) $context['templateVersion']->Id)
            ->assertSet('form.term', $context['quotationSetting']->default_term)
            ->assertSet('form.note', $context['quotationSetting']->default_note)
            ->call('saveQuotation')
            ->assertHasNoErrors();

        $quotation = Quotation::query()
            ->where('team_id', $user->currentTeam->id)
            ->latest('Id')
            ->firstOrFail();

        $this->assertSame($user->id, $quotation->created_by);
        $this->assertSame($context['contact']->Id, $quotation->contacts_Id);
        $this->assertSame($context['createdStatus']->Id, $quotation->document_statuses_Id);
        $this->assertSame($context['templateVersion']->Id, $quotation->document_template_versions_Id);
        $this->assertSame($context['quotationSetting']->default_term, $quotation->term);
        $this->assertSame($context['quotationSetting']->default_note, $quotation->note);
        $this->assertSame('EM-0001', $quotation->number);
    }

    public function test_users_can_configure_services_discounts_and_taxes_from_the_grid(): void
    {
        $user = User::factory()->create();
        $context = $this->quotationContext($user);

        $quotation = Quotation::factory()->create([
            'team_id' => $user->currentTeam->id,
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'enterprises_Id' => $context['enterprise']->Id,
            'parties_Id' => $context['party']->Id,
            'contacts_Id' => $context['contact']->Id,
            'currencies_Id' => $context['currency']->Id,
            'document_statuses_Id' => $context['createdStatus']->Id,
            'document_template_versions_Id' => $context['templateVersion']->Id,
            'number' => 'EM-0001',
        ]);

        Livewire::actingAs($user)
            ->test('pages::quotations.index')
            ->call('openItemsModal', $quotation->Id)
            ->set('selectedServiceIds', [(string) $context['service']->Id])
            ->set('items.0.quantity', 2)
            ->set('items.0.discount_rate', 10)
            ->set('items.0.taxes_Id', (string) $context['tax']->Id)
            ->call('saveItems')
            ->assertHasNoErrors();

        $quotation->refresh();

        $this->assertSame('300000.00', (string) $quotation->subtotal);
        $this->assertSame('30000.00', (string) $quotation->discount_total);
        $this->assertSame('51300.00', (string) $quotation->tax_total);
        $this->assertSame('321300.00', (string) $quotation->total);
        $this->assertDatabaseHas('quotation_items', [
            'quotations_Id' => $quotation->Id,
            'services_Id' => $context['service']->Id,
            'tax_rate' => 19,
        ]);
    }

    public function test_users_can_update_a_quotation_status_from_the_panel(): void
    {
        $user = User::factory()->create();
        $context = $this->quotationContext($user);

        $quotation = Quotation::factory()->create([
            'team_id' => $user->currentTeam->id,
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'enterprises_Id' => $context['enterprise']->Id,
            'parties_Id' => $context['party']->Id,
            'contacts_Id' => $context['contact']->Id,
            'currencies_Id' => $context['currency']->Id,
            'document_statuses_Id' => $context['createdStatus']->Id,
            'document_template_versions_Id' => $context['templateVersion']->Id,
        ]);

        Livewire::actingAs($user)
            ->test('pages::quotations.index')
            ->call('openStatusModal', $quotation->Id)
            ->set('statusForm.document_statuses_Id', (string) $context['sentStatus']->Id)
            ->call('updateStatus')
            ->assertHasNoErrors();

        $quotation->refresh();

        $this->assertSame($context['sentStatus']->Id, $quotation->document_statuses_Id);
    }

    /**
     * @return array<string, mixed>
     */
    private function quotationContext(User $user): array
    {
        $currency = Currency::query()->create([
            'code' => 'COP',
            'name' => 'Peso colombiano',
            'symbol' => '$',
            'decimal_place' => 2,
        ]);

        $createdStatus = DocumentStatus::query()->create([
            'code' => 'created',
            'name' => 'Created',
        ]);

        $sentStatus = DocumentStatus::query()->create([
            'code' => 'sent',
            'name' => 'Sent',
        ]);

        $enterprise = Enterprise::factory()->create([
            'team_id' => $user->currentTeam->id,
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'legal_name' => 'Nexo Comercial SAS',
        ]);

        $party = Party::factory()->create([
            'team_id' => $user->currentTeam->id,
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'legal_name' => 'Cliente Uno SAS',
        ]);

        $otherParty = Party::factory()->create([
            'team_id' => $user->currentTeam->id,
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'legal_name' => 'Cliente Dos SAS',
        ]);

        $contact = Contact::factory()->primary()->create([
            'team_id' => $user->currentTeam->id,
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'parties_Id' => $party->Id,
            'name' => 'Laura Gomez',
        ]);

        $otherContact = Contact::factory()->primary()->create([
            'team_id' => $user->currentTeam->id,
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'parties_Id' => $otherParty->Id,
            'name' => 'Miguel Ruiz',
        ]);

        $service = Service::factory()->create([
            'team_id' => $user->currentTeam->id,
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'code' => 'SERV-001',
            'name' => 'Servicio mensual',
            'unit' => 'unidad',
            'unit_price' => 150000,
        ]);

        $tax = Tax::factory()->create([
            'code' => 'iva_19',
            'name' => 'IVA 19%',
            'rate' => 19,
        ]);

        $template = DocumentTemplate::factory()->quotationDefault()->create([
            'team_id' => $user->currentTeam->id,
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'enterprises_Id' => $enterprise->Id,
            'name' => 'Plantilla comercial base',
        ]);

        $templateVersion = DocumentTemplateVersion::factory()->create([
            'document_templates_Id' => $template->Id,
            'version' => 1,
            'is_published' => true,
            'published_at' => now(),
        ]);

        $quotationSetting = QuotationSetting::factory()->create([
            'team_id' => $user->currentTeam->id,
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'enterprises_Id' => $enterprise->Id,
            'document_template_versions_Id' => $templateVersion->Id,
            'default_term' => 'Pago a 30 dias calendario.',
            'default_note' => 'Validar cupo y aprobacion comercial.',
            'validity_days' => 15,
        ]);

        return compact(
            'enterprise',
            'party',
            'otherParty',
            'contact',
            'otherContact',
            'currency',
            'createdStatus',
            'sentStatus',
            'service',
            'tax',
            'templateVersion',
            'quotationSetting',
        );
    }
}

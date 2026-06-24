<?php

namespace Tests\Feature\DocumentSequences;

use App\Models\Currency;
use App\Models\DocumentStatus;
use App\Models\InternalSequence;
use App\Models\Quotation;
use App\Models\SequenceHistory;
use App\Models\User;
use App\Support\DocumentSequenceGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentSequenceGeneratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_numbers_from_configured_format_and_updates_current_value(): void
    {
        $user = User::factory()->create();
        $sequence = InternalSequence::factory()->create([
            'team_id' => $user->currentTeam->id,
            'document_type' => 'quotation',
            'prefix' => 'COT-',
            'suffix' => '-A',
            'initial_value' => 100,
            'final_value' => 101,
            'current_value' => 99,
            'number_format' => '{prefix}{year}-{number}{suffix}',
            'number_length' => 5,
            'padding' => 4,
        ]);

        $this->actingAs($user);

        $generator = app(DocumentSequenceGenerator::class);

        $this->assertSame('COT-'.now()->format('Y').'-00100-A', $generator->preview('quotation', $user->currentTeam->id));

        $first = $generator->next('quotation', $user->currentTeam->id);
        $second = $generator->next('quotation', $user->currentTeam->id);

        $this->assertSame('COT-'.now()->format('Y').'-00100-A', $first['number']);
        $this->assertSame('COT-'.now()->format('Y').'-00101-A', $second['number']);
        $this->assertSame(101, $sequence->refresh()->current_value);
        $this->assertSame(2, SequenceHistory::query()->where('internal_sequence_id', $sequence->getKey())->count());
    }

    public function test_it_assigns_generated_number_to_a_document_and_history(): void
    {
        $user = User::factory()->create();
        InternalSequence::factory()->create([
            'team_id' => $user->currentTeam->id,
            'document_type' => 'quotation',
            'prefix' => 'Q-',
            'initial_value' => 1,
            'current_value' => 0,
            'number_length' => 3,
            'padding' => 4,
        ]);
        $currency = Currency::query()->create([
            'code' => 'COP',
            'name' => 'Peso colombiano',
            'symbol' => '$',
            'decimal_place' => 2,
        ]);
        $status = DocumentStatus::query()->create([
            'code' => 'created',
            'name' => 'Creada',
        ]);

        $quotation = Quotation::factory()->create([
            'team_id' => $user->currentTeam->id,
            'currencies_Id' => $currency->getKey(),
            'document_statuses_Id' => $status->getKey(),
            'number' => 'TEMP',
        ]);

        $this->actingAs($user);

        $number = app(DocumentSequenceGenerator::class)->assign($quotation, 'quotation', 'number', $user->currentTeam->id);

        $this->assertSame('Q-001', $number);
        $this->assertSame('Q-001', $quotation->refresh()->number);
        $this->assertDatabaseHas('sequence_histories', [
            'document_number' => 'Q-001',
            'documentable_type' => Quotation::class,
            'documentable_id' => $quotation->getKey(),
        ]);
    }
}

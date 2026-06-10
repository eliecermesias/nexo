<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Currency;
use App\Models\DocumentStatus;
use App\Models\DocumentTemplateVersion;
use App\Models\Enterprise;
use App\Models\Party;
use App\Models\Quotation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Quotation>
 */
class QuotationFactory extends Factory
{
    protected $model = Quotation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $owner = User::query()->first() ?? User::factory()->create();
        $currencyId = Currency::query()->value('Id');
        $statusId = DocumentStatus::query()->where('code', 'created')->value('Id')
            ?? DocumentStatus::query()->value('Id');

        return [
            'team_id' => $owner->currentTeam?->id,
            'created_by' => $owner->getKey(),
            'updated_by' => $owner->getKey(),
            'enterprises_Id' => Enterprise::factory(),
            'parties_Id' => Party::factory(),
            'contacts_Id' => Contact::factory(),
            'currencies_Id' => $currencyId,
            'document_statuses_Id' => $statusId,
            'document_template_versions_Id' => DocumentTemplateVersion::factory(),
            'number' => strtoupper($owner->initials()).'-'.str_pad((string) fake()->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'issue_date' => now()->toDateString(),
            'valid_until' => now()->addDays(15)->toDateString(),
            'subtotal' => 0,
            'discount_total' => 0,
            'tax_total' => 0,
            'total' => 0,
            'term' => fake()->paragraph(),
            'note' => fake()->sentence(),
        ];
    }
}

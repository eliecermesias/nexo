<?php

namespace Database\Seeders;

use App\Models\InternalSequence;
use App\Models\User;
use Illuminate\Database\Seeder;

class InternalSequencesSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::query()->firstOrFail();
        $teamId = $owner->currentTeam?->id;

        foreach ($this->sequences() as $sequence) {
            InternalSequence::query()->updateOrCreate(
                [
                    'team_id' => $teamId,
                    'client_enterprise_id' => null,
                    'document_type' => $sequence['document_type'],
                ],
                $sequence + [
                    'team_id' => $teamId,
                    'client_enterprise_id' => null,
                    'current_value' => 0,
                    'number_length' => 4,
                    'number_format' => '{prefix}{number}{suffix}',
                    'created_by' => $owner->getKey(),
                    'is_active' => true,
                ],
            );
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function sequences(): array
    {
        return [
            [
                'document_type' => 'quotation',
                'prefix' => 'COT-',
                'initial_value' => 1,
                'final_value' => null,
                'suffix' => null,
                'padding' => 4,
                'resets_yearly' => false,
            ],
            [
                'document_type' => 'proposal',
                'prefix' => 'PROP-',
                'initial_value' => 1,
                'final_value' => null,
                'suffix' => null,
                'padding' => 4,
                'resets_yearly' => false,
            ],
            [
                'document_type' => 'collection_account',
                'prefix' => 'CC-',
                'initial_value' => 1,
                'final_value' => null,
                'suffix' => null,
                'padding' => 4,
                'resets_yearly' => false,
            ],
        ];
    }
}

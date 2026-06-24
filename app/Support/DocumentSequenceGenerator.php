<?php

namespace App\Support;

use App\Models\InternalSequence;
use App\Models\SequenceHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class DocumentSequenceGenerator
{
    public function preview(string $documentType, ?int $teamId = null, ?int $clientEnterpriseId = null): string
    {
        $teamId ??= Auth::user()?->currentTeam?->id;
        $sequence = $this->findSequence($documentType, $teamId, $clientEnterpriseId);

        if ($sequence === null) {
            $sequence = $this->defaultSequence($documentType, $teamId, $clientEnterpriseId);
        }

        return $this->format($sequence, $this->nextNumericValue($sequence));
    }

    public function next(string $documentType, ?int $teamId = null, ?int $clientEnterpriseId = null): array
    {
        $teamId ??= Auth::user()?->currentTeam?->id;

        return DB::transaction(function () use ($documentType, $teamId, $clientEnterpriseId): array {
            $sequence = $this->findSequence($documentType, $teamId, $clientEnterpriseId, true);

            if ($sequence === null) {
                $sequence = $this->defaultSequence($documentType, $teamId, $clientEnterpriseId);
                $sequence->save();
                $sequence->refresh();
            }

            if (! $sequence->is_active) {
                throw new RuntimeException("La secuencia {$documentType} no está activa.");
            }

            $numericValue = $this->nextNumericValue($sequence);

            if ($sequence->final_value !== null && $numericValue > $sequence->final_value) {
                throw new RuntimeException("La secuencia {$documentType} alcanzó su valor final.");
            }

            $documentNumber = $this->format($sequence, $numericValue);
            $sequence->update(['current_value' => $numericValue]);

            $history = SequenceHistory::query()->create([
                'internal_sequence_id' => $sequence->getKey(),
                'sequence_counter_id' => null,
                'document_number' => $documentNumber,
                'numeric_value' => $numericValue,
                'generated_by' => Auth::id(),
                'generated_at' => now(),
            ]);

            return [
                'number' => $documentNumber,
                'numeric_value' => $numericValue,
                'sequence' => $sequence,
                'history' => $history,
            ];
        }, 5);
    }

    public function assign(Model $document, string $documentType, string $numberColumn = 'number', ?int $teamId = null, ?int $clientEnterpriseId = null): string
    {
        $allocation = $this->next($documentType, $teamId, $clientEnterpriseId);

        $document->forceFill([$numberColumn => $allocation['number']])->save();

        /** @var SequenceHistory $history */
        $history = $allocation['history'];
        $history->documentable()->associate($document);
        $history->save();

        return $allocation['number'];
    }

    private function findSequence(string $documentType, ?int $teamId, ?int $clientEnterpriseId, bool $lock = false): ?InternalSequence
    {
        $query = InternalSequence::query()
            ->where('team_id', $teamId)
            ->where('document_type', $documentType)
            ->where(function ($query) use ($clientEnterpriseId): void {
                if ($clientEnterpriseId === null) {
                    $query->whereNull('client_enterprise_id');
                } else {
                    $query->where('client_enterprise_id', $clientEnterpriseId);
                }
            });

        if ($lock) {
            $query->lockForUpdate();
        }

        return $query->first();
    }

    private function defaultSequence(string $documentType, ?int $teamId, ?int $clientEnterpriseId): InternalSequence
    {
        return new InternalSequence([
            'team_id' => $teamId,
            'client_enterprise_id' => $clientEnterpriseId,
            'document_type' => $documentType,
            'prefix' => $this->defaultPrefix($documentType),
            'initial_value' => 1,
            'final_value' => null,
            'current_value' => 0,
            'number_length' => 4,
            'number_format' => '{prefix}{number}{suffix}',
            'suffix' => null,
            'padding' => 4,
            'resets_yearly' => false,
            'is_active' => true,
            'created_by' => Auth::id(),
        ]);
    }

    private function nextNumericValue(InternalSequence $sequence): int
    {
        $currentValue = max((int) $sequence->current_value, (int) $sequence->initial_value - 1);

        return $currentValue + 1;
    }

    private function format(InternalSequence $sequence, int $numericValue): string
    {
        $length = (int) ($sequence->number_length ?: $sequence->padding);
        $number = str_pad((string) $numericValue, $length, '0', STR_PAD_LEFT);

        return strtr($sequence->number_format ?: '{prefix}{number}{suffix}', [
            '{prefix}' => (string) $sequence->prefix,
            '{number}' => $number,
            '{suffix}' => (string) $sequence->suffix,
            '{year}' => now()->format('Y'),
            '{document_type}' => $sequence->document_type,
        ]);
    }

    private function defaultPrefix(string $documentType): string
    {
        return match ($documentType) {
            'quotation' => 'COT-',
            'proposal' => 'PROP-',
            'collection_account' => 'CC-',
            default => strtoupper($documentType).'-',
        };
    }
}

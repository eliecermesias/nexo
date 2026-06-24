<?php

use App\Models\Contact;
use App\Models\Currency;
use App\Models\DocumentStatus;
use App\Models\DocumentTemplateVersion;
use App\Models\Enterprise;
use App\Models\Party;
use App\Models\Quotation;
use App\Models\QuotationSetting;
use App\Models\Service;
use App\Models\Tax;
use App\Support\DocumentSequenceGenerator;
use Flux\Flux;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Cotizaciones')] class extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'status')]
    public string $statusFilter = '';

    #[Url(as: 'party')]
    public string $partyFilter = '';

    #[Url(as: 'enterprise')]
    public string $enterpriseFilter = '';

    #[Url(as: 'sort')]
    public string $sortBy = 'issue_date';

    #[Url(as: 'dir')]
    public string $sortDirection = 'desc';

    #[Url(as: 'per_page')]
    public int $perPage = 12;

    public string $formMode = 'create';

    public ?int $selectedQuotationId = null;

    /**
     * @var array<int, string>
     */
    public array $selectedQuotationIds = [];

    public bool $selectPage = false;

    public bool $showSummaryPanel = true;

    /**
     * @var array{
     *     enterprises_Id: string,
     *     parties_Id: string,
     *     contacts_Id: string,
     *     currencies_Id: string,
     *     document_template_versions_Id: string,
     *     number: string,
     *     issue_date: string,
     *     valid_until: string,
     *     term: string,
     *     note: string
     * }
     */
    public array $form = [];

    /**
     * @var array<int, array{
     *     services_Id: string,
     *     taxes_Id: string,
     *     description: string,
     *     quantity: float|int|string,
     *     unit_price: float|int|string,
     *     discount_type: string,
     *     discount_rate: float|int|string,
     *     discount_amount: float|int|string,
     *     discount_description: string,
     *     tax_rate: float|int|string,
     *     line_total: float|int|string
     * }>
     */
    public array $items = [];

    /**
     * @var array<int, string>
     */
    public array $selectedServiceIds = [];

    /**
     * @var array<int, string>
     */
    public array $selectedTaxIds = [];

    /**
     * @var array{document_statuses_Id: string}
     */
    public array $statusForm = [
        'document_statuses_Id' => '',
    ];

    /**
     * @var array{document_statuses_Id: string}
     */
    public array $bulkStatusForm = [
        'document_statuses_Id' => '',
    ];

    /**
     * @var array{term: string, note: string}
     */
    public array $termsForm = [
        'term' => '',
        'note' => '',
    ];

    public function mount(): void
    {
        $this->resetFormState();
    }

    public function updated(string $property): void
    {
        if (in_array($property, ['search', 'statusFilter', 'partyFilter', 'enterpriseFilter', 'perPage'], true)) {
            $this->clearSelection();
            $this->resetPage();
        }

        if ($property === 'form.parties_Id') {
            $this->form['contacts_Id'] = $this->resolveDefaultContactId($this->form['parties_Id']);
        }

        if ($property === 'form.enterprises_Id') {
            $this->applyQuotationDefaultsForEnterprise($this->form['enterprises_Id']);
        }

        if (! str_starts_with($property, 'items.')) {
            return;
        }

        $segments = explode('.', $property);
        $index = isset($segments[1]) ? (int) $segments[1] : null;
        $field = $segments[2] ?? null;

        if ($index === null || $field === null || ! isset($this->items[$index])) {
            return;
        }

        if ($field === 'services_Id' && $this->items[$index]['services_Id'] !== '') {
            $service = $this->services->firstWhere('Id', (int) $this->items[$index]['services_Id']);

            if ($service) {
                $this->items[$index]['description'] = $service->name;
                $this->items[$index]['unit_price'] = (float) $service->unit_price;
            }
        }

        if ($field === 'taxes_Id') {
            $tax = $this->taxes->firstWhere('Id', (int) ($this->items[$index]['taxes_Id'] ?: 0));
            $this->items[$index]['tax_rate'] = $tax ? (float) $tax->rate : 0;
        }

        $this->recalculateTotals();
    }

    public function startCreate(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->resetFormState();
        $this->formMode = 'create';
        Flux::modal('quotation-form')->show();
    }

    public function openShowModal(int $quotationId): void
    {
        $this->selectedQuotationId = $quotationId;
        Flux::modal('quotation-details')->show();
    }

    public function openEditModal(int $quotationId): void
    {
        $quotation = Quotation::query()
            ->with('items')
            ->findOrFail($quotationId);

        $this->resetErrorBag();
        $this->resetValidation();

        $this->selectedQuotationId = $quotation->Id;
        $this->formMode = 'edit';
        $this->form = [
            'enterprises_Id' => (string) $quotation->enterprises_Id,
            'parties_Id' => (string) $quotation->parties_Id,
            'contacts_Id' => (string) ($quotation->contacts_Id ?? ''),
            'currencies_Id' => (string) $quotation->currencies_Id,
            'document_template_versions_Id' => (string) ($quotation->document_template_versions_Id ?? ''),
            'number' => $quotation->number,
            'issue_date' => $quotation->issue_date?->format('Y-m-d') ?? '',
            'valid_until' => $quotation->valid_until?->format('Y-m-d') ?? '',
            'term' => $quotation->term ?? '',
            'note' => $quotation->note ?? '',
        ];
        Flux::modal('quotation-form')->show();
    }

    public function updatedSelectedServiceIds(): void
    {
        $this->syncSelectedServices();
    }

    public function updatedSelectedTaxIds(): void
    {
        $this->selectedTaxIds = collect($this->selectedTaxIds)
            ->map(fn (string|int $id): string => (string) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $tax = $this->taxes->firstWhere('Id', (int) (collect($this->selectedTaxIds)->last() ?: 0));

        foreach ($this->items as $index => $item) {
            $this->items[$index]['taxes_Id'] = $tax ? (string) $tax->Id : '';
            $this->items[$index]['tax_rate'] = $tax ? (float) $tax->rate : 0;
        }

        $this->recalculateTotals();
    }

    public function setServiceSelection(string $serviceId, bool $selected): void
    {
        $this->selectedServiceIds = $selected
            ? collect($this->selectedServiceIds)->push($serviceId)->unique()->values()->all()
            : collect($this->selectedServiceIds)->reject(fn (string $id): bool => $id === $serviceId)->values()->all();

        $this->syncSelectedServices();

        if (! $selected) {
            return;
        }

        $index = collect($this->items)->search(fn (array $item): bool => $item['services_Id'] === $serviceId);

        if ($index !== false) {
            $this->items[$index]['quantity'] = 1;
        }
    }

    public function updatedSelectPage(bool $checked): void
    {
        $this->selectedQuotationIds = $checked
            ? $this->quotations->pluck('Id')->map(fn (int $id) => (string) $id)->all()
            : [];
    }

    public function updatedSelectedQuotationIds(): void
    {
        $this->selectedQuotationIds = collect($this->selectedQuotationIds)
            ->map(fn (string|int $id): string => (string) $id)
            ->unique()
            ->values()
            ->all();

        $this->selectPage = count($this->selectedQuotationIds) === $this->quotations->count()
            && $this->quotations->count() > 0;
    }

    public function openItemsModal(int $quotationId): void
    {
        $this->openItemsEditor($quotationId);
        Flux::modal('quotation-items')->show();
    }

    public function openDiscountsModal(int $quotationId): void
    {
        $this->openItemsEditor($quotationId);
        Flux::modal('quotation-discounts')->show();
    }

    public function openTaxesModal(int $quotationId): void
    {
        $this->openItemsEditor($quotationId);
        Flux::modal('quotation-taxes')->show();
    }

    public function saveQuotation(): void
    {
        $this->normalizeOptionalFields();
        $validated = $this->validate($this->quotationRules());
        $statusId = $this->createdStatusId();

        DB::transaction(function () use ($validated, $statusId): void {
            $payload = [
                'team_id' => Auth::user()?->currentTeam?->id,
                'enterprises_Id' => (int) $validated['form']['enterprises_Id'],
                'parties_Id' => (int) $validated['form']['parties_Id'],
                'contacts_Id' => filled($validated['form']['contacts_Id']) ? (int) $validated['form']['contacts_Id'] : null,
                'currencies_Id' => (int) $validated['form']['currencies_Id'],
                'document_statuses_Id' => $this->formMode === 'edit' && $this->selectedQuotation !== null
                    ? $this->selectedQuotation->document_statuses_Id
                    : $statusId,
                'document_template_versions_Id' => filled($validated['form']['document_template_versions_Id']) ? (int) $validated['form']['document_template_versions_Id'] : null,
                'number' => $validated['form']['number'],
                'issue_date' => $validated['form']['issue_date'],
                'valid_until' => filled($validated['form']['valid_until']) ? $validated['form']['valid_until'] : null,
                'subtotal' => $this->selectedQuotation?->subtotal ?? 0,
                'discount_total' => $this->selectedQuotation?->discount_total ?? 0,
                'tax_total' => $this->selectedQuotation?->tax_total ?? 0,
                'total' => $this->selectedQuotation?->total ?? 0,
                'term' => filled($validated['form']['term']) ? $validated['form']['term'] : null,
                'note' => filled($validated['form']['note']) ? $validated['form']['note'] : null,
                'updated_by' => Auth::id(),
            ];

            if ($this->formMode === 'edit' && $this->selectedQuotationId !== null) {
                $quotation = Quotation::query()->findOrFail($this->selectedQuotationId);
                $quotation->update($payload);
            } else {
                $allocation = app(DocumentSequenceGenerator::class)->next(
                    'quotation',
                    Auth::user()?->currentTeam?->id,
                );

                $payload['number'] = $allocation['number'];
                $payload['created_by'] = Auth::id();
                $quotation = Quotation::query()->create($payload);

                $allocation['history']->documentable()->associate($quotation);
                $allocation['history']->save();

                $this->selectedQuotationId = $quotation->Id;
            }
        });

        $message = $this->formMode === 'edit'
            ? 'La cotización se actualizó correctamente.'
            : 'La cotización se creó correctamente.';

        Flux::modal('quotation-form')->close();
        Flux::toast(variant: 'success', text: $message);
        $this->resetFormState();
        $this->resetPage();
    }

    public function saveItems(): void
    {
        if ($this->selectedQuotationId === null) {
            return;
        }

        $this->normalizeOptionalFields();
        $this->recalculateTotals();

        $validated = $this->validate($this->itemRules());
        $quotation = Quotation::query()->findOrFail($this->selectedQuotationId);

        DB::transaction(function () use ($quotation, $validated): void {
            $quotation->items()->delete();

            foreach ($validated['items'] as $index => $item) {
                $baseAmount = (float) $item['quantity'] * (float) $item['unit_price'];
                $discountAmount = $this->discountAmountForItem($item, $baseAmount);
                $discountRate = $baseAmount > 0 ? ($discountAmount / $baseAmount) * 100 : 0;
                $taxableAmount = $baseAmount - $discountAmount;
                $taxAmount = $taxableAmount * ((float) $item['tax_rate'] / 100);

                $quotation->items()->create([
                    'services_Id' => filled($item['services_Id']) ? (int) $item['services_Id'] : null,
                    'plans_Id' => null,
                    'taxes_Id' => filled($item['taxes_Id']) ? (int) $item['taxes_Id'] : null,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount_rate' => $discountRate,
                    'discount_amount' => $discountAmount,
                    'discount_description' => filled($item['discount_description']) ? $item['discount_description'] : null,
                    'tax_rate' => $item['tax_rate'],
                    'line_total' => $taxableAmount + $taxAmount,
                    'sort_order' => $index + 1,
                ]);
            }

            $quotation->update([
                'subtotal' => $this->totals['subtotal'],
                'discount_total' => $this->totals['discount_total'],
                'tax_total' => $this->totals['tax_total'],
                'total' => $this->totals['total'],
                'updated_by' => Auth::id(),
            ]);
        });

        Flux::modal('quotation-items')->close();
        Flux::modal('quotation-discounts')->close();
        Flux::modal('quotation-taxes')->close();
        Flux::toast(variant: 'success', text: 'Los servicios de la cotización se actualizaron correctamente.');
    }

    public function sort(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';

            return;
        }

        $this->sortBy = $field;
        $this->sortDirection = in_array($field, ['number', 'party', 'enterprise'], true) ? 'asc' : 'desc';
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->enterpriseFilter = '';
        $this->partyFilter = '';
        $this->perPage = 12;
        $this->resetPage();
    }

    public function openStatusModal(int $quotationId): void
    {
        $quotation = Quotation::query()->findOrFail($quotationId);

        $this->selectedQuotationId = $quotation->Id;
        $this->statusForm['document_statuses_Id'] = (string) $quotation->document_statuses_Id;

        Flux::modal('quotation-status')->show();
    }

    public function openBulkStatusModal(): void
    {
        if ($this->selectedQuotationIds === []) {
            Flux::toast(variant: 'warning', text: 'Selecciona al menos una cotización.');

            return;
        }

        $this->bulkStatusForm['document_statuses_Id'] = (string) $this->createdStatusId();

        Flux::modal('quotation-bulk-status')->show();
    }

    public function updateStatus(): void
    {
        $validated = $this->validate([
            'statusForm.document_statuses_Id' => ['required', 'exists:document_statuses,Id'],
        ]);

        if ($this->selectedQuotationId === null) {
            return;
        }

        Quotation::query()
            ->findOrFail($this->selectedQuotationId)
            ->update([
                'document_statuses_Id' => (int) $validated['statusForm']['document_statuses_Id'],
                'updated_by' => Auth::id(),
            ]);

        Flux::modal('quotation-status')->close();
        Flux::toast(variant: 'success', text: 'El estado de la cotización se actualizó correctamente.');
    }

    public function updateBulkStatus(): void
    {
        $validated = $this->validate([
            'bulkStatusForm.document_statuses_Id' => ['required', 'exists:document_statuses,Id'],
        ]);

        $affected = Quotation::query()
            ->where('team_id', $this->currentTeamId())
            ->whereIn('Id', $this->selectedQuotationIds)
            ->update([
                'document_statuses_Id' => (int) $validated['bulkStatusForm']['document_statuses_Id'],
                'updated_by' => Auth::id(),
            ]);

        $this->clearSelection();
        Flux::modal('quotation-bulk-status')->close();
        Flux::toast(variant: 'success', text: "{$affected} cotizaciones actualizadas.");
    }

    public function sendQuotation(int $quotationId): void
    {
        $sentStatusId = $this->sentStatusId();

        if ($sentStatusId === null) {
            Flux::toast(variant: 'danger', text: 'No existe un estado Sent configurado.');

            return;
        }

        Quotation::query()
            ->where('team_id', $this->currentTeamId())
            ->findOrFail($quotationId)
            ->update([
                'document_statuses_Id' => $sentStatusId,
                'updated_by' => Auth::id(),
            ]);

        Flux::toast(variant: 'success', text: 'La cotización quedó marcada como enviada.');
    }

    public function sendSelectedQuotations(): void
    {
        if ($this->selectedQuotationIds === []) {
            Flux::toast(variant: 'warning', text: 'Selecciona al menos una cotización.');

            return;
        }

        $sentStatusId = $this->sentStatusId();

        if ($sentStatusId === null) {
            Flux::toast(variant: 'danger', text: 'No existe un estado Sent configurado.');

            return;
        }

        $affected = Quotation::query()
            ->where('team_id', $this->currentTeamId())
            ->whereIn('Id', $this->selectedQuotationIds)
            ->update([
                'document_statuses_Id' => $sentStatusId,
                'updated_by' => Auth::id(),
            ]);

        $this->clearSelection();
        Flux::toast(variant: 'success', text: "{$affected} cotizaciones enviadas.");
    }

    public function deleteQuotation(int $quotationId): void
    {
        $quotation = Quotation::query()
            ->where('team_id', $this->currentTeamId())
            ->findOrFail($quotationId);

        DB::transaction(function () use ($quotation): void {
            $quotation->items()->delete();
            $quotation->delete();
        });

        $this->clearSelection();
        Flux::toast(variant: 'success', text: 'La cotización fue eliminada.');
    }

    public function deleteSelectedQuotations(): void
    {
        if ($this->selectedQuotationIds === []) {
            Flux::toast(variant: 'warning', text: 'Selecciona al menos una cotización.');

            return;
        }

        $quotationIds = $this->selectedQuotationIds;

        $affected = DB::transaction(function () use ($quotationIds): int {
            $quotations = Quotation::query()
                ->where('team_id', $this->currentTeamId())
                ->whereIn('Id', $quotationIds)
                ->get();

            $quotations->each(function (Quotation $quotation): void {
                $quotation->items()->delete();
                $quotation->delete();
            });

            return $quotations->count();
        });

        $this->clearSelection();
        Flux::toast(variant: 'success', text: "{$affected} cotizaciones eliminadas.");
    }

    public function openTermsModal(int $quotationId): void
    {
        $quotation = Quotation::query()
            ->where('team_id', $this->currentTeamId())
            ->findOrFail($quotationId);

        $this->selectedQuotationId = $quotation->Id;
        $this->termsForm = [
            'term' => $quotation->term ?? '',
            'note' => $quotation->note ?? '',
        ];

        Flux::modal('quotation-terms')->show();
    }

    public function applyTermPreset(string $term): void
    {
        $this->termsForm['term'] = $term;
    }

    public function saveTerms(): void
    {
        if ($this->selectedQuotationId === null) {
            return;
        }

        $validated = $this->validate([
            'termsForm.term' => ['nullable', 'string'],
            'termsForm.note' => ['nullable', 'string'],
        ]);

        Quotation::query()
            ->where('team_id', $this->currentTeamId())
            ->findOrFail($this->selectedQuotationId)
            ->update([
                'term' => filled($validated['termsForm']['term']) ? $validated['termsForm']['term'] : null,
                'note' => filled($validated['termsForm']['note']) ? $validated['termsForm']['note'] : null,
                'updated_by' => Auth::id(),
            ]);

        Flux::modal('quotation-terms')->close();
        Flux::toast(variant: 'success', text: 'Los términos y condiciones fueron actualizados.');
    }

    public function printQuotation(int $quotationId): void
    {
        $this->selectedQuotationId = $quotationId;
        Flux::modal('quotation-details')->show();
        $this->js('setTimeout(() => window.print(), 250)');
    }

    #[Computed]
    public function quotations(): LengthAwarePaginator
    {
        $query = $this->filteredQuery();

        $orderedQuery = match ($this->sortBy) {
            'number' => $query->orderBy('number', $this->sortDirection),
            'party' => $query->join('parties', 'parties.Id', '=', 'quotations.parties_Id')
                ->select('quotations.*')
                ->orderBy('parties.legal_name', $this->sortDirection),
            'enterprise' => $query->join('enterprises', 'enterprises.Id', '=', 'quotations.enterprises_Id')
                ->select('quotations.*')
                ->orderBy('enterprises.legal_name', $this->sortDirection),
            'total' => $query->orderBy('total', $this->sortDirection),
            default => $query->orderBy($this->sortBy, $this->sortDirection),
        };

        return $orderedQuery->paginate($this->perPage);
    }

    /**
     * @return array{total:int,this_month:int,expiring_soon:int,customers:int}
     */
    #[Computed]
    public function stats(): array
    {
        $query = $this->filteredQuery();

        return [
            'total' => (clone $query)->count(),
            'this_month' => (clone $query)
                ->whereBetween('issue_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
                ->count(),
            'expiring_soon' => (clone $query)
                ->whereNotNull('valid_until')
                ->whereBetween('valid_until', [now()->toDateString(), now()->addDays(7)->toDateString()])
                ->count(),
            'customers' => (clone $query)
                ->distinct('parties_Id')
                ->count('parties_Id'),
        ];
    }

    #[Computed]
    public function totals(): array
    {
        $subtotal = 0.0;
        $discountTotal = 0.0;
        $taxTotal = 0.0;

        foreach ($this->items as $index => $item) {
            $quantity = max((float) ($item['quantity'] ?: 0), 0);
            $unitPrice = max((float) ($item['unit_price'] ?: 0), 0);
            $taxRate = max((float) ($item['tax_rate'] ?: 0), 0);

            $baseAmount = $quantity * $unitPrice;
            $discountAmount = $this->discountAmountForItem($item, $baseAmount);
            $taxableAmount = $baseAmount - $discountAmount;
            $taxAmount = $taxableAmount * ($taxRate / 100);
            $lineTotal = $taxableAmount + $taxAmount;

            $subtotal += $baseAmount;
            $discountTotal += $discountAmount;
            $taxTotal += $taxAmount;
            $this->items[$index]['line_total'] = round($lineTotal, 2);
        }

        return [
            'subtotal' => round($subtotal, 2),
            'discount_total' => round($discountTotal, 2),
            'tax_total' => round($taxTotal, 2),
            'total' => round($subtotal - $discountTotal + $taxTotal, 2),
        ];
    }

    #[Computed]
    public function enterprises(): Collection
    {
        return Enterprise::query()
            ->where('team_id', Auth::user()?->currentTeam?->id)
            ->orderBy('legal_name')
            ->get(['Id', 'legal_name']);
    }

    #[Computed]
    public function parties(): Collection
    {
        return Party::query()
            ->where('team_id', Auth::user()?->currentTeam?->id)
            ->where('is_customer', true)
            ->orderBy('legal_name')
            ->get(['Id', 'legal_name']);
    }

    #[Computed]
    public function contacts(): Collection
    {
        if (($this->form['parties_Id'] ?? '') === '' || $this->form['parties_Id'] === null) {
            return new Collection;
        }

        return Contact::query()
            ->where('team_id', Auth::user()?->currentTeam?->id)
            ->where('parties_Id', (int) $this->form['parties_Id'])
            ->orderByDesc('is_primary')
            ->orderBy('name')
            ->get(['Id', 'name']);
    }

    #[Computed]
    public function currencies(): Collection
    {
        return Currency::query()
            ->orderBy('name')
            ->get(['Id', 'code', 'name', 'symbol']);
    }

    #[Computed]
    public function documentStatuses(): Collection
    {
        return DocumentStatus::query()
            ->orderBy('name')
            ->get(['Id', 'code', 'name']);
    }

    #[Computed]
    public function services(): Collection
    {
        return Service::query()
            ->where('team_id', Auth::user()?->currentTeam?->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['Id', 'name', 'unit_price']);
    }

    #[Computed]
    public function templateVersions(): Collection
    {
        $enterpriseId = $this->form['enterprises_Id'] ?? null;

        if ($enterpriseId === null || $enterpriseId === '') {
            return new Collection;
        }

        return DocumentTemplateVersion::query()
            ->where('is_published', true)
            ->whereHas('documentTemplate', function (Builder $query) use ($enterpriseId): void {
                $query->where('team_id', Auth::user()?->currentTeam?->id)
                    ->where('enterprises_Id', (int) $enterpriseId)
                    ->where('document_kind', 'quotation')
                    ->where('is_active', true);
            })
            ->with('documentTemplate')
            ->orderByDesc('published_at')
            ->get(['Id', 'document_templates_Id', 'version', 'published_at']);
    }

    #[Computed]
    public function taxes(): Collection
    {
        return Tax::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['Id', 'name', 'rate']);
    }

    #[Computed]
    public function termOptions(): Collection
    {
        return QuotationSetting::query()
            ->where('team_id', $this->currentTeamId())
            ->whereNotNull('default_term')
            ->orderBy('Id')
            ->get(['Id', 'default_term']);
    }

    #[Computed]
    public function selectedQuotation(): ?Quotation
    {
        if ($this->selectedQuotationId === null) {
            return null;
        }

        return Quotation::query()
            ->with([
                'issuerEnterprise',
                'party',
                'contact',
                'currency',
                'documentStatus',
                'templateVersion.documentTemplate',
                'creator',
                'items.service',
                'items.tax',
            ])
            ->find($this->selectedQuotationId);
    }

    private function filteredQuery(): Builder
    {
        return Quotation::query()
            ->where('team_id', Auth::user()?->currentTeam?->id)
            ->with(['issuerEnterprise', 'party', 'contact', 'currency', 'documentStatus', 'templateVersion.documentTemplate', 'creator'])
            ->when($this->search !== '', function (Builder $query): void {
                $search = trim($this->search);

                $query->where(function (Builder $nestedQuery) use ($search): void {
                    $nestedQuery->where('number', 'like', "%{$search}%")
                        ->orWhereHas('party', fn (Builder $partyQuery) => $partyQuery->where('legal_name', 'like', "%{$search}%"))
                        ->orWhereHas('issuerEnterprise', fn (Builder $enterpriseQuery) => $enterpriseQuery->where('legal_name', 'like', "%{$search}%"))
                        ->orWhereHas('contact', fn (Builder $contactQuery) => $contactQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($this->statusFilter !== '', fn (Builder $query) => $query->where('document_statuses_Id', (int) $this->statusFilter))
            ->when($this->partyFilter !== '', fn (Builder $query) => $query->where('parties_Id', (int) $this->partyFilter))
            ->when($this->enterpriseFilter !== '', fn (Builder $query) => $query->where('enterprises_Id', (int) $this->enterpriseFilter));
    }

    /**
     * @return array<string, mixed>
     */
    private function quotationRules(): array
    {
        $uniqueRule = Rule::unique('quotations', 'number')
            ->where(fn ($query) => $query->where('enterprises_Id', $this->form['enterprises_Id']));

        if ($this->formMode === 'edit' && $this->selectedQuotationId !== null) {
            $uniqueRule->ignore($this->selectedQuotationId, 'Id');
        }

        return [
            'form.enterprises_Id' => ['required', 'exists:enterprises,Id'],
            'form.parties_Id' => ['required', 'exists:parties,Id'],
            'form.contacts_Id' => ['nullable', 'exists:contacts,Id'],
            'form.currencies_Id' => ['required', 'exists:currencies,Id'],
            'form.document_template_versions_Id' => ['nullable', 'exists:document_template_versions,Id'],
            'form.number' => ['required', 'string', 'max:50', $uniqueRule],
            'form.issue_date' => ['required', 'date'],
            'form.valid_until' => ['nullable', 'date', 'after_or_equal:form.issue_date'],
            'form.term' => ['nullable', 'string'],
            'form.note' => ['nullable', 'string'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function itemRules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.services_Id' => ['nullable', 'exists:services,Id'],
            'items.*.taxes_Id' => ['nullable', 'exists:taxes,Id'],
            'items.*.description' => ['required', 'string'],
            'items.*.quantity' => ['required', 'numeric', 'gt:0'],
            'items.*.unit_price' => ['required', 'numeric', 'gte:0'],
            'items.*.discount_type' => ['nullable', 'in:percent,amount'],
            'items.*.discount_rate' => ['nullable', 'numeric', 'gte:0'],
            'items.*.discount_amount' => ['nullable', 'numeric', 'gte:0'],
            'items.*.discount_description' => ['nullable', 'string', 'max:255'],
            'items.*.tax_rate' => ['nullable', 'numeric', 'gte:0'],
        ];
    }

    private function resetFormState(): void
    {
        $defaultCurrencyId = (string) (Currency::query()->orderBy('Id')->value('Id') ?? '');
        $defaultEnterpriseId = $this->defaultEnterpriseId();

        $this->selectedQuotationId = null;
        $this->form = [
            'enterprises_Id' => $defaultEnterpriseId,
            'parties_Id' => '',
            'contacts_Id' => '',
            'currencies_Id' => $defaultCurrencyId,
            'document_template_versions_Id' => '',
            'number' => $this->generateNextQuotationNumber(),
            'issue_date' => now()->toDateString(),
            'valid_until' => now()->addDays(15)->toDateString(),
            'term' => '',
            'note' => '',
        ];
        $this->items = [];
        $this->selectedServiceIds = [];
        $this->statusForm = ['document_statuses_Id' => (string) $this->createdStatusId()];

        $this->applyQuotationDefaultsForEnterprise($defaultEnterpriseId);
    }

    /**
     * @return array{
     *     services_Id: string,
     *     taxes_Id: string,
     *     description: string,
     *     quantity: float,
     *     unit_price: float,
     *     discount_type: string,
     *     discount_rate: float,
     *     discount_amount: float,
     *     discount_description: string,
     *     tax_rate: float,
     *     line_total: float
     * }
     */
    private function emptyItem(): array
    {
        return [
            'services_Id' => '',
            'taxes_Id' => '',
            'description' => '',
            'quantity' => 1,
            'unit_price' => 0,
            'discount_type' => 'percent',
            'discount_rate' => 0,
            'discount_amount' => 0,
            'discount_description' => '',
            'tax_rate' => 0,
            'line_total' => 0,
        ];
    }

    private function recalculateTotals(): void
    {
        unset($this->totals);
        $this->totals;
    }

    public function money(float|int|string $amount, ?string $currencyCode = 'COP'): string
    {
        $currencyCode ??= 'COP';

        return $currencyCode.' '.number_format((float) $amount, 2, ',', '.');
    }

    public function selectedFormCurrencyCode(): string
    {
        $currencyId = $this->form['currencies_Id'] ?? null;

        if ($currencyId === null || $currencyId === '') {
            return 'COP';
        }

        return $this->currencies
            ->firstWhere('Id', (int) $currencyId)
            ?->code ?? 'COP';
    }

    private function normalizeOptionalFields(): void
    {
        if ($this->form['contacts_Id'] === '') {
            $this->form['contacts_Id'] = null;
        }

        if ($this->form['valid_until'] === '') {
            $this->form['valid_until'] = null;
        }

        foreach ($this->items as $index => $item) {
            foreach (['services_Id', 'taxes_Id'] as $field) {
                if ($item[$field] === '') {
                    $this->items[$index][$field] = null;
                }
            }

            foreach (['discount_rate', 'tax_rate'] as $field) {
                if ($item[$field] === '') {
                    $this->items[$index][$field] = 0;
                }
            }

            if (($item['discount_amount'] ?? '') === '') {
                $this->items[$index]['discount_amount'] = 0;
            }

            if (($item['discount_description'] ?? '') === '') {
                $this->items[$index]['discount_description'] = null;
            }
        }
    }

    private function currentTeamId(): ?int
    {
        return Auth::user()?->currentTeam?->id;
    }

    private function createdStatusId(): int
    {
        return (int) (DocumentStatus::query()->where('code', 'created')->value('Id')
            ?? DocumentStatus::query()->where('code', 'draft')->value('Id'));
    }

    private function sentStatusId(): ?int
    {
        $statusId = DocumentStatus::query()->where('code', 'sent')->value('Id');

        return $statusId !== null ? (int) $statusId : null;
    }

    private function defaultEnterpriseId(): string
    {
        $enterpriseId = Enterprise::query()
            ->where('team_id', $this->currentTeamId())
            ->orderByRaw('created_by = ? desc', [Auth::id()])
            ->orderBy('legal_name')
            ->value('Id');

        return $enterpriseId !== null ? (string) $enterpriseId : '';
    }

    private function resolveDefaultContactId(?string $partyId): string
    {
        if ($partyId === null || $partyId === '') {
            return '';
        }

        $contactId = Contact::query()
            ->where('team_id', $this->currentTeamId())
            ->where('parties_Id', (int) $partyId)
            ->orderByDesc('is_primary')
            ->orderBy('name')
            ->value('Id');

        return $contactId !== null ? (string) $contactId : '';
    }

    private function applyQuotationDefaultsForEnterprise(?string $enterpriseId): void
    {
        if ($enterpriseId === null || $enterpriseId === '') {
            return;
        }

        $setting = QuotationSetting::query()
            ->where('team_id', $this->currentTeamId())
            ->where('enterprises_Id', (int) $enterpriseId)
            ->first();

        if ($setting === null) {
            return;
        }

        $this->form['document_template_versions_Id'] = (string) ($setting->document_template_versions_Id ?? '');
        $this->form['term'] = $setting->default_term ?? '';
        $this->form['note'] = $setting->default_note ?? '';
        $this->form['valid_until'] = Carbon::parse($this->form['issue_date'])
            ->addDays((int) $setting->validity_days)
            ->toDateString();
    }

    private function generateNextQuotationNumber(): string
    {
        return app(DocumentSequenceGenerator::class)->preview('quotation', $this->currentTeamId());
    }

    private function syncSelectedServices(): void
    {
        $existingItemsByService = collect($this->items)
            ->keyBy('services_Id');

        $selectedItems = collect($this->selectedServiceIds)
            ->unique()
            ->map(function (string $serviceId) use ($existingItemsByService): array {
                $existingItem = $existingItemsByService->get($serviceId);

                if (is_array($existingItem)) {
                    return $existingItem;
                }

                $service = $this->services->firstWhere('Id', (int) $serviceId);

                return [
                    'services_Id' => $serviceId,
                    'taxes_Id' => '',
                    'description' => $service?->name ?? '',
                    'quantity' => 1,
                    'unit_price' => (float) ($service?->unit_price ?? 0),
                    'discount_type' => 'percent',
                    'discount_rate' => 0,
                    'discount_amount' => 0,
                    'discount_description' => '',
                    'tax_rate' => 0,
                    'line_total' => 0,
                ];
            })
            ->values()
            ->all();

        $this->items = $selectedItems;
        $this->recalculateTotals();
    }

    private function clearSelection(): void
    {
        $this->selectedQuotationIds = [];
        $this->selectPage = false;
    }

    private function openItemsEditor(int $quotationId): void
    {
        $quotation = Quotation::query()
            ->where('team_id', $this->currentTeamId())
            ->with('items')
            ->findOrFail($quotationId);

        $this->selectedQuotationId = $quotation->Id;
        $this->items = $quotation->items
            ->sortBy('sort_order')
            ->map(fn ($item) => [
                'services_Id' => (string) ($item->services_Id ?? ''),
                'taxes_Id' => (string) ($item->taxes_Id ?? ''),
                'description' => $item->description,
                'quantity' => (float) $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'discount_type' => ((float) ($item->discount_amount ?? 0)) > 0 ? 'amount' : 'percent',
                'discount_rate' => (float) $item->discount_rate,
                'discount_amount' => (float) ($item->discount_amount ?? 0),
                'discount_description' => $item->discount_description ?? '',
                'tax_rate' => (float) $item->tax_rate,
                'line_total' => (float) $item->line_total,
            ])
            ->values()
            ->all();
        $this->selectedServiceIds = collect($this->items)
            ->pluck('services_Id')
            ->filter()
            ->values()
            ->all();
        $this->selectedTaxIds = collect($this->items)
            ->pluck('taxes_Id')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $this->recalculateTotals();
    }

    /**
     * @param  array<string, mixed>  $item
     */
    private function discountAmountForItem(array $item, float $baseAmount): float
    {
        if (($item['discount_type'] ?? 'percent') === 'amount') {
            return min(max((float) ($item['discount_amount'] ?? 0), 0), $baseAmount);
        }

        return $baseAmount * (max((float) ($item['discount_rate'] ?? 0), 0) / 100);
    }
}; 

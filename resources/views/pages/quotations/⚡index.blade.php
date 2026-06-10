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
     *     discount_rate: float|int|string,
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
     * @var array{document_statuses_Id: string}
     */
    public array $statusForm = [
        'document_statuses_Id' => '',
    ];

    public function mount(): void
    {
        $this->resetFormState();
    }

    public function updated(string $property): void
    {
        if (in_array($property, ['search', 'statusFilter', 'partyFilter', 'enterpriseFilter', 'perPage'], true)) {
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

    public function openItemsModal(int $quotationId): void
    {
        $quotation = Quotation::query()
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
                'discount_rate' => (float) $item->discount_rate,
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

        $this->recalculateTotals();
        Flux::modal('quotation-items')->show();
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
                'document_statuses_Id' => $statusId,
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
                $payload['created_by'] = Auth::id();
                $quotation = Quotation::query()->create($payload);
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
                $discountAmount = $baseAmount * ((float) $item['discount_rate'] / 100);
                $taxableAmount = $baseAmount - $discountAmount;
                $taxAmount = $taxableAmount * ((float) $item['tax_rate'] / 100);

                $quotation->items()->create([
                    'services_Id' => filled($item['services_Id']) ? (int) $item['services_Id'] : null,
                    'plans_Id' => null,
                    'taxes_Id' => filled($item['taxes_Id']) ? (int) $item['taxes_Id'] : null,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount_rate' => $item['discount_rate'],
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
            $discountRate = max((float) ($item['discount_rate'] ?: 0), 0);
            $taxRate = max((float) ($item['tax_rate'] ?: 0), 0);

            $baseAmount = $quantity * $unitPrice;
            $discountAmount = $baseAmount * ($discountRate / 100);
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
            'items.*.discount_rate' => ['nullable', 'numeric', 'gte:0'],
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
     *     discount_rate: float,
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
            'discount_rate' => 0,
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
        $prefix = strtoupper(trim(Auth::user()?->initials() ?? 'CT')).'-';
        $lastValue = Quotation::query()
            ->where('team_id', $this->currentTeamId())
            ->where('number', 'like', $prefix.'%')
            ->pluck('number')
            ->map(function (string $number) use ($prefix): int {
                return (int) str($number)->after($prefix)->toString();
            })
            ->max() ?? 0;

        return $prefix.str_pad((string) ($lastValue + 1), 4, '0', STR_PAD_LEFT);
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
                    'discount_rate' => 0,
                    'tax_rate' => 0,
                    'line_total' => 0,
                ];
            })
            ->values()
            ->all();

        $this->items = $selectedItems;
        $this->recalculateTotals();
    }
}; ?>

<section class="mx-auto flex w-full max-w-7xl flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-[28px] border border-slate-200/80 bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.12),_transparent_30%),linear-gradient(135deg,_rgba(255,255,255,0.94),_rgba(241,245,249,0.9))] p-6 shadow-[0_35px_80px_-48px_rgba(15,23,42,0.45)] dark:border-cyan-500/20 dark:bg-[radial-gradient(circle_at_top_left,_rgba(34,211,238,0.14),_transparent_28%),linear-gradient(145deg,_rgba(15,23,42,0.92),_rgba(2,6,23,0.9))]">
            <div class="absolute -right-16 top-0 h-40 w-40 rounded-full bg-cyan-300/20 blur-3xl dark:bg-cyan-400/20"></div>
            <div class="absolute bottom-0 left-1/3 h-32 w-32 rounded-full bg-sky-400/20 blur-3xl dark:bg-sky-500/20"></div>

            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl space-y-3">
                    <div class="inline-flex items-center gap-2 rounded-full border border-cyan-300/60 bg-cyan-50/90 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-cyan-700 dark:border-cyan-400/20 dark:bg-cyan-400/10 dark:text-cyan-200">
                        <flux:icon name="document-text" class="size-4" />
                        Centro de cotizaciones
                    </div>

                    <div class="space-y-2">
                        <h1 class="text-3xl font-semibold tracking-tight text-slate-950 dark:text-white sm:text-4xl">
                            Cotizaciones en formato dashboard
                        </h1>
                        <p class="max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300 sm:text-base">
                            Administra el pipeline comercial desde un solo panel: listado ordenable, filtros operativos y modales para crear, consultar y editar sin salir del contexto.
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <flux:button variant="filled" icon="arrow-path" wire:click="$refresh">
                        Actualizar
                    </flux:button>

                    <flux:button variant="primary" icon="plus" wire:click="startCreate" data-test="quotation-create-button">
                        Nueva cotización
                    </flux:button>
                </div>
            </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-[24px] border border-slate-200 bg-white/90 p-5 shadow-[0_20px_45px_-30px_rgba(15,23,42,0.45)] dark:border-cyan-500/20 dark:bg-slate-900/75">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Totales</p>
                        <p class="mt-1 text-2xl font-semibold text-slate-950 dark:text-white">{{ $this->stats['total'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-sky-100 p-3 text-sky-700 dark:bg-sky-500/10 dark:text-sky-300">
                        <flux:icon name="queue-list" class="size-6" />
                    </div>
                </div>
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Cotizaciones visibles según los filtros activos.</p>
            </div>

            <div class="rounded-[24px] border border-slate-200 bg-white/90 p-5 shadow-[0_20px_45px_-30px_rgba(15,23,42,0.45)] dark:border-cyan-500/20 dark:bg-slate-900/75">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Mes actual</p>
                        <p class="mt-1 text-2xl font-semibold text-slate-950 dark:text-white">{{ $this->stats['this_month'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-emerald-100 p-3 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                        <flux:icon name="calendar-days" class="size-6" />
                    </div>
                </div>
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Emitidas entre el inicio y el cierre del mes.</p>
            </div>

            <div class="rounded-[24px] border border-slate-200 bg-white/90 p-5 shadow-[0_20px_45px_-30px_rgba(15,23,42,0.45)] dark:border-cyan-500/20 dark:bg-slate-900/75">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Por vencer</p>
                        <p class="mt-1 text-2xl font-semibold text-slate-950 dark:text-white">{{ $this->stats['expiring_soon'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-amber-100 p-3 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300">
                        <flux:icon name="clock" class="size-6" />
                    </div>
                </div>
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Con vigencia que termina en los próximos 7 días.</p>
            </div>

            <div class="rounded-[24px] border border-slate-200 bg-white/90 p-5 shadow-[0_20px_45px_-30px_rgba(15,23,42,0.45)] dark:border-cyan-500/20 dark:bg-slate-900/75">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Clientes visibles</p>
                        <p class="mt-1 text-2xl font-semibold text-slate-950 dark:text-white">{{ $this->stats['customers'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-fuchsia-100 p-3 text-fuchsia-700 dark:bg-fuchsia-500/10 dark:text-fuchsia-300">
                        <flux:icon name="users" class="size-6" />
                    </div>
                </div>
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Cantidad de clientes distintos dentro del conjunto filtrado.</p>
            </div>
            </div>

            <div class="rounded-[28px] border border-slate-200 bg-white/90 shadow-[0_30px_70px_-45px_rgba(15,23,42,0.45)] dark:border-cyan-500/20 dark:bg-slate-900/75">
            <div class="border-b border-slate-200/80 p-5 dark:border-slate-800">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-slate-950 dark:text-white">Listado de cotizaciones</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Grid filtrable, paginable y ordenable para la operación comercial diaria.</p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <flux:select wire:model.live="perPage" size="sm">
                                <flux:select.option value="8">8 por página</flux:select.option>
                                <flux:select.option value="12">12 por página</flux:select.option>
                                <flux:select.option value="24">24 por página</flux:select.option>
                            </flux:select>
                        </div>
                    </div>

                    <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-5">
                        <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Buscar por número, cliente o empresa" />

                        <flux:select wire:model.live="statusFilter">
                            <flux:select.option value="">Todos los estados</flux:select.option>
                            @foreach ($this->documentStatuses as $status)
                                <flux:select.option value="{{ $status->Id }}">{{ $status->name }}</flux:select.option>
                            @endforeach
                        </flux:select>

                        <flux:select wire:model.live="enterpriseFilter">
                            <flux:select.option value="">Todas las empresas</flux:select.option>
                            @foreach ($this->enterprises as $enterprise)
                                <flux:select.option value="{{ $enterprise->Id }}">{{ $enterprise->legal_name }}</flux:select.option>
                            @endforeach
                        </flux:select>

                        <flux:select wire:model.live="partyFilter">
                            <flux:select.option value="">Todos los clientes</flux:select.option>
                            @foreach ($this->parties as $party)
                                <flux:select.option value="{{ $party->Id }}">{{ $party->legal_name }}</flux:select.option>
                            @endforeach
                        </flux:select>

                        <flux:button
                            variant="ghost"
                            icon="x-mark"
                            wire:click="clearFilters"
                        >
                            Limpiar filtros
                        </flux:button>
                    </div>
                </div>
            </div>

            <div class="hidden border-b border-slate-200/80 px-5 py-4 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:border-slate-800 dark:text-slate-400 xl:grid xl:grid-cols-[1.2fr_1.5fr_1.5fr_1fr_1fr_1fr_auto] xl:gap-4">
                <button type="button" class="flex items-center gap-2 text-left" wire:click="sort('number')">
                    Número
                    <flux:icon name="{{ $sortBy === 'number' && $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="size-3" />
                </button>
                <button type="button" class="flex items-center gap-2 text-left" wire:click="sort('party')">
                    Cliente
                    <flux:icon name="{{ $sortBy === 'party' && $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="size-3" />
                </button>
                <button type="button" class="flex items-center gap-2 text-left" wire:click="sort('enterprise')">
                    Empresa
                    <flux:icon name="{{ $sortBy === 'enterprise' && $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="size-3" />
                </button>
                <button type="button" class="flex items-center gap-2 text-left" wire:click="sort('issue_date')">
                    Emisión
                    <flux:icon name="{{ $sortBy === 'issue_date' && $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="size-3" />
                </button>
                <button type="button" class="flex items-center gap-2 text-left" wire:click="sort('total')">
                    Total
                    <flux:icon name="{{ $sortBy === 'total' && $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="size-3" />
                </button>
                <div>Estado</div>
                <div class="text-right">Acciones</div>
            </div>

            <div class="p-5">
                @if ($this->quotations->count() === 0)
                    <div class="rounded-[24px] border border-dashed border-slate-300 bg-slate-50/90 px-6 py-16 text-center dark:border-slate-700 dark:bg-slate-950/60">
                        <div class="mx-auto flex size-16 items-center justify-center rounded-3xl bg-cyan-100 text-cyan-700 dark:bg-cyan-500/10 dark:text-cyan-300">
                            <flux:icon name="document-plus" class="size-8" />
                        </div>
                        <h3 class="mt-5 text-lg font-semibold text-slate-950 dark:text-white">No hay cotizaciones para mostrar</h3>
                        <p class="mx-auto mt-2 max-w-xl text-sm text-slate-500 dark:text-slate-400">
                            Ajusta los filtros o crea una nueva cotización desde este mismo módulo para empezar a poblar el dashboard comercial.
                        </p>
                    </div>
                @else
                    <div class="grid gap-4 xl:gap-0">
                        @foreach ($this->quotations as $quotation)
                            <article
                                wire:key="quotation-card-{{ $quotation->Id }}"
                                class="grid gap-4 rounded-[24px] border border-slate-200 bg-white px-5 py-4 shadow-[0_24px_60px_-42px_rgba(15,23,42,0.42)] transition hover:border-cyan-300 hover:shadow-[0_28px_70px_-42px_rgba(14,165,233,0.35)] dark:border-slate-800 dark:bg-slate-950/60 dark:hover:border-cyan-500/30 xl:grid-cols-[1.2fr_1.5fr_1.5fr_1fr_1fr_1fr_auto] xl:items-center xl:rounded-none xl:border-x-0 xl:border-t-0 xl:px-0 xl:py-5 xl:shadow-none"
                            >
                                <div class="space-y-1">
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 xl:hidden">Número</p>
                                    <div class="font-semibold text-slate-950 dark:text-white">{{ $quotation->number }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $quotation->contact?->name ?? 'Sin contacto asignado' }}
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 xl:hidden">Cliente</p>
                                    <div class="font-medium text-slate-900 dark:text-slate-100">{{ $quotation->party?->legal_name }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $quotation->party?->email ?? 'Sin correo' }}
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 xl:hidden">Empresa</p>
                                    <div class="font-medium text-slate-900 dark:text-slate-100">{{ $quotation->issuerEnterprise?->legal_name }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $quotation->currency?->code ?? 'Moneda' }}
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 xl:hidden">Emisión</p>
                                    <div class="font-medium text-slate-900 dark:text-slate-100">{{ $quotation->issue_date?->format('Y-m-d') }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">
                                        Vigencia: {{ $quotation->valid_until?->format('Y-m-d') ?? 'Abierta' }}
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 xl:hidden">Total</p>
                                    <div class="font-semibold text-slate-950 dark:text-white">
                                        {{ $this->money((float) $quotation->total, $quotation->currency?->code ?? 'COP') }}
                                    </div>
                                </div>

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 xl:hidden">Estado</p>
                                    @php
                                        $statusCode = $quotation->documentStatus?->code;
                                        $badgeClass = match ($statusCode) {
                                            'approved' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300',
                                            'sent' => 'bg-sky-100 text-sky-700 dark:bg-sky-500/10 dark:text-sky-300',
                                            'rejected', 'cancelled' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300',
                                            'expired' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300',
                                            default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
                                        };
                                    @endphp
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                                        {{ $quotation->documentStatus?->name ?? 'Sin estado' }}
                                    </span>
                                </div>

                                <div class="flex flex-wrap items-center justify-end gap-2">
                                    <flux:tooltip content="Configurar servicios">
                                        <flux:button
                                            variant="ghost"
                                            size="sm"
                                            icon="list-bullet"
                                            wire:click="openItemsModal({{ $quotation->Id }})"
                                            data-test="quotation-items-button"
                                        />
                                    </flux:tooltip>

                                    <flux:tooltip content="Actualizar estado">
                                        <flux:button
                                            variant="ghost"
                                            size="sm"
                                            icon="flag"
                                            wire:click="openStatusModal({{ $quotation->Id }})"
                                            data-test="quotation-status-button"
                                        />
                                    </flux:tooltip>

                                    <flux:tooltip content="Ver detalle">
                                        <flux:button
                                            variant="ghost"
                                            size="sm"
                                            icon="eye"
                                            wire:click="openShowModal({{ $quotation->Id }})"
                                            data-test="quotation-view-button"
                                        />
                                    </flux:tooltip>

                                    <flux:tooltip content="Editar cotización">
                                        <flux:button
                                            variant="ghost"
                                            size="sm"
                                            icon="pencil-square"
                                            wire:click="openEditModal({{ $quotation->Id }})"
                                            data-test="quotation-edit-button"
                                        />
                                    </flux:tooltip>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>

            @if ($this->quotations->hasPages())
                <div class="border-t border-slate-200/80 px-5 py-4 dark:border-slate-800">
                    {{ $this->quotations->links() }}
                </div>
            @endif
        </div>

    <flux:modal name="quotation-form" :show="$errors->isNotEmpty()" focusable class="w-full max-w-4xl">
        <form wire:submit="saveQuotation" class="space-y-5">
            <div class="flex flex-col gap-2 border-b border-slate-200 pb-4 dark:border-slate-800">
                <flux:heading size="lg">
                    {{ $formMode === 'edit' ? 'Editar encabezado' : 'Nueva cotización' }}
                </flux:heading>
                <flux:subheading>
                    Crea o ajusta el encabezado. Los servicios, descuentos e impuestos se gestionan desde el panel de la cotización.
                </flux:subheading>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <div class="rounded-[20px] border border-slate-200 bg-slate-50/80 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Emisor</div>
                    <div class="mt-2 font-semibold text-slate-950 dark:text-white">{{ auth()->user()?->name }}</div>
                    <div class="text-sm text-slate-500 dark:text-slate-400">{{ auth()->user()?->email }}</div>
                </div>

                <div class="rounded-[20px] border border-cyan-300 bg-cyan-50/80 p-4 dark:border-cyan-500/30 dark:bg-cyan-500/10">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-cyan-700 dark:text-cyan-300">Estado inicial</div>
                    <div class="mt-2 font-semibold text-slate-950 dark:text-white">
                        {{ $this->documentStatuses->firstWhere('code', 'created')?->name ?? 'Created' }}
                    </div>
                    <div class="text-sm text-cyan-800/80 dark:text-cyan-200/80">El cambio de estado se realiza desde el panel.</div>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <flux:select wire:model.live="form.enterprises_Id" label="Empresa emisora" required>
                    <flux:select.option value="">Selecciona una empresa</flux:select.option>
                    @foreach ($this->enterprises as $enterprise)
                        <flux:select.option value="{{ $enterprise->Id }}">{{ $enterprise->legal_name }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:select wire:model.live="form.parties_Id" label="Cliente" required>
                    <flux:select.option value="">Selecciona un cliente</flux:select.option>
                    @foreach ($this->parties as $party)
                        <flux:select.option value="{{ $party->Id }}">{{ $party->legal_name }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:select wire:model.live="form.contacts_Id" label="Contacto principal">
                    <flux:select.option value="">Sin contacto</flux:select.option>
                    @foreach ($this->contacts as $contact)
                        <flux:select.option value="{{ $contact->Id }}">{{ $contact->name }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:select wire:model="form.document_template_versions_Id" label="Plantilla de cotización">
                    <flux:select.option value="">Sin plantilla</flux:select.option>
                    @foreach ($this->templateVersions as $templateVersion)
                        <flux:select.option value="{{ $templateVersion->Id }}">
                            {{ $templateVersion->documentTemplate?->name }} · v{{ $templateVersion->version }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:input wire:model="form.number" label="Consecutivo" readonly />
                <flux:input wire:model="form.issue_date" type="date" label="Fecha de emisión" required />
                <flux:input wire:model="form.valid_until" type="date" label="Válida hasta" />

                <flux:select wire:model="form.currencies_Id" label="Moneda" required>
                    @foreach ($this->currencies as $currency)
                        <flux:select.option value="{{ $currency->Id }}">{{ $currency->code }} - {{ $currency->name }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:textarea wire:model="form.term" label="Términos y condiciones" rows="4" class="md:col-span-2" />
                <flux:textarea wire:model="form.note" label="Notas internas" rows="3" class="md:col-span-2" />
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-200 pt-4 dark:border-slate-800">
                <flux:modal.close>
                    <flux:button variant="filled">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary" data-test="quotation-save-button">
                    {{ $formMode === 'edit' ? 'Guardar cambios' : 'Crear cotización' }}
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="quotation-items" class="w-full max-w-6xl">
        <form wire:submit="saveItems" class="space-y-5">
            <div class="flex flex-col gap-2 border-b border-slate-200 pb-4 dark:border-slate-800">
                <flux:heading size="lg">Servicios de la cotización</flux:heading>
                <flux:subheading>Selecciona servicios desde el checklist y ajusta cantidades, impuestos y descuentos.</flux:subheading>
            </div>

            <div class="grid gap-5 xl:grid-cols-[0.95fr_1.6fr]">
                <div class="rounded-[24px] border border-slate-200 bg-slate-50/80 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                    <div class="mb-4 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Checklist de servicios</div>
                    <div class="space-y-3">
                        @foreach ($this->services as $service)
                            <label wire:key="service-check-{{ $service->Id }}" class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-3 text-sm dark:border-slate-800 dark:bg-slate-900/70">
                                <flux:checkbox wire:model.live="selectedServiceIds" value="{{ (string) $service->Id }}" />
                                <div class="min-w-0">
                                    <div class="font-medium text-slate-950 dark:text-white">{{ $service->name }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ $this->money((float) $service->unit_price, $this->selectedFormCurrencyCode()) }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse ($items as $index => $item)
                        <div wire:key="quotation-item-service-{{ $item['services_Id'] ?: $index }}" class="rounded-[22px] border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900/70">
                            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-[1.4fr_repeat(5,minmax(0,0.75fr))]">
                                <flux:input wire:model.live="items.{{ $index }}.description" label="Descripción" required />
                                <flux:input wire:model.live="items.{{ $index }}.quantity" type="number" step="0.01" min="0" label="Cantidad" />
                                <flux:input wire:model.live="items.{{ $index }}.unit_price" type="number" step="0.01" min="0" label="Vr. unitario" />
                                <flux:input wire:model.live="items.{{ $index }}.discount_rate" type="number" step="0.01" min="0" label="% Desc." />
                                <flux:select wire:model.live="items.{{ $index }}.taxes_Id" label="Impuesto">
                                    <flux:select.option value="">Sin impuesto</flux:select.option>
                                    @foreach ($this->taxes as $tax)
                                        <flux:select.option value="{{ $tax->Id }}">{{ $tax->name }}</flux:select.option>
                                    @endforeach
                                </flux:select>
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-800/80">
                                    <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-400">Total línea</div>
                                    <div class="mt-1 font-semibold text-slate-950 dark:text-white">
                                        {{ $this->money((float) $item['line_total'], $this->selectedFormCurrencyCode()) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-[22px] border border-dashed border-slate-300 bg-slate-50/80 px-6 py-10 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-950/50 dark:text-slate-400">
                            Selecciona al menos un servicio para construir la cotización.
                        </div>
                    @endforelse

                    <div class="grid gap-3 md:grid-cols-4">
                        <div class="rounded-[18px] border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/50">
                            <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-400">Subtotal</div>
                            <div class="mt-1 font-semibold text-slate-950 dark:text-white">{{ $this->money($this->totals['subtotal'], $this->selectedFormCurrencyCode()) }}</div>
                        </div>
                        <div class="rounded-[18px] border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/50">
                            <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-400">Descuentos</div>
                            <div class="mt-1 font-semibold text-slate-950 dark:text-white">{{ $this->money($this->totals['discount_total'], $this->selectedFormCurrencyCode()) }}</div>
                        </div>
                        <div class="rounded-[18px] border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/50">
                            <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-400">Impuestos</div>
                            <div class="mt-1 font-semibold text-slate-950 dark:text-white">{{ $this->money($this->totals['tax_total'], $this->selectedFormCurrencyCode()) }}</div>
                        </div>
                        <div class="rounded-[18px] border border-cyan-300 bg-cyan-50 px-4 py-3 dark:border-cyan-500/30 dark:bg-cyan-500/10">
                            <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-cyan-700 dark:text-cyan-300">Total</div>
                            <div class="mt-1 font-semibold text-slate-950 dark:text-white">{{ $this->money($this->totals['total'], $this->selectedFormCurrencyCode()) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-200 pt-4 dark:border-slate-800">
                <flux:modal.close>
                    <flux:button variant="filled">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary">Guardar servicios</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="quotation-status" class="w-full max-w-lg">
        <form wire:submit="updateStatus" class="space-y-5">
            <div>
                <flux:heading size="lg">Actualizar estado</flux:heading>
                <flux:subheading>Cambia el estado comercial de la cotización desde el panel.</flux:subheading>
            </div>

            <flux:select wire:model="statusForm.document_statuses_Id" label="Estado" required>
                @foreach ($this->documentStatuses as $status)
                    <flux:select.option value="{{ $status->Id }}">{{ $status->name }}</flux:select.option>
                @endforeach
            </flux:select>

            <div class="flex justify-end gap-3 border-t border-slate-200 pt-4 dark:border-slate-800">
                <flux:modal.close>
                    <flux:button variant="filled">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary">Guardar estado</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="quotation-details" class="w-full max-w-5xl">
        <div class="space-y-6">
            @if ($this->selectedQuotation)
                <div class="flex flex-col gap-4 border-b border-slate-200 pb-4 dark:border-slate-800 md:flex-row md:items-start md:justify-between">
                    <div>
                        <flux:heading size="lg">Cotización {{ $this->selectedQuotation->number }}</flux:heading>
                        <flux:subheading>
                            {{ $this->selectedQuotation->party?->legal_name }} · {{ $this->selectedQuotation->issuerEnterprise?->legal_name }}
                        </flux:subheading>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                            {{ $this->selectedQuotation->documentStatus?->name }}
                        </span>

                        <flux:button
                            variant="primary"
                            icon="pencil-square"
                            wire:click="openEditModal({{ $this->selectedQuotation->Id }})"
                        >
                            Editar
                        </flux:button>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-[20px] border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/50">
                        <div class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Emisión</div>
                        <div class="mt-2 font-medium text-slate-950 dark:text-white">{{ $this->selectedQuotation->issue_date?->format('Y-m-d') }}</div>
                    </div>
                    <div class="rounded-[20px] border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/50">
                        <div class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Vigencia</div>
                        <div class="mt-2 font-medium text-slate-950 dark:text-white">{{ $this->selectedQuotation->valid_until?->format('Y-m-d') ?? 'Sin fecha límite' }}</div>
                    </div>
                    <div class="rounded-[20px] border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/50">
                        <div class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Moneda</div>
                        <div class="mt-2 font-medium text-slate-950 dark:text-white">{{ $this->selectedQuotation->currency?->code }}</div>
                    </div>
                    <div class="rounded-[20px] border border-cyan-300 bg-cyan-50 p-4 dark:border-cyan-500/30 dark:bg-cyan-500/10">
                        <div class="text-xs font-semibold uppercase tracking-[0.16em] text-cyan-700 dark:text-cyan-300">Total</div>
                        <div class="mt-2 font-semibold text-slate-950 dark:text-white">
                            {{ $this->money((float) $this->selectedQuotation->total, $this->selectedQuotation->currency?->code ?? 'COP') }}
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="rounded-[24px] border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/50">
                        <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Encabezado</h3>
                        <dl class="mt-4 space-y-3 text-sm">
                            <div class="flex justify-between gap-4">
                                <dt class="text-slate-500 dark:text-slate-400">Cliente</dt>
                                <dd class="font-medium text-slate-950 dark:text-white">{{ $this->selectedQuotation->party?->legal_name }}</dd>
                            </div>
                            <div class="flex justify-between gap-4">
                                <dt class="text-slate-500 dark:text-slate-400">Contacto</dt>
                                <dd class="font-medium text-slate-950 dark:text-white">{{ $this->selectedQuotation->contact?->name ?? 'Sin contacto' }}</dd>
                            </div>
                            <div class="flex justify-between gap-4">
                                <dt class="text-slate-500 dark:text-slate-400">Empresa</dt>
                                <dd class="font-medium text-slate-950 dark:text-white">{{ $this->selectedQuotation->issuerEnterprise?->legal_name }}</dd>
                            </div>
                            <div class="flex justify-between gap-4">
                                <dt class="text-slate-500 dark:text-slate-400">Emisor</dt>
                                <dd class="font-medium text-slate-950 dark:text-white">{{ $this->selectedQuotation->creator?->name ?? 'Sin emisor' }}</dd>
                            </div>
                            <div class="flex justify-between gap-4">
                                <dt class="text-slate-500 dark:text-slate-400">Plantilla</dt>
                                <dd class="font-medium text-slate-950 dark:text-white">
                                    {{ $this->selectedQuotation->templateVersion?->documentTemplate?->name ? $this->selectedQuotation->templateVersion->documentTemplate->name.' v'.$this->selectedQuotation->templateVersion->version : 'Sin plantilla' }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div class="rounded-[24px] border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/50">
                        <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Notas</h3>
                        <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                            <p><span class="font-semibold text-slate-900 dark:text-white">Términos:</span> {{ $this->selectedQuotation->term ?: 'Sin términos registrados.' }}</p>
                            <p><span class="font-semibold text-slate-900 dark:text-white">Nota:</span> {{ $this->selectedQuotation->note ?: 'Sin notas registradas.' }}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Ítems</h3>

                    @foreach ($this->selectedQuotation->items->sortBy('sort_order') as $item)
                        <div class="grid gap-3 rounded-[20px] border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/50 md:grid-cols-[2fr_repeat(4,minmax(0,1fr))]">
                            <div>
                                <div class="font-medium text-slate-950 dark:text-white">{{ $item->description }}</div>
                                <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                    {{ $item->service?->name ?? $item->plan?->name ?? 'Línea manual' }}
                                </div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Cantidad</div>
                                <div class="mt-1 text-sm font-medium text-slate-950 dark:text-white">{{ number_format((float) $item->quantity, 2, ',', '.') }}</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Vr. unitario</div>
                                <div class="mt-1 text-sm font-medium text-slate-950 dark:text-white">{{ $this->money((float) $item->unit_price, $this->selectedQuotation->currency?->code ?? 'COP') }}</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Impuesto</div>
                                <div class="mt-1 text-sm font-medium text-slate-950 dark:text-white">{{ number_format((float) $item->tax_rate, 2, ',', '.') }}%</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Total línea</div>
                                <div class="mt-1 text-sm font-semibold text-slate-950 dark:text-white">{{ $this->money((float) $item->line_total, $this->selectedQuotation->currency?->code ?? 'COP') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">
                    No fue posible cargar el detalle de la cotización.
                </div>
            @endif
        </div>
    </flux:modal>
</section>

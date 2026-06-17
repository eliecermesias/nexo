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

            <div class="rounded-[24px] border border-slate-200 bg-white/90 dark:border-cyan-500/20 dark:bg-slate-900/75">
                <div class="flex items-center justify-between gap-3 px-5 py-4">
                    <div>
                        <h2 class="text-base font-semibold text-slate-950 dark:text-white">Resumen comercial</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Indicadores del conjunto filtrado.</p>
                    </div>
                    <flux:button
                        variant="ghost"
                        size="sm"
                        icon="{{ $showSummaryPanel ? 'chevron-up' : 'chevron-down' }}"
                        wire:click="$toggle('showSummaryPanel')"
                    >
                        {{ $showSummaryPanel ? 'Ocultar' : 'Mostrar' }}
                    </flux:button>
                </div>

                @if ($showSummaryPanel)
                    <div class="grid gap-4 border-t border-slate-200 p-5 dark:border-slate-800 md:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-[18px] border border-slate-200 bg-slate-50/90 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Totales</p>
                                    <p class="mt-1 text-2xl font-semibold text-slate-950 dark:text-white">{{ $this->stats['total'] }}</p>
                                </div>
                                <div class="rounded-2xl bg-sky-100 p-3 text-sky-700 dark:bg-sky-500/10 dark:text-sky-300">
                                    <flux:icon name="queue-list" class="size-6" />
                                </div>
                            </div>
                        </div>

                        <div class="rounded-[18px] border border-slate-200 bg-slate-50/90 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Mes actual</p>
                                    <p class="mt-1 text-2xl font-semibold text-slate-950 dark:text-white">{{ $this->stats['this_month'] }}</p>
                                </div>
                                <div class="rounded-2xl bg-emerald-100 p-3 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                                    <flux:icon name="calendar-days" class="size-6" />
                                </div>
                            </div>
                        </div>

                        <div class="rounded-[18px] border border-slate-200 bg-slate-50/90 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Por vencer</p>
                                    <p class="mt-1 text-2xl font-semibold text-slate-950 dark:text-white">{{ $this->stats['expiring_soon'] }}</p>
                                </div>
                                <div class="rounded-2xl bg-amber-100 p-3 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300">
                                    <flux:icon name="clock" class="size-6" />
                                </div>
                            </div>
                        </div>

                        <div class="rounded-[18px] border border-slate-200 bg-slate-50/90 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Clientes visibles</p>
                                    <p class="mt-1 text-2xl font-semibold text-slate-950 dark:text-white">{{ $this->stats['customers'] }}</p>
                                </div>
                                <div class="rounded-2xl bg-fuchsia-100 p-3 text-fuchsia-700 dark:bg-fuchsia-500/10 dark:text-fuchsia-300">
                                    <flux:icon name="users" class="size-6" />
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
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

            <div class="border-b border-slate-200/80 px-5 py-4 dark:border-slate-800">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div class="text-sm font-medium text-slate-600 dark:text-slate-300">
                        {{ count($selectedQuotationIds) }} seleccionadas
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <flux:button variant="filled" size="sm" icon="flag" wire:click="openBulkStatusModal" :disabled="count($selectedQuotationIds) === 0">
                            Cambiar estado
                        </flux:button>

                        <flux:button
                            variant="filled"
                            size="sm"
                            icon="paper-airplane"
                            data-nexo-confirm
                            data-nexo-title="Enviar cotizaciones"
                            data-nexo-text="Se marcarán como enviadas las cotizaciones seleccionadas."
                            data-nexo-confirm-text="Enviar"
                            data-nexo-method="sendSelectedQuotations"
                            :disabled="count($selectedQuotationIds) === 0"
                        >
                            Enviar
                        </flux:button>

                        <flux:button
                            variant="danger"
                            size="sm"
                            icon="trash"
                            data-nexo-confirm
                            data-nexo-title="Eliminar cotizaciones"
                            data-nexo-text="Esta acción eliminará las cotizaciones seleccionadas y sus detalles."
                            data-nexo-confirm-text="Eliminar"
                            data-nexo-method="deleteSelectedQuotations"
                            :disabled="count($selectedQuotationIds) === 0"
                        >
                            Eliminar
                        </flux:button>
                    </div>
                </div>
            </div>

            <div>
                @if ($this->quotations->count() === 0)
                    <div class="m-5 rounded-[24px] border border-dashed border-slate-300 bg-slate-50/90 px-6 py-16 text-center dark:border-slate-700 dark:bg-slate-950/60">
                        <div class="mx-auto flex size-16 items-center justify-center rounded-3xl bg-cyan-100 text-cyan-700 dark:bg-cyan-500/10 dark:text-cyan-300">
                            <flux:icon name="document-plus" class="size-8" />
                        </div>
                        <h3 class="mt-5 text-lg font-semibold text-slate-950 dark:text-white">No hay cotizaciones para mostrar</h3>
                        <p class="mx-auto mt-2 max-w-xl text-sm text-slate-500 dark:text-slate-400">
                            Ajusta los filtros o crea una nueva cotización desde este mismo módulo para empezar a poblar el dashboard comercial.
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="nexo-table w-full min-w-[78rem] text-left text-sm">
                            <thead>
                                <tr>
                                    <th scope="col" class="w-12">
                                        <flux:checkbox wire:model.live="selectPage" aria-label="Seleccionar página" />
                                    </th>
                                    <th scope="col">
                                        <button type="button" class="flex items-center gap-2" wire:click="sort('number')">
                                            Número
                                            <flux:icon name="{{ $sortBy === 'number' && $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="size-3" />
                                        </button>
                                    </th>
                                    <th scope="col">
                                        <button type="button" class="flex items-center gap-2" wire:click="sort('party')">
                                            Cliente
                                            <flux:icon name="{{ $sortBy === 'party' && $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="size-3" />
                                        </button>
                                    </th>
                                    <th scope="col">
                                        <button type="button" class="flex items-center gap-2" wire:click="sort('enterprise')">
                                            Empresa
                                            <flux:icon name="{{ $sortBy === 'enterprise' && $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="size-3" />
                                        </button>
                                    </th>
                                    <th scope="col">
                                        <button type="button" class="flex items-center gap-2" wire:click="sort('issue_date')">
                                            Emisión
                                            <flux:icon name="{{ $sortBy === 'issue_date' && $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="size-3" />
                                        </button>
                                    </th>
                                    <th scope="col">
                                        <button type="button" class="flex items-center gap-2" wire:click="sort('total')">
                                            Total
                                            <flux:icon name="{{ $sortBy === 'total' && $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="size-3" />
                                        </button>
                                    </th>
                                    <th scope="col">Estado</th>
                                    <th scope="col" class="text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($this->quotations as $quotation)
                                    <tr wire:key="quotation-row-{{ $quotation->Id }}">
                                        <td>
                                            <flux:checkbox wire:model.live="selectedQuotationIds" value="{{ (string) $quotation->Id }}" aria-label="Seleccionar cotización {{ $quotation->number }}" />
                                        </td>
                                        <td>
                                            <button type="button" class="text-left" wire:click="openShowModal({{ $quotation->Id }})">
                                                <span class="nexo-table-primary block font-semibold">{{ $quotation->number }}</span>
                                                <span class="nexo-table-secondary mt-1 block text-xs">{{ $quotation->contact?->name ?? 'Sin contacto asignado' }}</span>
                                            </button>
                                        </td>
                                        <td>
                                            <p class="nexo-table-primary font-medium">{{ $quotation->party?->legal_name }}</p>
                                            <p class="nexo-table-secondary mt-1 text-xs">{{ $quotation->party?->email ?? 'Sin correo' }}</p>
                                        </td>
                                        <td>
                                            <p class="nexo-table-primary font-medium">{{ $quotation->issuerEnterprise?->legal_name }}</p>
                                            <p class="nexo-table-secondary mt-1 text-xs">{{ $quotation->currency?->code ?? 'Moneda' }}</p>
                                        </td>
                                        <td>
                                            <p class="nexo-table-primary font-medium">{{ $quotation->issue_date?->format('Y-m-d') }}</p>
                                            <p class="nexo-table-secondary mt-1 text-xs">Vigencia: {{ $quotation->valid_until?->format('Y-m-d') ?? 'Abierta' }}</p>
                                        </td>
                                        <td>
                                            <p class="nexo-table-primary font-semibold">{{ $this->money((float) $quotation->total, $quotation->currency?->code ?? 'COP') }}</p>
                                        </td>
                                        <td>
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
                                        </td>
                                        <td>
                                            <div class="flex items-center justify-end gap-2">
                                                <flux:tooltip content="Adjuntar detalles" position="top">
                                                    <flux:button variant="ghost" size="sm" icon="list-bullet" wire:click="openItemsModal({{ $quotation->Id }})" data-test="quotation-items-button" />
                                                </flux:tooltip>
                                                <flux:tooltip content="Registrar descuentos" position="top">
                                                    <flux:button variant="ghost" size="sm" icon="receipt-percent" wire:click="openDiscountsModal({{ $quotation->Id }})" data-test="quotation-discounts-button" />
                                                </flux:tooltip>
                                                <flux:tooltip content="Asociar impuestos" position="top">
                                                    <flux:button variant="ghost" size="sm" icon="scale" wire:click="openTaxesModal({{ $quotation->Id }})" data-test="quotation-taxes-button" />
                                                </flux:tooltip>
                                                <flux:tooltip content="Enviar cotización" position="top">
                                                    <flux:button
                                                        variant="ghost"
                                                        size="sm"
                                                        icon="paper-airplane"
                                                        data-nexo-confirm
                                                        data-nexo-title="Enviar cotización"
                                                        data-nexo-text="{{ "La cotización {$quotation->number} quedará marcada como enviada." }}"
                                                        data-nexo-confirm-text="Enviar"
                                                        data-nexo-method="sendQuotation"
                                                        data-nexo-args="@js([$quotation->Id])"
                                                        data-test="quotation-send-button"
                                                    />
                                                </flux:tooltip>
                                                <flux:tooltip content="Seleccionar términos" position="top">
                                                    <flux:button variant="ghost" size="sm" icon="document-check" wire:click="openTermsModal({{ $quotation->Id }})" data-test="quotation-terms-button" />
                                                </flux:tooltip>
                                                <flux:tooltip content="Editar" position="top">
                                                    <flux:button variant="ghost" size="sm" icon="pencil-square" wire:click="openEditModal({{ $quotation->Id }})" data-test="quotation-edit-button" />
                                                </flux:tooltip>
                                                <flux:tooltip content="Eliminar" position="top">
                                                    <flux:button
                                                        variant="ghost"
                                                        size="sm"
                                                        icon="trash"
                                                        data-nexo-confirm
                                                        data-nexo-title="Eliminar cotización"
                                                        data-nexo-text="{{ "Esta acción eliminará la cotización {$quotation->number} y sus detalles." }}"
                                                        data-nexo-confirm-text="Eliminar"
                                                        data-nexo-method="deleteQuotation"
                                                        data-nexo-args="@js([$quotation->Id])"
                                                        data-test="quotation-delete-button"
                                                    />
                                                </flux:tooltip>
                                                <flux:tooltip content="Imprimir PDF" position="top">
                                                    <flux:button variant="ghost" size="sm" icon="printer" wire:click="printQuotation({{ $quotation->Id }})" data-test="quotation-print-button" />
                                                </flux:tooltip>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                <flux:heading size="lg">Adjuntar detalles</flux:heading>
                <flux:subheading>Selecciona servicios y define la cantidad. Los descuentos se registran desde su propia acción.</flux:subheading>
            </div>

            <div class="overflow-x-auto rounded-[20px] border border-slate-200 dark:border-slate-800">
                <table class="nexo-table w-full min-w-[58rem] text-left text-sm">
                    <thead>
                        <tr>
                            <th scope="col" class="w-12">Sel.</th>
                            <th scope="col">Detalle</th>
                            <th scope="col">Vr. unitario</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Total línea</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->services as $service)
                            @php
                                $serviceId = (string) $service->Id;
                                $itemIndex = collect($items)->search(fn (array $item): bool => $item['services_Id'] === $serviceId);
                                $isSelected = $itemIndex !== false;
                                $lineTotal = $isSelected ? (float) $items[$itemIndex]['line_total'] : 0;
                            @endphp
                            <tr wire:key="detail-service-row-{{ $service->Id }}">
                                <td>
                                    <flux:checkbox
                                        :checked="$isSelected"
                                        wire:click="setServiceSelection('{{ $serviceId }}', {{ $isSelected ? 'false' : 'true' }})"
                                        aria-label="Seleccionar {{ $service->name }}"
                                    />
                                </td>
                                <td>
                                    <p class="nexo-table-primary font-medium">{{ $service->name }}</p>
                                </td>
                                <td>
                                    <p class="nexo-table-primary">{{ $this->money((float) $service->unit_price, $this->selectedFormCurrencyCode()) }}</p>
                                </td>
                                <td>
                                    @if ($isSelected)
                                        <flux:input wire:model.live="items.{{ $itemIndex }}.quantity" type="number" step="0.01" min="0" class="max-w-28" />
                                    @else
                                        <span class="nexo-table-secondary">0</span>
                                    @endif
                                </td>
                                <td>
                                    <p class="nexo-table-primary font-semibold">{{ $this->money($lineTotal, $this->selectedFormCurrencyCode()) }}</p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

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

            <div class="flex justify-end gap-3 border-t border-slate-200 pt-4 dark:border-slate-800">
                <flux:modal.close>
                    <flux:button variant="filled">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary">Guardar servicios</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="quotation-discounts" class="w-full max-w-5xl">
        <form wire:submit="saveItems" class="space-y-5">
            <div class="flex flex-col gap-2 border-b border-slate-200 pb-4 dark:border-slate-800">
                <flux:heading size="lg">Registrar descuentos</flux:heading>
                <flux:subheading>Define el descuento por porcentaje o monto y registra una descripción.</flux:subheading>
            </div>

            <div class="overflow-x-auto rounded-[20px] border border-slate-200 dark:border-slate-800">
                <table class="nexo-table w-full min-w-[68rem] text-left text-sm">
                    <thead>
                        <tr>
                            <th scope="col">Detalle</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Porcentaje</th>
                            <th scope="col">Monto</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Total línea</th>
                        </tr>
                    </thead>
                    <tbody>
                @forelse ($items as $index => $item)
                    <tr wire:key="quotation-discount-{{ $item['services_Id'] ?: $index }}">
                        <td><p class="nexo-table-primary font-medium">{{ $item['description'] }}</p></td>
                        <td>
                            <flux:select wire:model.live="items.{{ $index }}.discount_type" size="sm">
                                <flux:select.option value="percent">Porcentaje</flux:select.option>
                                <flux:select.option value="amount">Monto</flux:select.option>
                            </flux:select>
                        </td>
                        <td><flux:input wire:model.live="items.{{ $index }}.discount_rate" type="number" step="0.01" min="0" class="max-w-28" /></td>
                        <td><flux:input wire:model.live="items.{{ $index }}.discount_amount" type="number" step="0.01" min="0" class="max-w-32" /></td>
                        <td><flux:input wire:model.live="items.{{ $index }}.discount_description" placeholder="Motivo del descuento" /></td>
                        <td><p class="nexo-table-primary font-semibold">{{ $this->money((float) $item['line_total'], $this->selectedFormCurrencyCode()) }}</p></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">
                        Adjunta detalles antes de registrar descuentos.
                        </td>
                    </tr>
                @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-200 pt-4 dark:border-slate-800">
                <flux:modal.close>
                    <flux:button variant="filled">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary">Guardar descuentos</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="quotation-taxes" class="w-full max-w-5xl">
        <form wire:submit="saveItems" class="space-y-5">
            <div class="flex flex-col gap-2 border-b border-slate-200 pb-4 dark:border-slate-800">
                <flux:heading size="lg">Asociar impuestos</flux:heading>
                <flux:subheading>Selecciona el impuesto colombiano aplicable. Se aplicará a los detalles adjuntos de la cotización.</flux:subheading>
            </div>

            <div class="overflow-x-auto rounded-[20px] border border-slate-200 dark:border-slate-800">
                <table class="nexo-table w-full min-w-[48rem] text-left text-sm">
                    <thead>
                        <tr>
                            <th scope="col" class="w-12">Sel.</th>
                            <th scope="col">Impuesto</th>
                            <th scope="col">Porcentaje</th>
                            <th scope="col">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->taxes as $tax)
                            <tr wire:key="quotation-tax-option-{{ $tax->Id }}">
                                <td>
                                    <flux:checkbox wire:model.live="selectedTaxIds" value="{{ (string) $tax->Id }}" aria-label="Seleccionar {{ $tax->name }}" />
                                </td>
                                <td><p class="nexo-table-primary font-medium">{{ $tax->name }}</p></td>
                                <td><p class="nexo-table-primary">{{ number_format((float) $tax->rate, 2, ',', '.') }}%</p></td>
                                <td>
                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                        Activo
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-200 pt-4 dark:border-slate-800">
                <flux:modal.close>
                    <flux:button variant="filled">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary">Guardar impuestos</flux:button>
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

    <flux:modal name="quotation-bulk-status" class="w-full max-w-lg">
        <form wire:submit="updateBulkStatus" class="space-y-5">
            <div>
                <flux:heading size="lg">Actualizar estado por lote</flux:heading>
                <flux:subheading>Aplica el mismo estado a las cotizaciones seleccionadas.</flux:subheading>
            </div>

            <flux:select wire:model="bulkStatusForm.document_statuses_Id" label="Estado" required>
                @foreach ($this->documentStatuses as $status)
                    <flux:select.option value="{{ $status->Id }}">{{ $status->name }}</flux:select.option>
                @endforeach
            </flux:select>

            <div class="flex justify-end gap-3 border-t border-slate-200 pt-4 dark:border-slate-800">
                <flux:modal.close>
                    <flux:button variant="filled">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary">Actualizar lote</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="quotation-terms" class="w-full max-w-3xl">
        <form wire:submit="saveTerms" class="space-y-5">
            <div>
                <flux:heading size="lg">Términos y condiciones</flux:heading>
                <flux:subheading>Selecciona o ajusta los términos después de crear la cotización.</flux:subheading>
            </div>

            @if ($this->termOptions->isNotEmpty())
                <div class="space-y-2">
                    <div class="text-sm font-medium text-slate-700 dark:text-slate-200">Términos disponibles</div>
                    <div class="grid gap-2">
                        @foreach ($this->termOptions as $termOption)
                            <button
                                type="button"
                                wire:key="quotation-term-option-{{ $termOption->Id }}"
                                wire:click="applyTermPreset(@js($termOption->default_term))"
                                class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-left text-sm text-slate-700 transition hover:border-cyan-300 hover:bg-cyan-50 dark:border-slate-800 dark:bg-slate-950/60 dark:text-slate-200 dark:hover:border-cyan-500/40 dark:hover:bg-cyan-500/10"
                            >
                                {{ $termOption->default_term }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <flux:textarea wire:model="termsForm.term" label="Términos seleccionados" rows="5" />
            <flux:textarea wire:model="termsForm.note" label="Notas internas" rows="3" />

            <div class="flex justify-end gap-3 border-t border-slate-200 pt-4 dark:border-slate-800">
                <flux:modal.close>
                    <flux:button variant="filled">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary">Guardar términos</flux:button>
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

<x-layouts::app>
    <section class="mx-auto flex w-full max-w-7xl flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-[28px] border border-slate-200/80 bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.12),_transparent_30%),linear-gradient(135deg,_rgba(255,255,255,0.94),_rgba(241,245,249,0.9))] p-6 shadow-[0_35px_80px_-48px_rgba(15,23,42,0.45)] dark:border-cyan-500/20 dark:bg-[radial-gradient(circle_at_top_left,_rgba(34,211,238,0.14),_transparent_28%),linear-gradient(145deg,_rgba(15,23,42,0.92),_rgba(2,6,23,0.9))]">
            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl space-y-3">
                    <div class="inline-flex items-center gap-2 rounded-full border border-cyan-300/60 bg-cyan-50/90 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-cyan-700 dark:border-cyan-400/20 dark:bg-cyan-400/10 dark:text-cyan-200">
                        <flux:icon name="wrench-screwdriver" class="size-4" />
                        Catálogo comercial
                    </div>

                    <div class="space-y-2">
                        <h1 class="text-3xl font-semibold tracking-tight text-slate-950 dark:text-white sm:text-4xl">Gestión de Servicios</h1>
                        <p class="max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300 sm:text-base">Administra servicios ofertables, unidades, precios base y disponibilidad para cotizaciones.</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <flux:button variant="filled" icon="arrow-path" :href="route('services.index')">Actualizar</flux:button>
                    <flux:button variant="primary" icon="plus" :href="route('services.create')">Nuevo servicio</flux:button>
                </div>
            </div>
        </div>

        @if (session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-200">{{ session('status') }}</div>
        @endif

        <div class="rounded-[28px] border border-slate-200 bg-white/90 shadow-[0_30px_70px_-45px_rgba(15,23,42,0.45)] dark:border-cyan-500/20 dark:bg-slate-900/75">
            <div class="border-b border-slate-200/80 p-5 dark:border-slate-800">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-slate-950 dark:text-white">Listado de servicios</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Grid filtrable para la operación comercial diaria.</p>
                        </div>

                        <form action="{{ route('services.index') }}" method="GET" class="grid gap-3 sm:grid-cols-[minmax(16rem,24rem)_auto]">
                            <flux:input name="search" icon="magnifying-glass" placeholder="Buscar servicio" :value="$search" />
                            <div class="flex gap-2">
                                <flux:button type="submit" variant="filled" icon="funnel">Filtrar</flux:button>
                                @if ($search !== '')
                                    <flux:button variant="ghost" icon="x-mark" :href="route('services.index')">Limpiar</flux:button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @if ($services->count() === 0)
                <div class="m-5 rounded-[24px] border border-dashed border-slate-300 bg-slate-50/90 px-6 py-16 text-center dark:border-slate-700 dark:bg-slate-950/60">
                    <div class="mx-auto flex size-16 items-center justify-center rounded-3xl bg-cyan-100 text-cyan-700 dark:bg-cyan-500/10 dark:text-cyan-300">
                        <flux:icon name="wrench-screwdriver" class="size-8" />
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-slate-950 dark:text-white">No hay servicios registrados</h3>
                    <p class="mx-auto mt-2 max-w-xl text-sm text-slate-500 dark:text-slate-400">Crea un servicio para usarlo como línea comercial en cotizaciones y documentos.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="nexo-table w-full min-w-[76rem] text-left text-sm">
                        <thead>
                            <tr>
                                <th scope="col">Servicio</th>
                                <th scope="col">Categoría</th>
                                <th scope="col">Precio</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Uso</th>
                                <th scope="col">Estado</th>
                                <th scope="col" class="text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($services as $service)
                                <tr>
                                    <td>
                                        <p class="nexo-table-primary font-semibold">{{ $service->name }}</p>
                                        <p class="nexo-table-secondary mt-1 text-xs">{{ $service->code }} · {{ $service->unit }}</p>
                                    </td>
                                    <td>
                                        <p class="nexo-table-primary font-medium">{{ $service->category ?: 'Sin categoría' }}</p>
                                        <p class="nexo-table-secondary mt-1 line-clamp-1 text-xs">{{ $service->description ?: 'Sin descripción' }}</p>
                                    </td>
                                    <td>
                                        <p class="nexo-table-primary font-semibold">
                                            {{ $service->currency?->symbol ?? '$' }} {{ number_format((float) $service->unit_price, 2, ',', '.') }}
                                        </p>
                                        <p class="nexo-table-secondary mt-1 text-xs">{{ $service->currency?->code ?? 'Sin moneda' }}</p>
                                    </td>
                                    <td>
                                        <span class="rounded-full bg-sky-100 px-2.5 py-1 text-xs font-semibold text-sky-700 dark:bg-sky-500/10 dark:text-sky-300">
                                            {{ match ($service->pricing_type) {
                                                'hourly' => 'Por hora',
                                                'monthly' => 'Mensual',
                                                'custom' => 'Personalizado',
                                                default => 'Fijo',
                                            } }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex flex-wrap gap-2">
                                            <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">{{ $service->plan_items_count }} planes</span>
                                            <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-300">{{ $service->service_rates_count }} tarifas</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $service->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300' }}">
                                            {{ $service->is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex items-center justify-end gap-2">
                                            <flux:tooltip content="Editar servicio">
                                                <a href="{{ route('services.edit', $service) }}" class="nexo-action-button nexo-action-button-primary" aria-label="Editar servicio">
                                                    <flux:icon name="pencil" class="size-4" />
                                                </a>
                                            </flux:tooltip>

                                            <form action="{{ route('services.destroy', $service) }}" method="POST" onsubmit="return confirm('¿Eliminar este servicio?');">
                                                @csrf
                                                @method('DELETE')

                                                <flux:tooltip content="Eliminar servicio">
                                                    <button type="submit" class="nexo-action-button nexo-action-button-danger" aria-label="Eliminar servicio">
                                                        <flux:icon name="trash" class="size-4" />
                                                    </button>
                                                </flux:tooltip>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if ($services->hasPages())
                <div class="border-t border-slate-200/80 px-5 py-4 dark:border-slate-800">{{ $services->onEachSide(1)->links() }}</div>
            @endif
        </div>
    </section>
</x-layouts::app>

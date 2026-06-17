<x-layouts::app>
    <section class="mx-auto flex w-full max-w-7xl flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-[28px] border border-slate-200/80 bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.12),_transparent_30%),linear-gradient(135deg,_rgba(255,255,255,0.94),_rgba(241,245,249,0.9))] p-6 shadow-[0_35px_80px_-48px_rgba(15,23,42,0.45)] dark:border-cyan-500/20 dark:bg-[radial-gradient(circle_at_top_left,_rgba(34,211,238,0.14),_transparent_28%),linear-gradient(145deg,_rgba(15,23,42,0.92),_rgba(2,6,23,0.9))]">
            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl space-y-3">
                    <div class="inline-flex items-center gap-2 rounded-full border border-cyan-300/60 bg-cyan-50/90 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-cyan-700 dark:border-cyan-400/20 dark:bg-cyan-400/10 dark:text-cyan-200">
                        <flux:icon name="building-office-2" class="size-4" />
                        Directorio de empresas
                    </div>

                    <div class="space-y-2">
                        <h1 class="text-3xl font-semibold tracking-tight text-slate-950 dark:text-white sm:text-4xl">
                            Gestión de Empresas
                        </h1>
                        <p class="max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300 sm:text-base">
                            Administra emisores, datos fiscales, ubicación y configuración comercial desde una vista alineada con el flujo de cotizaciones.
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <flux:button variant="filled" icon="arrow-path" :href="route('enterprises.index')">
                        Actualizar
                    </flux:button>

                    <flux:button variant="primary" icon="plus" :href="route('enterprises.create')">
                        Nueva empresa
                    </flux:button>
                </div>
            </div>
        </div>

        @if (session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        {{-- <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-[18px] border border-slate-200 bg-white/90 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Registradas</p>
                        <p class="mt-1 text-2xl font-semibold text-slate-950 dark:text-white">{{ $stats['total'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-sky-100 p-3 text-sky-700 dark:bg-sky-500/10 dark:text-sky-300">
                        <flux:icon name="building-office" class="size-6" />
                    </div>
                </div>
            </div>

            <div class="rounded-[18px] border border-slate-200 bg-white/90 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Con correo</p>
                        <p class="mt-1 text-2xl font-semibold text-slate-950 dark:text-white">{{ $stats['with_email'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-emerald-100 p-3 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                        <flux:icon name="envelope" class="size-6" />
                    </div>
                </div>
            </div>

            <div class="rounded-[18px] border border-slate-200 bg-white/90 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Ubicadas</p>
                        <p class="mt-1 text-2xl font-semibold text-slate-950 dark:text-white">{{ $stats['with_location'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-amber-100 p-3 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300">
                        <flux:icon name="map-pin" class="size-6" />
                    </div>
                </div>
            </div>

            <div class="rounded-[18px] border border-slate-200 bg-white/90 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Con cotizaciones</p>
                        <p class="mt-1 text-2xl font-semibold text-slate-950 dark:text-white">{{ $stats['with_quotations'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-fuchsia-100 p-3 text-fuchsia-700 dark:bg-fuchsia-500/10 dark:text-fuchsia-300">
                        <flux:icon name="document-text" class="size-6" />
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="rounded-[28px] border border-slate-200 bg-white/90 shadow-[0_30px_70px_-45px_rgba(15,23,42,0.45)] dark:border-cyan-500/20 dark:bg-slate-900/75">
            <div class="border-b border-slate-200/80 p-5 dark:border-slate-800">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-950 dark:text-white">Listado de empresas</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Busca por razón social, documento, correo o ciudad.</p>
                    </div>

                    <form action="{{ route('enterprises.index') }}" method="GET" class="grid gap-3 sm:grid-cols-[minmax(16rem,24rem)_auto]">
                        <flux:input name="search" icon="magnifying-glass" placeholder="Buscar empresa" :value="$search" />
                        <div class="flex gap-2">
                            <flux:button type="submit" variant="filled" icon="funnel">Filtrar</flux:button>
                            @if ($search !== '')
                                <flux:button variant="ghost" icon="x-mark" :href="route('enterprises.index')">Limpiar</flux:button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div>
                @if ($enterprises->count() === 0)
                    <div class="m-5 rounded-[24px] border border-dashed border-slate-300 bg-slate-50/90 px-6 py-16 text-center dark:border-slate-700 dark:bg-slate-950/60">
                        <div class="mx-auto flex size-16 items-center justify-center rounded-3xl bg-cyan-100 text-cyan-700 dark:bg-cyan-500/10 dark:text-cyan-300">
                            <flux:icon name="building-office-2" class="size-8" />
                        </div>
                        <h3 class="mt-5 text-lg font-semibold text-slate-950 dark:text-white">No hay empresas registradas</h3>
                        <p class="mx-auto mt-2 max-w-xl text-sm text-slate-500 dark:text-slate-400">
                            Crea una empresa para usarla como emisor en cotizaciones, plantillas, cuentas bancarias y documentos comerciales.
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="nexo-table w-full min-w-[72rem] text-left text-sm">
                            <thead>
                                <tr>
                                    <th scope="col">Empresa</th>
                                    <th scope="col">Documento</th>
                                    <th scope="col">Contacto</th>
                                    <th scope="col">Ubicación</th>
                                    <th scope="col">Uso comercial</th>
                                    <th scope="col" class="text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($enterprises as $enterprise)
                                    <tr>
                                        <td>
                                            <p class="nexo-table-primary font-semibold">{{ $enterprise->legal_name }}</p>
                                            <p class="nexo-table-secondary mt-1 text-xs">{{ $enterprise->trade_name ?: 'Sin nombre comercial' }}</p>
                                        </td>
                                        <td>
                                            <p class="nexo-table-primary font-medium">{{ $enterprise->documentType?->name ?? 'Documento' }}</p>
                                            <p class="nexo-table-secondary mt-1 text-xs">{{ $enterprise->document_number }}</p>
                                        </td>
                                        <td>
                                            <p class="nexo-table-primary font-medium">{{ $enterprise->email ?: 'Sin correo' }}</p>
                                            <p class="nexo-table-secondary mt-1 text-xs">{{ $enterprise->phone ?: 'Sin teléfono' }}</p>
                                        </td>
                                        <td>
                                            <p class="nexo-table-primary font-medium">
                                                {{ collect([$enterprise->city, $enterprise->state])->filter()->join(', ') ?: 'Sin ciudad' }}
                                            </p>
                                            <p class="nexo-table-secondary mt-1 text-xs">{{ $enterprise->country ?: 'Sin país' }}</p>
                                        </td>
                                        <td>
                                            <div class="flex flex-wrap gap-2">
                                                <span class="rounded-full bg-sky-100 px-2.5 py-1 text-xs font-semibold text-sky-700 dark:bg-sky-500/10 dark:text-sky-300">{{ $enterprise->quotations_count }} cot.</span>
                                                <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">{{ $enterprise->bank_accounts_count }} bancos</span>
                                                <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-300">{{ $enterprise->document_templates_count }} plantillas</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex items-center justify-end gap-2">
                                                <flux:tooltip content="Ver empresa">
                                                    <a href="{{ route('enterprises.show', $enterprise) }}" class="nexo-action-button nexo-action-button-primary" aria-label="Ver empresa">
                                                        <flux:icon name="eye" class="size-4" />
                                                    </a>
                                                </flux:tooltip>

                                                <flux:tooltip content="Editar empresa">
                                                    <a href="{{ route('enterprises.edit', $enterprise) }}" class="nexo-action-button nexo-action-button-primary" aria-label="Editar empresa">
                                                        <flux:icon name="pencil" class="size-4" />
                                                    </a>
                                                </flux:tooltip>

                                                <form action="{{ route('enterprises.destroy', $enterprise) }}" method="POST" onsubmit="return confirm('¿Eliminar esta empresa?');">
                                                    @csrf
                                                    @method('DELETE')

                                                    <flux:tooltip content="Eliminar empresa">
                                                        <button type="submit" class="nexo-action-button nexo-action-button-danger" aria-label="Eliminar empresa">
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
            </div>

            @if ($enterprises->hasPages())
                <div class="border-t border-slate-200/80 px-5 py-4 dark:border-slate-800">
                    {{ $enterprises->onEachSide(1)->links() }}
                </div>
            @endif
        </div>
    </section>
</x-layouts::app>

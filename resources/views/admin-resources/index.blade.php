<x-layouts::app>
    <section class="mx-auto flex w-full max-w-7xl flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-[28px] border border-slate-200/80 bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.12),_transparent_30%),linear-gradient(135deg,_rgba(255,255,255,0.94),_rgba(241,245,249,0.9))] p-6 shadow-[0_35px_80px_-48px_rgba(15,23,42,0.45)] dark:border-cyan-500/20 dark:bg-[radial-gradient(circle_at_top_left,_rgba(34,211,238,0.14),_transparent_28%),linear-gradient(145deg,_rgba(15,23,42,0.92),_rgba(2,6,23,0.9))]">
            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl space-y-3">
                    <div class="inline-flex items-center gap-2 rounded-full border border-cyan-300/60 bg-cyan-50/90 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-cyan-700 dark:border-cyan-400/20 dark:bg-cyan-400/10 dark:text-cyan-200">
                        <flux:icon :name="$resource['icon']" class="size-4" />
                        {{ $resource['eyebrow'] }}
                    </div>

                    <div class="space-y-2">
                        <h1 class="text-3xl font-semibold tracking-tight text-slate-950 dark:text-white sm:text-4xl">
                            {{ $resource['title'] }}
                        </h1>
                        <p class="max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300 sm:text-base">
                            {{ $resource['description'] }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <flux:button variant="filled" icon="arrow-path" :href="route($resource['route'].'.index')">
                        Actualizar
                    </flux:button>

                    <flux:button variant="primary" icon="plus" :href="route($resource['route'].'.create')">
                        Nuevo
                    </flux:button>
                </div>
            </div>
        </div>

        @if (session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-[18px] border border-slate-200 bg-white/90 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Registros</p>
                <p class="mt-1 text-2xl font-semibold text-slate-950 dark:text-white">{{ $stats['total'] }}</p>
            </div>

            @if (array_key_exists('active', $stats))
                <div class="rounded-[18px] border border-slate-200 bg-white/90 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Activos</p>
                    <p class="mt-1 text-2xl font-semibold text-slate-950 dark:text-white">{{ $stats['active'] }}</p>
                </div>
            @endif

            @if (array_key_exists('default', $stats))
                <div class="rounded-[18px] border border-slate-200 bg-white/90 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Predeterminados</p>
                    <p class="mt-1 text-2xl font-semibold text-slate-950 dark:text-white">{{ $stats['default'] }}</p>
                </div>
            @endif
        </div>

        <div class="rounded-[28px] border border-slate-200 bg-white/90 shadow-[0_30px_70px_-45px_rgba(15,23,42,0.45)] dark:border-cyan-500/20 dark:bg-slate-900/75">
            <div class="border-b border-slate-200/80 p-5 dark:border-slate-800">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-950 dark:text-white">Listado</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Busca y gestiona registros del módulo.</p>
                    </div>

                    <form action="{{ route($resource['route'].'.index') }}" method="GET" class="grid gap-3 sm:grid-cols-[minmax(16rem,24rem)_auto]">
                        <flux:input name="search" icon="magnifying-glass" placeholder="Buscar" :value="$search" />
                        <div class="flex gap-2">
                            <flux:button type="submit" variant="filled" icon="funnel">Filtrar</flux:button>
                            @if ($search !== '')
                                <flux:button variant="ghost" icon="x-mark" :href="route($resource['route'].'.index')">Limpiar</flux:button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            @if ($records->count() === 0)
                <div class="m-5 rounded-[24px] border border-dashed border-slate-300 bg-slate-50/90 px-6 py-16 text-center dark:border-slate-700 dark:bg-slate-950/60">
                    <div class="mx-auto flex size-16 items-center justify-center rounded-3xl bg-cyan-100 text-cyan-700 dark:bg-cyan-500/10 dark:text-cyan-300">
                        <flux:icon :name="$resource['icon']" class="size-8" />
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-slate-950 dark:text-white">No hay registros para mostrar</h3>
                    <p class="mx-auto mt-2 max-w-xl text-sm text-slate-500 dark:text-slate-400">
                        Crea un registro para empezar a usar este módulo en la operación.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="nexo-table w-full min-w-[72rem] text-left text-sm">
                        <thead>
                            <tr>
                                @foreach (collect($resource['fields'])->where('table', true) as $field)
                                    <th scope="col">{{ $field['label'] }}</th>
                                @endforeach
                                <th scope="col" class="text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records as $record)
                                <tr>
                                    @foreach (collect($resource['fields'])->where('table', true) as $field)
                                        <td>
                                            <p class="nexo-table-primary font-medium">
                                                @include('admin-resources._value', ['record' => $record, 'field' => $field])
                                            </p>
                                        </td>
                                    @endforeach
                                    <td>
                                        <div class="flex items-center justify-end gap-2">
                                            <flux:tooltip content="Ver resumen">
                                                <a href="{{ route($resource['route'].'.show', $record->getKey()) }}" class="nexo-action-button nexo-action-button-primary" aria-label="Ver resumen">
                                                    <flux:icon name="eye" class="size-4" />
                                                </a>
                                            </flux:tooltip>

                                            <flux:tooltip content="Editar">
                                                <a href="{{ route($resource['route'].'.edit', $record->getKey()) }}" class="nexo-action-button nexo-action-button-primary" aria-label="Editar">
                                                    <flux:icon name="pencil" class="size-4" />
                                                </a>
                                            </flux:tooltip>

                                            <form action="{{ route($resource['route'].'.destroy', $record->getKey()) }}" method="POST" onsubmit="return confirm('¿Eliminar este registro?');">
                                                @csrf
                                                @method('DELETE')

                                                <flux:tooltip content="Eliminar">
                                                    <button type="submit" class="nexo-action-button nexo-action-button-danger" aria-label="Eliminar">
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

            @if ($records->hasPages())
                <div class="border-t border-slate-200/80 px-5 py-4 dark:border-slate-800">
                    {{ $records->onEachSide(1)->links() }}
                </div>
            @endif
        </div>
    </section>

    @if ($selectedRecord)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 px-4 py-8 backdrop-blur-sm">
            <div class="w-full max-w-3xl overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_35px_90px_-35px_rgba(15,23,42,0.65)] dark:border-cyan-500/20 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-4 border-b border-slate-200/80 px-6 py-5 dark:border-slate-800">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-cyan-700 dark:text-cyan-200">Resumen</p>
                        <h2 class="mt-2 text-2xl font-semibold text-slate-950 dark:text-white">{{ $resource['title'] }}</h2>
                    </div>
                    <a href="{{ route($resource['route'].'.index') }}" class="nexo-action-button nexo-action-button-primary" aria-label="Cerrar resumen">
                        <flux:icon name="x-mark" class="size-5" />
                    </a>
                </div>

                <dl class="max-h-[70vh] overflow-y-auto divide-y divide-slate-200/80 dark:divide-slate-800">
                    @foreach ($resource['fields'] as $field)
                        <div class="grid gap-1 px-6 py-4 md:grid-cols-3">
                            <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $field['label'] }}</dt>
                            <dd class="text-sm font-semibold text-slate-950 dark:text-white md:col-span-2">
                                @include('admin-resources._value', ['record' => $selectedRecord, 'field' => $field])
                            </dd>
                        </div>
                    @endforeach
                </dl>

                <div class="flex flex-wrap justify-end gap-3 border-t border-slate-200/80 px-6 py-5 dark:border-slate-800">
                    <flux:button variant="filled" icon="x-mark" :href="route($resource['route'].'.index')">
                        Cerrar
                    </flux:button>
                    <flux:button variant="primary" icon="pencil" :href="route($resource['route'].'.edit', $selectedRecord->getKey())">
                        Editar
                    </flux:button>
                </div>
            </div>
        </div>
    @endif
</x-layouts::app>

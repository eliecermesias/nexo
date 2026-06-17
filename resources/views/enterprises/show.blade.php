<x-layouts::app>
    <section class="mx-auto flex w-full max-w-6xl flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-[28px] border border-slate-200/80 bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.12),_transparent_30%),linear-gradient(135deg,_rgba(255,255,255,0.94),_rgba(241,245,249,0.9))] p-6 shadow-[0_35px_80px_-48px_rgba(15,23,42,0.45)] dark:border-cyan-500/20 dark:bg-[radial-gradient(circle_at_top_left,_rgba(34,211,238,0.14),_transparent_28%),linear-gradient(145deg,_rgba(15,23,42,0.92),_rgba(2,6,23,0.9))]">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div class="space-y-3">
                    <div class="inline-flex items-center gap-2 rounded-full border border-cyan-300/60 bg-cyan-50/90 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-cyan-700 dark:border-cyan-400/20 dark:bg-cyan-400/10 dark:text-cyan-200">
                        <flux:icon name="building-office-2" class="size-4" />
                        Ficha de empresa
                    </div>

                    <div>
                        <h1 class="text-3xl font-semibold tracking-tight text-slate-950 dark:text-white sm:text-4xl">{{ $enterprise->legal_name }}</h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300">
                            {{ $enterprise->trade_name ?: 'Sin nombre comercial' }} · {{ $enterprise->documentType?->name ?? 'Documento' }} {{ $enterprise->document_number }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <flux:button variant="filled" icon="arrow-left" :href="route('enterprises.index')">
                        Volver
                    </flux:button>
                    <flux:button variant="primary" icon="pencil" :href="route('enterprises.edit', $enterprise)">
                        Editar
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
                <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Cotizaciones</p>
                <p class="mt-1 text-2xl font-semibold text-slate-950 dark:text-white">{{ $enterprise->quotations_count }}</p>
            </div>
            <div class="rounded-[18px] border border-slate-200 bg-white/90 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Cuentas bancarias</p>
                <p class="mt-1 text-2xl font-semibold text-slate-950 dark:text-white">{{ $enterprise->bank_accounts_count }}</p>
            </div>
            <div class="rounded-[18px] border border-slate-200 bg-white/90 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Plantillas</p>
                <p class="mt-1 text-2xl font-semibold text-slate-950 dark:text-white">{{ $enterprise->document_templates_count }}</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1.4fr_0.8fr]">
            <div class="rounded-[28px] border border-slate-200 bg-white/90 shadow-[0_30px_70px_-45px_rgba(15,23,42,0.45)] dark:border-cyan-500/20 dark:bg-slate-900/75">
                <div class="border-b border-slate-200/80 px-5 py-4 dark:border-slate-800">
                    <h2 class="text-xl font-semibold text-slate-950 dark:text-white">Información general</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Datos fiscales, contacto y ubicación.</p>
                </div>

                <dl class="divide-y divide-slate-200/80 dark:divide-slate-800">
                    @foreach ([
                        'Tipo de documento' => $enterprise->documentType?->name ?? '-',
                        'Número de documento' => $enterprise->document_number,
                        'Razón social' => $enterprise->legal_name,
                        'Nombre comercial' => $enterprise->trade_name ?: '-',
                        'Correo' => $enterprise->email ?: '-',
                        'Teléfono' => $enterprise->phone ?: '-',
                        'Dirección' => $enterprise->address ?: '-',
                        'Ubicación' => collect([$enterprise->city, $enterprise->state, $enterprise->country])->filter()->join(', ') ?: '-',
                        'Régimen tributario' => $enterprise->tax_regime ?: '-',
                    ] as $label => $value)
                        <div class="grid gap-1 px-5 py-4 md:grid-cols-3">
                            <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $label }}</dt>
                            <dd class="text-sm font-semibold text-slate-950 dark:text-white md:col-span-2">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            <div class="rounded-[28px] border border-slate-200 bg-white/90 p-5 shadow-[0_30px_70px_-45px_rgba(15,23,42,0.45)] dark:border-cyan-500/20 dark:bg-slate-900/75">
                <h2 class="text-xl font-semibold text-slate-950 dark:text-white">Acciones</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Gestiona esta empresa desde su ficha.</p>

                <div class="mt-5 grid gap-3">
                    <flux:button variant="primary" icon="pencil" :href="route('enterprises.edit', $enterprise)">
                        Editar empresa
                    </flux:button>

                    <form action="{{ route('enterprises.destroy', $enterprise) }}" method="POST" onsubmit="return confirm('¿Eliminar esta empresa?');">
                        @csrf
                        @method('DELETE')

                        <flux:button type="submit" variant="danger" icon="trash" class="w-full">
                            Eliminar empresa
                        </flux:button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-layouts::app>

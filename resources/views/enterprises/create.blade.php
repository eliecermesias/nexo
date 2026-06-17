<x-layouts::app>
    <section class="mx-auto flex w-full max-w-5xl flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-[28px] border border-slate-200/80 bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.12),_transparent_30%),linear-gradient(135deg,_rgba(255,255,255,0.94),_rgba(241,245,249,0.9))] p-6 shadow-[0_35px_80px_-48px_rgba(15,23,42,0.45)] dark:border-cyan-500/20 dark:bg-[radial-gradient(circle_at_top_left,_rgba(34,211,238,0.14),_transparent_28%),linear-gradient(145deg,_rgba(15,23,42,0.92),_rgba(2,6,23,0.9))]">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="space-y-3">
                    <div class="inline-flex items-center gap-2 rounded-full border border-cyan-300/60 bg-cyan-50/90 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-cyan-700 dark:border-cyan-400/20 dark:bg-cyan-400/10 dark:text-cyan-200">
                        <flux:icon name="plus" class="size-4" />
                        Nueva empresa
                    </div>
                    <div>
                        <h1 class="text-3xl font-semibold tracking-tight text-slate-950 dark:text-white">Crear empresa</h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300">
                            Registra la información fiscal y operativa que después usarán cotizaciones, plantillas y cuentas bancarias.
                        </p>
                    </div>
                </div>

                <flux:button variant="filled" icon="arrow-left" :href="route('enterprises.index')">
                    Volver
                </flux:button>
            </div>
        </div>

        <div class="rounded-[28px] border border-slate-200 bg-white/90 p-5 shadow-[0_30px_70px_-45px_rgba(15,23,42,0.45)] dark:border-cyan-500/20 dark:bg-slate-900/75 sm:p-6">
            @include('enterprises._form', [
                'action' => route('enterprises.store'),
                'cancelUrl' => route('enterprises.index'),
            ])
        </div>
    </section>
</x-layouts::app>

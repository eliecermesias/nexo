<x-layouts::app>
    @php
        $totalEnterprises = $enterprises->count();
        $enterprisesWithEmail = $enterprises->filter(fn ($enterprise) => filled($enterprise->email))->count();
        $enterprisesWithLocation = $enterprises->filter(fn ($enterprise) => filled($enterprise->city) || filled($enterprise->state) || filled($enterprise->country))->count();
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <flux:breadcrumbs>
                <flux:breadcrumbs.item :href="route('dashboard')">Dashboard</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>{{ __('Empresas') }}</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>

        <section class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="grid gap-6 border-b border-zinc-200 bg-zinc-950 px-5 py-6 text-white sm:px-6 lg:grid-cols-[1fr_auto] lg:items-end dark:border-zinc-800">
                <div class="max-w-3xl">
                    <p class="text-sm font-medium uppercase tracking-wide text-sky-200">{{ __('Directorio comercial') }}</p>
                    <h1 class="mt-2 text-2xl font-semibold tracking-normal sm:text-3xl">{{ __('Empresas') }}</h1>
                    <p class="mt-2 text-sm leading-6 text-zinc-300">
                        {{ __('Gestiona la informacion comercial, tributaria y de contacto de las empresas registradas.') }}
                    </p>
                </div>

                <div class="grid grid-cols-3 gap-2 text-center sm:min-w-[24rem]">
                    <div class="rounded-lg border border-white/10 bg-white/10 px-3 py-3">
                        <p class="text-2xl font-semibold">{{ $totalEnterprises }}</p>
                        <p class="mt-1 text-xs text-zinc-300">{{ __('Total') }}</p>
                    </div>
                    <div class="rounded-lg border border-white/10 bg-white/10 px-3 py-3">
                        <p class="text-2xl font-semibold">{{ $enterprisesWithEmail }}</p>
                        <p class="mt-1 text-xs text-zinc-300">{{ __('Con correo') }}</p>
                    </div>
                    <div class="rounded-lg border border-white/10 bg-white/10 px-3 py-3">
                        <p class="text-2xl font-semibold">{{ $enterprisesWithLocation }}</p>
                        <p class="mt-1 text-xs text-zinc-300">{{ __('Ubicadas') }}</p>
                    </div>
                </div>
            </div>

            <div class="px-5 py-5 sm:px-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-zinc-950 dark:text-white">{{ __('Listado de empresas') }}</h2>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                            {{ __('Revisa rapidamente los datos principales y accede a las acciones de cada registro.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[52rem] text-left text-sm">
                    <thead class="border-y border-zinc-200 bg-zinc-50 text-xs font-semibold uppercase text-zinc-500 dark:border-zinc-800 dark:bg-zinc-950/50 dark:text-zinc-400">
                        <tr>
                            <th scope="col" class="px-5 py-3 sm:px-6">{{ __('Empresa') }}</th>
                            <th scope="col" class="px-5 py-3 sm:px-6">{{ __('Contacto') }}</th>
                            <th scope="col" class="px-5 py-3 sm:px-6">{{ __('Ubicacion') }}</th>
                            <th scope="col" class="px-5 py-3 text-right sm:px-6">{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @forelse ($enterprises as $enterprise)
                            <tr class="bg-white transition hover:bg-sky-50/60 dark:bg-zinc-900 dark:hover:bg-sky-950/20">
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-sky-100 text-sm font-semibold text-sky-700 ring-1 ring-sky-200 dark:bg-sky-950 dark:text-sky-200 dark:ring-sky-800">
                                            {{ str($enterprise->legal_name)->substr(0, 1)->upper() }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="truncate font-semibold text-zinc-950 dark:text-white">{{ $enterprise->legal_name }}</p>
                                            <p class="mt-1 truncate text-xs text-zinc-500 dark:text-zinc-400">
                                                {{ $enterprise->trade_name ?: __('Sin nombre comercial') }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="space-y-1">
                                        <p class="font-medium text-zinc-800 dark:text-zinc-100">{{ $enterprise->email ?: __('Sin correo') }}</p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $enterprise->phone ?: __('Sin telefono') }}</p>
                                    </div>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <p class="font-medium text-zinc-800 dark:text-zinc-100">
                                        {{ collect([$enterprise->city, $enterprise->state])->filter()->join(', ') ?: __('Sin ciudad') }}
                                    </p>
                                    <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">{{ $enterprise->country ?: __('Sin pais') }}</p>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center justify-end gap-2">
                                        <flux:tooltip :content="__('Ver empresa')">
                                            <flux:button
                                                variant="ghost"
                                                size="sm"
                                                icon="eye"
                                                :href="route('enterprises.show', $enterprise)"
                                                :aria-label="__('Ver empresa')"
                                            />
                                        </flux:tooltip>

                                        <flux:tooltip :content="__('Editar empresa')">
                                            <flux:button
                                                variant="primary"
                                                size="sm"
                                                icon="pencil"
                                                :href="route('enterprises.edit', $enterprise)"
                                                :aria-label="__('Editar empresa')"
                                            />
                                        </flux:tooltip>

                                        <form action="{{ route('enterprises.destroy', $enterprise) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <flux:tooltip :content="__('Eliminar empresa')">
                                                <flux:button
                                                    variant="danger"
                                                    size="sm"
                                                    type="submit"
                                                    icon="trash"
                                                    :aria-label="__('Eliminar empresa')"
                                                />
                                            </flux:tooltip>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-14 text-center sm:px-6">
                                    <div class="mx-auto max-w-sm">
                                        <div class="mx-auto flex size-12 items-center justify-center rounded-lg bg-zinc-100 text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                                            <flux:icon name="building-office-2" class="size-6" />
                                        </div>
                                        <h3 class="mt-4 text-base font-semibold text-zinc-950 dark:text-white">{{ __('No hay empresas registradas') }}</h3>
                                        <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                                            {{ __('Cuando existan empresas, apareceran aqui con sus datos de contacto y ubicacion.') }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

</x-layouts::app>

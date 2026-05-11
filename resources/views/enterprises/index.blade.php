<x-layouts::app>
    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <flux:breadcrumbs>
                <flux:breadcrumbs.item :href="route('dashboard')">Dashboard</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>{{ __('Enterprises') }}</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>

        <section class="nexo-panel overflow-hidden rounded-xl border shadow-sm">
            <div class="px-5 py-5 sm:px-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="nexo-section-title text-base font-semibold">{{ __('Enterprise list') }}</h2>
                        <p class="nexo-section-subtitle mt-1 text-sm">
                            {{ __('Review key details and access each record action quickly.') }}
                        </p>
                    </div>

                    <a href="{{ route('enterprises.create') }}" class="nexo-button-primary self-end">
                        <flux:icon name="plus" class="size-4" />
                        <span>{{ __('New enterprise') }}</span>
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="nexo-table w-full min-w-[52rem] text-left text-sm">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('Enterprise') }}</th>
                            <th scope="col">{{ __('Contact') }}</th>
                            <th scope="col">{{ __('Location') }}</th>
                            <th scope="col" class="text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($enterprises as $enterprise)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="min-w-0">
                                            <p class="nexo-table-primary truncate font-semibold">{{ $enterprise->legal_name }}</p>
                                            <p class="nexo-table-secondary mt-1 truncate text-xs">
                                                {{ $enterprise->trade_name ?: __('No trade name') }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="space-y-1">
                                        <p class="nexo-table-primary font-medium">{{ $enterprise->email ?: __('No email') }}</p>
                                        <p class="nexo-table-secondary text-xs">{{ $enterprise->phone ?: __('No phone') }}</p>
                                    </div>
                                </td>
                                <td>
                                    <p class="nexo-table-primary font-medium">
                                        {{ collect([$enterprise->city, $enterprise->state])->filter()->join(', ') ?: __('No city') }}
                                    </p>
                                    <p class="nexo-table-secondary mt-1 text-xs">{{ $enterprise->country ?: __('No country') }}</p>
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <flux:tooltip :content="__('View enterprise')">
                                            <a href="{{ route('enterprises.show', $enterprise) }}" class="nexo-action-button nexo-action-button-primary" aria-label="{{ __('View enterprise') }}">
                                                <flux:icon name="eye" class="size-4" />
                                            </a>
                                        </flux:tooltip>

                                        <flux:tooltip :content="__('Edit enterprise')">
                                            <a href="{{ route('enterprises.edit', $enterprise) }}" class="nexo-action-button nexo-action-button-primary" aria-label="{{ __('Edit enterprise') }}">
                                                <flux:icon name="pencil" class="size-4" />
                                            </a>
                                        </flux:tooltip>

                                        <form action="{{ route('enterprises.destroy', $enterprise) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <flux:tooltip :content="__('Delete enterprise')">
                                                <button type="submit" class="nexo-action-button nexo-action-button-danger" aria-label="{{ __('Delete enterprise') }}">
                                                    <flux:icon name="trash" class="size-4" />
                                                </button>
                                            </flux:tooltip>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-14 text-center">
                                    <div class="mx-auto max-w-sm">
                                        <div class="nexo-empty-icon mx-auto flex size-12 items-center justify-center rounded-lg">
                                            <flux:icon name="building-office-2" class="size-6" />
                                        </div>
                                        <h3 class="nexo-table-primary mt-4 text-base font-semibold">{{ __('No enterprises registered') }}</h3>
                                        <p class="nexo-table-secondary mt-2 text-sm">
                                            {{ __('Enterprises will appear here with their contact and location details.') }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($enterprises->hasPages())
                <div class="nexo-pagination border-t px-5 py-4 sm:px-6">
                    {{ $enterprises->onEachSide(1)->links() }}
                </div>
            @endif
        </section>
    </div>
</x-layouts::app>

@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="Nexalvia" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-9 items-center justify-center rounded-xl bg-white shadow-[0_0_24px_-8px_rgba(0,194,199,0.9)]">
            <x-app-logo-icon class="size-7" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="Nexalvia" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-9 items-center justify-center rounded-xl bg-white shadow-[0_0_24px_-8px_rgba(0,194,199,0.9)]">
            <x-app-logo-icon class="size-7" />
        </x-slot>
    </flux:brand>
@endif

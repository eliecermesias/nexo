<x-layouts::app.sidebar :title="$title ?? null">
    <flux:main class="relative z-10 min-h-screen bg-slate-100 text-slate-950 dark:bg-slate-900 dark:text-slate-100">
        {{ $slot }}
    </flux:main>
</x-layouts::app.sidebar>

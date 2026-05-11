<x-layouts::app>
    <div class="mb-4 flex items-center justify-between">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item :href="route('enterprises.index')">Empresas</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ $enterprise->legal_name }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>

        <a href="{{ route('enterprises.edit', $enterprise) }}" class="btn btn-yellow text-xs">Editar</a>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('status') }}
        </div>
    @endif

    <div class="overflow-hidden rounded border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
        <dl class="divide-y divide-gray-200 dark:divide-gray-700">
            <div class="grid gap-1 px-4 py-3 md:grid-cols-3">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID</dt>
                <dd class="md:col-span-2">{{ $enterprise->getKey() }}</dd>
            </div>
            <div class="grid gap-1 px-4 py-3 md:grid-cols-3">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Documento</dt>
                <dd class="md:col-span-2">{{ $enterprise->document_number }}</dd>
            </div>
            <div class="grid gap-1 px-4 py-3 md:grid-cols-3">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Razon social</dt>
                <dd class="md:col-span-2">{{ $enterprise->legal_name }}</dd>
            </div>
            <div class="grid gap-1 px-4 py-3 md:grid-cols-3">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nombre comercial</dt>
                <dd class="md:col-span-2">{{ $enterprise->trade_name ?: '-' }}</dd>
            </div>
            <div class="grid gap-1 px-4 py-3 md:grid-cols-3">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Correo</dt>
                <dd class="md:col-span-2">{{ $enterprise->email ?: '-' }}</dd>
            </div>
            <div class="grid gap-1 px-4 py-3 md:grid-cols-3">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telefono</dt>
                <dd class="md:col-span-2">{{ $enterprise->phone ?: '-' }}</dd>
            </div>
            <div class="grid gap-1 px-4 py-3 md:grid-cols-3">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Direccion</dt>
                <dd class="md:col-span-2">{{ $enterprise->address ?: '-' }}</dd>
            </div>
            <div class="grid gap-1 px-4 py-3 md:grid-cols-3">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ubicacion</dt>
                <dd class="md:col-span-2">{{ collect([$enterprise->city, $enterprise->state, $enterprise->country])->filter()->join(', ') ?: '-' }}</dd>
            </div>
            <div class="grid gap-1 px-4 py-3 md:grid-cols-3">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Regimen tributario</dt>
                <dd class="md:col-span-2">{{ $enterprise->tax_regime ?: '-' }}</dd>
            </div>
        </dl>
    </div>
</x-layouts::app>

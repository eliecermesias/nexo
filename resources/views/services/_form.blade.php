@php
    $selectClass = 'mt-2 block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm transition focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 disabled:cursor-not-allowed disabled:bg-zinc-100 disabled:text-zinc-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:focus:border-sky-400 dark:focus:ring-sky-400/30';
@endphp

<form action="{{ $action }}" method="POST" class="grid gap-6">
    @csrf

    @isset($method)
        @method($method)
    @endisset

    <div class="grid gap-5 lg:grid-cols-2">
        <flux:input name="code" label="Código" :value="old('code', $service->code ?? '')" required maxlength="50" autofocus />
        <flux:input name="name" label="Nombre" :value="old('name', $service->name ?? '')" required maxlength="180" />
        <flux:input name="category" label="Categoría" :value="old('category', $service->category ?? '')" maxlength="120" />
        <flux:input name="unit" label="Unidad" :value="old('unit', $service->unit ?? 'servicio')" required maxlength="40" />
        <flux:input name="unit_price" label="Precio unitario" type="number" step="0.01" min="0" :value="old('unit_price', $service->unit_price ?? '0')" required />

        <div>
            <label for="pricing_type" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Tipo de precio</label>
            <select id="pricing_type" name="pricing_type" required class="{{ $selectClass }}">
                @foreach ($pricingTypes as $value => $label)
                    <option value="{{ $value }}" @selected(old('pricing_type', $service->pricing_type ?? 'fixed') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('pricing_type') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="currency_id" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Moneda</label>
            <select id="currency_id" name="currency_id" class="{{ $selectClass }}">
                <option value="">Sin moneda predeterminada</option>
                @foreach ($currencies as $currency)
                    <option value="{{ $currency->getKey() }}" @selected(old('currency_id', $service->currency_id ?? '') == $currency->getKey())>
                        {{ $currency->code }} - {{ $currency->name }}
                    </option>
                @endforeach
            </select>
            @error('currency_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-sm font-medium text-slate-700 dark:border-slate-800 dark:bg-slate-950/50 dark:text-slate-200">
            <input type="checkbox" name="is_active" value="1" @checked((bool) old('is_active', $service->is_active ?? true)) class="size-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
            Servicio activo
        </label>

        <div class="lg:col-span-2">
            <label for="description" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Descripción</label>
            <textarea id="description" name="description" rows="4" class="{{ $selectClass }}">{{ old('description', $service->description ?? '') }}</textarea>
            @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        <flux:button variant="primary" type="submit" icon="check">Guardar</flux:button>
        <flux:button variant="filled" :href="$cancelUrl" icon="x-mark">Cancelar</flux:button>
    </div>
</form>

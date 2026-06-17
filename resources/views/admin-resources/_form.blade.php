@php
    $fieldClass = 'mt-2 block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm transition focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 disabled:cursor-not-allowed disabled:bg-zinc-100 disabled:text-zinc-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:focus:border-sky-400 dark:focus:ring-sky-400/30';
@endphp

<form action="{{ $action }}" method="POST" class="grid gap-6">
    @csrf

    @isset($method)
        @method($method)
    @endisset

    <div class="grid gap-5 lg:grid-cols-2">
        @foreach ($resource['fields'] as $field)
            @php
                $name = $field['name'];
                $fieldValue = old($name, $record?->{$name});

                if ($fieldValue instanceof \Illuminate\Support\Carbon) {
                    $fieldValue = $fieldValue->format('Y-m-d');
                }
            @endphp

            <div class="{{ ($field['span'] ?? 1) === 2 ? 'lg:col-span-2' : '' }}">
                @if (($field['type'] ?? 'text') === 'boolean')
                    <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-sm font-medium text-slate-700 dark:border-slate-800 dark:bg-slate-950/50 dark:text-slate-200">
                        <input type="checkbox" name="{{ $name }}" value="1" @checked((bool) old($name, $record?->{$name} ?? false)) class="size-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
                        {{ $field['label'] }}
                    </label>
                @elseif (($field['type'] ?? 'text') === 'select')
                    <label for="{{ $name }}" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                        {{ $field['label'] }}
                    </label>
                    <select id="{{ $name }}" name="{{ $name }}" @required($field['required'] ?? false) class="{{ $fieldClass }}">
                        <option value="">Seleccione una opción</option>
                        @foreach ($options[$field['options']] ?? [] as $option)
                            <option value="{{ $option['value'] }}" @selected((string) $fieldValue === (string) $option['value'])>
                                {{ $option['label'] }}
                            </option>
                        @endforeach
                    </select>
                    @error($name) <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                @elseif (($field['type'] ?? 'text') === 'textarea')
                    <label for="{{ $name }}" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                        {{ $field['label'] }}
                    </label>
                    <textarea id="{{ $name }}" name="{{ $name }}" rows="4" class="{{ $fieldClass }}">{{ $fieldValue }}</textarea>
                    @error($name) <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                @else
                    <flux:input
                        :name="$name"
                        :label="$field['label']"
                        :type="$field['type'] ?? 'text'"
                        :value="$fieldValue"
                        :step="$field['step'] ?? null"
                        :required="$field['required'] ?? false"
                    />
                @endif
            </div>
        @endforeach
    </div>

    <div class="flex flex-wrap gap-3">
        <flux:button variant="primary" type="submit" icon="check">
            Guardar
        </flux:button>

        <flux:button variant="filled" :href="route($resource['route'].'.index')" icon="x-mark">
            Cancelar
        </flux:button>
    </div>
</form>

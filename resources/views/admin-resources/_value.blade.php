@php
    $rawValue = data_get($record, $field['display'] ?? $field['name']);
    $type = $field['type'] ?? 'text';
    $format = $field['format'] ?? null;

    if ($type === 'boolean') {
        $value = $rawValue ? 'Sí' : 'No';
    } elseif ($rawValue instanceof \Illuminate\Support\Carbon) {
        $value = $rawValue->format('Y-m-d');
    } elseif ($format === 'money') {
        $value = '$ '.number_format((float) $rawValue, 2, ',', '.');
    } elseif ($format === 'percent') {
        $value = number_format((float) $rawValue, 4, ',', '.').' %';
    } else {
        $value = filled($rawValue) ? (string) $rawValue : '-';
    }
@endphp

@if ($type === 'boolean')
    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $rawValue ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300' }}">
        {{ $value }}
    </span>
@else
    {{ $value }}
@endif

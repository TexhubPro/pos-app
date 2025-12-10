@props([
    'title' => null,
    'value' => null,
    'accent' => 'blue', // blue|green|gray
    'icon' => null,
])

@php
    $colors = [
        'blue' => 'bg-blue-50 text-blue-700',
        'green' => 'bg-emerald-50 text-emerald-700',
        'gray' => 'bg-gray-50 text-gray-700',
    ];
    $bg = $colors[$accent] ?? $colors['blue'];
@endphp

<div {{ $attributes->merge(['class' => "flex items-center gap-3 rounded-2xl border border-gray-100 px-4 py-3 bg-white shadow-sm"]) }}>
    @if ($icon)
        <span class="inline-flex items-center justify-center rounded-xl {{ $bg }} p-2">
            {!! $icon !!}
        </span>
    @endif
    <div class="flex-1">
        @if ($title)
            <div class="text-sm font-semibold text-gray-600">{{ $title }}</div>
        @endif
        @if (!is_null($value))
            <div class="text-2xl font-bold text-gray-900">{{ $value }}</div>
        @endif
        {{ $slot }}
    </div>
</div>

@props([
    'title' => null,
    'value' => null,
    'hint' => null,
    'color' => 'blue', // blue, green, red, gray
    'dense' => false,
])

@php
    $palette = [
        'blue' => 'from-blue-600 to-blue-500 text-white',
        'green' => 'from-emerald-500 to-emerald-400 text-white',
        'red' => 'from-rose-500 to-rose-400 text-white',
        'gray' => 'from-gray-100 to-gray-50 text-gray-900 border border-gray-200',
    ];
    $bg = $palette[$color] ?? $palette['blue'];
    $padding = $dense ? 'px-4 py-3' : 'px-5 py-4';
@endphp

<div {{ $attributes->merge(['class' => "rounded-2xl shadow-sm bg-gradient-to-br $bg $padding"]) }}>
    @if ($title)
        <p class="text-sm font-semibold opacity-90">{{ $title }}</p>
    @endif
    @if (!is_null($value))
        <p class="{{ $dense ? 'text-2xl' : 'text-3xl' }} font-bold leading-tight">{{ $value }}</p>
    @endif
    @if ($hint)
        <span class="text-xs opacity-80">{{ $hint }}</span>
    @endif
</div>

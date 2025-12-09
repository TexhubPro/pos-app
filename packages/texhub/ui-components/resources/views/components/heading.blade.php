@props([
    'level' => 'h1',
])

@php
    $tag = in_array($level, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6']) ? $level : 'h1';
@endphp

<{{ $tag }}
    {{ $attributes->merge([
        'class' => 'text-2xl font-bold text-gray-900',
    ]) }}>
    {{ $slot }}
</{{ $tag }}>

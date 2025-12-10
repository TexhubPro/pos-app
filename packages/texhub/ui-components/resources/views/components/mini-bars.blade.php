@props([
    'data' => [], // array of numbers
    'labels' => [], // optional array matching data
    'color' => 'from-blue-200 to-blue-500',
])

@php
    $values = collect($data)->map(fn($v) => (float) $v)->all();
    $max = max($values ?: [0]) ?: 1;
@endphp

<div {{ $attributes->merge(['class' => 'flex items-end gap-2 w-full h-24']) }}>
    @foreach ($values as $idx => $val)
        @php
            $height = ($val / $max) * 100;
        @endphp
        <div class="flex-1 flex flex-col items-center">
            <div class="w-full rounded-md bg-gray-100 relative" style="height: {{ max(6, $height) }}%">
                <div class="absolute inset-0 rounded-md bg-gradient-to-t {{ $color }}"></div>
            </div>
            @if (!empty($labels[$idx] ?? null))
                <span class="mt-1 text-[10px] text-gray-500">{{ $labels[$idx] }}</span>
            @endif
        </div>
    @endforeach
</div>

@props([
    'title' => null,
    'value' => null,
    'subtitle' => null,
    'align' => 'between', // between | end
])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-gray-100 bg-white p-4 shadow-sm']) }}>
    <div class="flex items-{{ $align === 'end' ? 'end' : 'center' }} justify-between gap-2">
        <div class="space-y-1 text-sm text-gray-600">
            @if ($title)
                <div class="font-semibold text-gray-900">{{ $title }}</div>
            @endif
            @if ($subtitle)
                <div class="text-xs text-gray-500">{{ $subtitle }}</div>
            @endif
        </div>
        @if (!is_null($value))
            <div class="text-xl font-bold text-gray-900">{{ $value }}</div>
        @endif
    </div>
    {{ $slot }}
</div>

@props([
    'label' => null,
    'hint' => null,
    'error' => null,
])

<div {{ $attributes->class('space-y-1.5')->only('class') }}>
    @if ($label)
        <x-ui::label>{{ $label }}</x-ui::label>
    @endif
    <select {{ $attributes->except('class')->merge([
        'class' =>
            'h-12 w-full rounded-xl bg-white border border-gray-200 px-3 text-base font-semibold text-gray-800 focus:outline-2 focus:outline-blue-600',
    ]) }}>
        {{ $slot }}
    </select>
    @if ($error)
        <p class="text-sm text-red-600">{{ $error }}</p>
    @elseif ($hint)
        <p class="text-xs text-gray-500">{{ $hint }}</p>
    @endif
</div>

@props([
    'label' => null,
])

@php
    $id = $attributes->get('id') ?? 'toggle-' . uniqid();
@endphp

<label for="{{ $id }}" class="flex items-center gap-3 cursor-pointer select-none">
    <input id="{{ $id }}" type="checkbox"
        {{ $attributes->merge(['class' => 'peer sr-only']) }}>
    <span
        class="relative inline-flex h-7 w-12 items-center rounded-full bg-gray-200 transition-all duration-200 peer-checked:bg-blue-600 peer-checked:[&>span]:translate-x-5 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-400 peer-focus:ring-offset-2">
        <span
            class="absolute left-1 flex h-5 w-5 items-center justify-center rounded-full bg-white shadow-sm transition-transform duration-200">
            <svg class="hidden size-3 text-blue-600 peer-checked:block" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M5 12l4 4L19 7" />
            </svg>
        </span>
    </span>
    @if ($label)
        <span class="text-sm font-semibold text-gray-700">{{ $label }}</span>
    @endif
</label>

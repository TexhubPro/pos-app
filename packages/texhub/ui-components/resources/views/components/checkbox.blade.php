@props([
    'label' => null,
    'description' => null,
])

@php
    $id = $attributes->get('id') ?? 'checkbox-' . uniqid();
@endphp

<label for="{{ $id }}" class="flex items-start gap-3 cursor-pointer select-none">
    <input id="{{ $id }}" type="checkbox"
        {{ $attributes->merge(['class' => 'peer sr-only']) }}>
    <span
        class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-md border-2 border-gray-300 bg-white transition-all duration-150 peer-checked:border-blue-600 peer-checked:bg-blue-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-400 peer-focus:ring-offset-2">
        <svg class="size-4 text-white opacity-0 scale-90 transition peer-checked:opacity-100 peer-checked:scale-100"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12l4 4L19 7" />
        </svg>
    </span>
    <span class="space-y-1">
        @if ($label)
            <span class="block text-base font-semibold text-gray-800 leading-5">{{ $label }}</span>
        @else
            <span class="block text-base font-semibold text-gray-800 leading-5">{{ $slot }}</span>
        @endif
        @if ($description)
            <span class="block text-sm text-gray-500 leading-5">{{ $description }}</span>
        @endif
    </span>
</label>

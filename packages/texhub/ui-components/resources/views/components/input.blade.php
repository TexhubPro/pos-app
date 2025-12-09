@props([
    'label' => null,
    'error' => null,
    'type' => 'text',
])

<div class="space-y-1.5">
    @if ($label)
        <div class="text-gray-500 font-semibold">{{ $label }}</div>
    @endif

    <input type="{{ $type }}"
        {{ $attributes->merge([
            'class' =>
                'h-12 w-full border-0 rounded-xl bg-gray-100 px-4 text-base font-semibold placeholder:text-gray-400 ' .
                ($error ? 'outline-2 outline-red-500 bg-white' : 'focus:outline-2 focus:outline-blue-600 focus:bg-white'),
        ]) }} />

    @if ($error)
        <div class="flex items-center gap-2 p-0 text-sm text-red-600">
            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                <path d="M12 9h.01" />
                <path d="M11 12h1v4h1" />
            </svg>
            <p>{{ $error }}</p>
        </div>
    @endif
</div>

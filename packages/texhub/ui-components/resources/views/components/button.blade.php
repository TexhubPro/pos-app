@props([
    'label' => null,
    'error' => null,
    'type' => 'button',
    'loading' => null,
])

<div>
    <button type="{{ $type }}"
        {{ $attributes->merge([
            'class' => 'relative flex items-center justify-center h-12 rounded-xl bg-blue-600 hover:bg-blue-500 px-5 w-full
                                                                        text-base text-white font-semibold cursor-pointer transition-transform duration-100 active:scale-90
                                                                        disabled:bg-blue-500 disabled:cursor-not-allowed',
        ]) }}
        wire:loading.attr="disabled"
        wire:loading.class.add="opacity-70">

        <div class="relative inline-flex items-center justify-center gap-2">
            <div wire:loading.class.remove="hidden" wire:loading.class.add="flex gap-2 items-center"
                class="hidden animate-spin size-5 border-3 border-current border-t-transparent text-white rounded-full"
                role="status" aria-label="loading">
                <span class="sr-only">Loading...</span>
            </div>
            <span class="transition" wire:loading.class.add="opacity-70">
                {{ $slot }}
            </span>
            @if ($loading)
                <span class="sr-only" wire:loading>{{ $loading }}</span>
            @endif
        </div>
    </button>
</div>

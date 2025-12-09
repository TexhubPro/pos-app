@props([
    'show' => false,
    'title' => null,
    'closeAction' => "\$set('showModal', false)",
])

@if ($show)
    <div class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto px-3 sm:px-4 py-6">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="{{ $closeAction }}"></div>
        <div
            class="relative w-full max-w-xl sm:max-w-[800px] bg-white shadow-2xl ring-1 ring-gray-100 rounded-xl p-5 sm:p-6 space-y-4 max-h-[calc(100vh-48px)] overflow-y-auto pointer-events-auto">
            <div class="flex items-center justify-between">
                <p class="text-lg font-semibold text-gray-900">{{ $title }}</p>
                <button type="button" class="text-gray-400 hover:text-gray-600" wire:click="{{ $closeAction }}">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="space-y-4 pb-1 pr-1">
                {{ $slot }}
            </div>
        </div>
    </div>
@endif

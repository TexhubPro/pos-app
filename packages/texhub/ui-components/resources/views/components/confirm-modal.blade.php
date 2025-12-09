@props([
    'show' => false,
    'title' => null,
    'message' => null,
    'confirmAction' => null,
    'cancelAction' => null,
])

@if ($show)
    <div class="fixed inset-0 z-50 flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @if($cancelAction) wire:click="{{ $cancelAction }}" @endif></div>
        <div class="relative w-full max-w-md rounded-xl bg-white shadow-2xl ring-1 ring-gray-100 p-6 space-y-4">
            <div class="flex items-center justify-between">
                <p class="text-lg font-semibold text-gray-900">{{ $title }}</p>
                @if ($cancelAction)
                    <button type="button" class="text-gray-400 hover:text-gray-600" wire:click="{{ $cancelAction }}">
                        <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>
            @if ($message)
                <p class="text-sm text-gray-600">{{ $message }}</p>
            @endif
            <div class="flex items-center justify-end gap-3 pt-2">
                @if ($cancelAction)
                    <x-ui::button type="button" class="w-auto px-4 h-11 bg-blue-600 hover:bg-blue-500 text-white"
                        wire:click="{{ $cancelAction }}">
                        {{ __('Нет') }}
                    </x-ui::button>
                @endif
                @if ($confirmAction)
                    <x-ui::button type="button" class="w-auto px-5 h-11 bg-red-500 hover:bg-red-400 text-white"
                        wire:click="{{ $confirmAction }}">
                        {{ __('Да, удалить') }}
                    </x-ui::button>
                @endif
            </div>
        </div>
    </div>
@endif

@props(['paginator'])

@if ($paginator->hasPages())
    <nav class="flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-gray-600" role="navigation"
        aria-label="Pagination">
        <div class="text-gray-500">
            {{ __('Показано') }}
            <span class="font-semibold text-gray-800">{{ $paginator->firstItem() }}</span>
            {{ __('–') }}
            <span class="font-semibold text-gray-800">{{ $paginator->lastItem() }}</span>
            {{ __('из') }}
            <span class="font-semibold text-gray-800">{{ $paginator->total() }}</span>
        </div>

        <div class="inline-flex items-center gap-2">
            <button type="button"
                class="inline-flex items-center gap-1 rounded-xl border border-gray-200 px-3 py-2 font-semibold transition disabled:opacity-50 disabled:cursor-not-allowed bg-white hover:border-blue-200 hover:text-blue-600"
                wire:click="previousPage" wire:loading.attr="disabled" @if ($paginator->onFirstPage()) disabled @endif>
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6" />
                </svg>
                <span>{{ __('Назад') }}</span>
            </button>

            <span class="px-2 py-1 rounded-lg bg-gray-100 text-gray-800 font-semibold">
                {{ __('Страница') }} {{ $paginator->currentPage() }} {{ __('из') }} {{ $paginator->lastPage() }}
            </span>

            <button type="button"
                class="inline-flex items-center gap-1 rounded-xl border border-gray-200 px-3 py-2 font-semibold transition disabled:opacity-50 disabled:cursor-not-allowed bg-white hover:border-blue-200 hover:text-blue-600"
                wire:click="nextPage" wire:loading.attr="disabled" @if (!$paginator->hasMorePages()) disabled @endif>
                <span>{{ __('Вперёд') }}</span>
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </button>
        </div>
    </nav>
@endif

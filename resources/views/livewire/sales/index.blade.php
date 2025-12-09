<div class="space-y-6">
    <div class="flex items-start justify-between gap-3 flex-wrap">
        <div class="space-y-1">
            <x-ui::heading class="text-2xl">{{ __('Продажи') }}</x-ui::heading>
            <x-ui::subheading>{{ __('Оформление продажи и история') }}</x-ui::subheading>
        </div>
        <a href="{{ route('cashdesk') }}"
            class="inline-flex items-center justify-center h-11 rounded-xl bg-blue-600 text-white px-4 font-semibold hover:bg-blue-500 transition">
            {{ __('Оформить продажу') }}
        </a>
    </div>

    @if (session('status'))
        <x-ui::alert type="success" :messages="[session('status')]" />
    @endif

    <div class="space-y-3">

        <x-ui::table class="min-w-full">
            <x-slot:head>
                <tr>
                    <th
                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                        {{ __('Дата') }}</th>
                    <th
                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                        {{ __('Клиент') }}</th>
                    <th
                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                        {{ __('Товар') }}</th>
                    <th
                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                        {{ __('Шт., кор.') }}</th>
                    <th
                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                        {{ __('Итого, $') }}</th>
                    <th
                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                        {{ __('Оплата') }}</th>
                    <th
                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                        {{ __('Метод') }}</th>
                    <th
                        class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                        {{ __('Действия') }}</th>
                </tr>
            </x-slot:head>

            @forelse ($sales as $sale)
                <tr class="hover:bg-gray-50/60 transition">
                    <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                        {{ $sale->created_at->format('d.m.Y') }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap max-w-[12rem]">
                        <span class="truncate block">{{ $sale->client?->name ?? '—' }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap max-w-[12rem]">
                        <span class="truncate block">{{ $sale->product?->name ?? '—' }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                        {{ $sale->total_units }} / {{ $sale->box_qty }}
                    </td>
                    <td class="px-4 py-3 text-sm font-semibold text-gray-800 whitespace-nowrap">
                        {{ number_format($sale->total_price, 2) }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                        @if ($sale->payment_type === 'cash')
                            {{ __('Наличные') }}
                        @elseif($sale->payment_type === 'debt')
                            {{ __('В долг') }} ({{ number_format($sale->total_price, 2) }})
                        @else
                            {{ __('Смешанный') }} ({{ number_format($sale->cash_amount, 2) }} /
                            {{ number_format($sale->debt_amount, 2) }})
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                        @switch($sale->payment_method)
                            @case('card_milli')
                                {{ __('Корти Милли') }}
                                @break

                            @case('dushanbe_city')
                                {{ __('Душанбе Сити') }}
                            @break

                            @case('alif')
                                {{ __('Алиф') }}
                            @break

                            @default
                                {{ __('Наличные') }}
                        @endswitch
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('sales.show', $sale) }}"
                                class="inline-flex items-center justify-center h-10 w-10 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 transition"
                                title="{{ __('Просмотр') }}">
                                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.5 12.5s3.5-6.5 9.5-6.5 9.5 6.5 9.5 6.5-3.5 6.5-9.5 6.5S2.5 12.5 2.5 12.5Z" />
                                    <circle cx="12" cy="12.5" r="3" />
                                </svg>
                            </a>
                            <button type="button" wire:click="confirmDelete({{ $sale->id }})"
                                class="inline-flex items-center justify-center h-10 w-10 rounded-xl bg-red-500 text-white hover:bg-red-400 transition"
                                title="{{ __('Удалить') }}">
                                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 7h12M10 11v6M14 11v6M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 7V5a2 2 0 1 1 4 0v2" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">{{ __('Продаж пока нет') }}</td>
                    </tr>
                @endforelse
            </x-ui::table>

            <x-ui::pagination :paginator="$sales" />
        </div>

        <x-ui::confirm-modal :show="$showConfirm" :title="__('Удалить продажу?')" :message="__('Это действие нельзя отменить. Остаток вернётся на склад, долг клиента уменьшится.')" confirmAction="delete"
            cancelAction="$set('showConfirm', false)" />
    </div>
    </div>

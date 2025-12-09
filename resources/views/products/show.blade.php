<x-layouts.dashboard>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <x-ui::heading class="text-2xl">{{ __('Товар') }}: {{ $product->name }}</x-ui::heading>
                <x-ui::subheading>{{ __('Детальная информация и история закупок') }}</x-ui::subheading>
            </div>
            <a href="{{ route('products') }}"
                class="inline-flex items-center justify-center h-11 rounded-xl bg-blue-600 text-white px-4 font-semibold hover:bg-blue-500 transition">
                {{ __('Вернуться к списку') }}
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm space-y-4">
                <div class="flex items-center gap-4">
                    <div class="h-16 w-16 rounded-xl bg-gray-100 overflow-hidden flex items-center justify-center">
                        @if ($product->photo)
                            <img src="{{ $product->photo }}" alt="{{ $product->name }}"
                                class="h-full w-full object-cover">
                        @else
                            <svg class="size-7 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 7l5 5-5 5h18l-5-5 5-5z" />
                            </svg>
                        @endif
                    </div>
                    <div class="space-y-1">
                        <div class="text-lg font-semibold text-gray-900">{{ $product->name }}</div>
                        <div class="text-sm text-gray-500">{{ $product->category?->name ?? __('Без категории') }}</div>
                        <div class="text-sm text-gray-500">{{ __('Штрих-код') }}: {{ $product->barcode ?: '—' }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-3">
                    <div class="rounded-xl border border-gray-100 bg-gray-50 p-3">
                        <div class="text-xs text-gray-500 uppercase">{{ __('Коробок') }}</div>
                        <div class="text-xl font-semibold text-gray-900">{{ $product->box_count ?? 0 }}</div>
                    </div>
                    <div class="rounded-xl border border-gray-100 bg-gray-50 p-3">
                        <div class="text-xs text-gray-500 uppercase">{{ __('Штук всего') }}</div>
                        <div class="text-xl font-semibold text-gray-900">{{ $product->quantity ?? 0 }}</div>
                    </div>
                    <div class="rounded-xl border border-gray-100 bg-gray-50 p-3">
                        <div class="text-xs text-gray-500 uppercase">{{ __('Штук в коробке') }}</div>
                        <div class="text-xl font-semibold text-gray-900">{{ $product->units_per_box ?? 0 }}</div>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm space-y-3">
                <div class="text-sm font-semibold text-gray-900">{{ __('Кратко') }}</div>
                <div class="text-sm text-gray-700 space-y-1.5">
                    <div><span class="text-gray-500">{{ __('Категория:') }}</span>
                        <span class="font-semibold text-gray-900">{{ $product->category?->name ?? '—' }}</span>
                    </div>
                    <div><span class="text-gray-500">{{ __('Штрих-код:') }}</span>
                        <span class="font-semibold text-gray-900">{{ $product->barcode ?: '—' }}</span>
                    </div>
                    <div><span class="text-gray-500">{{ __('Создан:') }}</span>
                        <span class="font-semibold text-gray-900">{{ $product->created_at->format('d.m.Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-3">
            <div class="flex items-center gap-3">
                <button class="tab-btn px-4 py-2 rounded-xl text-sm font-semibold bg-blue-600 text-white"
                    data-target="tab-purchases">{{ __('История закупок') }}</button>
                <button class="tab-btn px-4 py-2 rounded-xl text-sm font-semibold bg-gray-100 text-gray-700"
                    data-target="tab-sales">{{ __('История продаж') }}</button>
            </div>

            <div class="tab-panel" id="tab-purchases">
                <x-ui::table class="min-w-full">
                    <x-slot:head>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Дата') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Фирма') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('К/р') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Осталось К/р') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Цена/кор., $') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Дост. Китай, $') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Дост. Тадж., $') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Себест., $') }}</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Действия') }}</th>
                        </tr>
                    </x-slot:head>

                    @forelse ($purchases as $purchase)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                                {{ $purchase->created_at->format('d.m.Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap max-w-[12rem]">
                                <span class="truncate block">{{ $purchase->firm?->name ?? '—' }}</span>
                            </td>
                            @php
                                $received = $purchase->receipts->sum('box_qty');
                                $remaining = max(0, ($purchase->box_qty ?? 0) - $received);
                            @endphp
                            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $purchase->box_qty ?? 0 }}</td>
                            <td class="px-4 py-3 text-sm font-semibold {{ $remaining > 0 ? 'text-amber-600' : 'text-green-600' }} whitespace-nowrap">
                                {{ $remaining }}
                            </td>
                            <td class="px-4 py-3 text-sm font-semibold text-gray-800 whitespace-nowrap">
                                {{ number_format($purchase->purchase_price, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                                {{ number_format($purchase->delivery_cn, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                                {{ number_format($purchase->delivery_tj, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm font-semibold text-gray-800 whitespace-nowrap">
                                {{ number_format($purchase->cost_per_unit, 4) }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('purchases.show', $purchase) }}"
                                        class="inline-flex items-center justify-center h-10 w-10 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 transition"
                                        title="{{ __('Просмотр закупки') }}">
                                        <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.5 12.5s3.5-6.5 9.5-6.5 9.5 6.5 9.5 6.5-3.5 6.5-9.5 6.5S2.5 12.5 2.5 12.5Z" />
                                            <circle cx="12" cy="12.5" r="3" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-6 text-center text-gray-500">{{ __('Закупок пока нет') }}</td>
                        </tr>
                    @endforelse
                </x-ui::table>
            </div>

            <div class="tab-panel hidden" id="tab-sales">
                <x-ui::table class="min-w-full">
                    <x-slot:head>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Дата') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Клиент') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Шт., кор.') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Итого, $') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Оплата') }}</th>
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
                            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                                {{ $sale->total_units }} / {{ $sale->box_qty }}
                            </td>
                            <td class="px-4 py-3 text-sm font-semibold text-gray-800 whitespace-nowrap">
                                {{ number_format($sale->total_price, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                                @switch($sale->payment_type)
                                    @case('debt')
                                        {{ __('В долг') }}
                                        @break
                                    @case('mixed')
                                        {{ __('Смешанный') }}
                                        @break
                                    @default
                                        {{ __('Наличные') }}
                                @endswitch
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">{{ __('Продаж пока нет') }}</td>
                        </tr>
                    @endforelse
                </x-ui::table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('.tab-btn');
            const panels = document.querySelectorAll('.tab-panel');

            buttons.forEach(btn => {
                btn.addEventListener('click', () => {
                    buttons.forEach(b => b.classList.remove('bg-blue-600', 'text-white'));
                    buttons.forEach(b => b.classList.add('bg-gray-100', 'text-gray-700'));
                    panels.forEach(p => p.classList.add('hidden'));

                    btn.classList.add('bg-blue-600', 'text-white');
                    btn.classList.remove('bg-gray-100', 'text-gray-700');

                    const targetId = btn.dataset.target;
                    const target = document.getElementById(targetId);
                    if (target) target.classList.remove('hidden');
                });
            });
        });
    </script>
</x-layouts.dashboard>

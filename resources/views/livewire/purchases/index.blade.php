<div class="space-y-6">
    <div class="flex items-start justify-between gap-3 flex-wrap">
        <div class="space-y-1">
            <x-ui::heading class="text-2xl">{{ __('Закупки') }}</x-ui::heading>
            <x-ui::subheading>{{ __('Учет себестоимости товаров') }}</x-ui::subheading>
        </div>
        <div class="flex items-center gap-3 flex-wrap w-full sm:w-auto">
            <div class="relative flex-1 min-w-[200px] sm:min-w-[240px]">
                <input type="text" wire:model.live="search"
                    class="h-12 w-full rounded-xl bg-white border border-gray-200 px-3 text-sm font-semibold text-gray-800 placeholder:text-gray-400 focus:outline-2 focus:outline-blue-600"
                    placeholder="{{ __('Поиск по товару') }}">
                <svg class="size-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 6.65 6.65a7.5 7.5 0 0 0 10.6 10.6Z" />
                </svg>
            </div>
            <select wire:model="categoryFilter"
                class="h-12 w-full sm:w-52 rounded-xl bg-white border border-gray-200 px-3 text-sm font-semibold text-gray-800 focus:outline-2 focus:outline-blue-600">
                <option value="">{{ __('Все категории') }}</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            <select wire:model="firmFilter"
                class="h-12 w-full sm:w-52 rounded-xl bg-white border border-gray-200 px-3 text-sm font-semibold text-gray-800 focus:outline-2 focus:outline-blue-600">
                <option value="">{{ __('Все фирмы') }}</option>
                @foreach ($firms as $firm)
                    <option value="{{ $firm->id }}">{{ $firm->name }}</option>
                @endforeach
            </select>
            <x-ui::button wire:click="openCreate"
                class="w-full sm:w-auto px-5">{{ __('Добавить закупку') }}</x-ui::button>
        </div>
    </div>

    @if (session('status'))
        <x-ui::alert type="success" :messages="[session('status')]" />
    @endif

    <x-ui::table class="min-w-full">
        <x-slot:head>
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide cursor-pointer whitespace-nowrap"
                    wire:click="sortBy('created_at')">
                    {{ __('Дата') }}
                </th>
                <th
                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                    {{ __('Товар') }}</th>
                <th
                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                    {{ __('Фирма') }}</th>
                <th
                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                    {{ __('Цена за К/р, $') }}</th>
                <th
                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                    {{ __('К/р') }}</th>
                <th
                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                    {{ __('Осталось К/р') }}</th>
                <th
                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                    {{ __('Сумма товара, $') }}</th>
                <th
                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                    {{ __('Дост. Китай, $') }}</th>
                <th
                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                    {{ __('Дост. Тадж., $') }}</th>
                <th
                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                    {{ __('Итого с дост., $') }}</th>
                <th
                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                    {{ __('Себест., $') }}</th>
                <th
                    class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide w-40 whitespace-nowrap">
                    {{ __('Действия') }}</th>
            </tr>
        </x-slot:head>

        @forelse ($purchases as $purchase)
            <tr class="hover:bg-gray-50/60 transition">
                @php
                    $goodsSum = ($purchase->purchase_price ?? 0) * ($purchase->box_qty ?? 1);
                    $totalWithDelivery = $goodsSum + ($purchase->delivery_cn ?? 0) + ($purchase->delivery_tj ?? 0);
                    $received = $purchase->receipts->sum('box_qty');
                    $remaining = max(0, ($purchase->box_qty ?? 0) - $received);
                @endphp
                <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                    {{ $purchase->created_at->format('d.m.Y') }}
                </td>
                <td class="px-4 py-3 text-sm font-semibold text-gray-800 whitespace-nowrap max-w-[13rem]">
                    <span
                        class="truncate block">{{ $purchase->product->category->name ?? ('' . $purchase->product?->name ?? '—') }}</span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap max-w-[13rem]">
                    <span class="truncate block">{{ $purchase->firm?->name ?? '—' }}</span>
                </td>
                <td class="px-4 py-3 text-sm font-semibold text-gray-800 whitespace-nowrap">
                    {{ number_format($purchase->purchase_price, 2) }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                    {{ $purchase->box_qty ?? 1 }}
                </td>
                <td
                    class="px-4 py-3 text-sm font-semibold {{ $remaining > 0 ? 'text-amber-600' : 'text-green-600' }} whitespace-nowrap">
                    {{ $remaining }}
                </td>
                <td class="px-4 py-3 text-sm font-semibold text-gray-800 whitespace-nowrap">
                    {{ number_format($goodsSum, 2) }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                    {{ number_format($purchase->delivery_cn, 2) }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                    {{ number_format($purchase->delivery_tj, 2) }}
                </td>
                <td class="px-4 py-3 text-sm font-semibold text-gray-800 whitespace-nowrap">
                    {{ number_format($totalWithDelivery, 2) }}
                </td>
                <td class="px-4 py-3 text-sm font-semibold text-gray-800 whitespace-nowrap">
                    {{ number_format($purchase->cost_per_unit, 4) }}
                </td>

                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('purchases.show', $purchase) }}"
                            class="inline-flex items-center justify-center h-10 w-10 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 transition"
                            title="{{ __('Просмотр') }}">
                            <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.5 12.5s3.5-6.5 9.5-6.5 9.5 6.5 9.5 6.5-3.5 6.5-9.5 6.5S2.5 12.5 2.5 12.5Z" />
                                <circle cx="12" cy="12.5" r="3" />
                            </svg>
                        </a>
                        <button type="button" wire:click="openEdit({{ $purchase->id }})"
                            class="inline-flex items-center justify-center h-10 w-10 rounded-xl bg-blue-600 text-white hover:bg-blue-500 transition">
                            <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.232 5.232a2.5 2.5 0 1 1 3.536 3.536L7.5 20.036 3 21l.964-4.5z" />
                            </svg>
                        </button>
                        <button type="button" wire:click="delete({{ $purchase->id }})"
                            class="inline-flex items-center justify-center h-10 w-10 rounded-xl bg-red-500 text-white hover:bg-red-400 transition">
                            <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
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
                <td colspan="9" class="px-4 py-6 text-center text-gray-500">{{ __('Закупок пока нет') }}</td>
            </tr>
        @endforelse
    </x-ui::table>

    <x-ui::pagination :paginator="$purchases" />

    <x-ui::modal :show="$showModal" :title="$editingId ? __('Редактирование закупки') : __('Новая закупка')" wire:key="purchase-modal">
        <form class="space-y-4" wire:submit.prevent="save">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <x-ui::label for="product_id">{{ __('Товар') }}</x-ui::label>
                    <select id="product_id" wire:model="product_id"
                        class="h-12 w-full rounded-xl bg-white border border-gray-200 px-3 text-base font-semibold text-gray-800 focus:outline-2 focus:outline-blue-600">
                        <option value="">{{ __('Выберите товар') }}</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->first('product_id'))
                        <p class="text-sm text-red-600">{{ $errors->first('product_id') }}</p>
                    @endif
                </div>
                <div class="space-y-1.5">
                    <x-ui::label for="firm_id">{{ __('Фирма поставщик') }}</x-ui::label>
                    <select id="firm_id" wire:model="firm_id"
                        class="h-12 w-full rounded-xl bg-white border border-gray-200 px-3 text-base font-semibold text-gray-800 focus:outline-2 focus:outline-blue-600">
                        <option value="">{{ __('Выберите фирму') }}</option>
                        @foreach ($firms as $firm)
                            <option value="{{ $firm->id }}">{{ $firm->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->first('firm_id'))
                        <p class="text-sm text-red-600">{{ $errors->first('firm_id') }}</p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="space-y-1.5">
                    <x-ui::label for="purchase_price">{{ __('Цена за коробку, $') }}</x-ui::label>
                    <x-ui::input id="purchase_price" type="text" inputmode="decimal"
                        wire:model.live.debounce.2000ms="purchase_price" :error="$errors->first('purchase_price')" />
                </div>
                <div class="space-y-1.5">
                    <x-ui::label for="box_qty">{{ __('Коробок покупаем') }}</x-ui::label>
                    <x-ui::input id="box_qty" type="number" min="1" step="1"
                        wire:model.live="box_qty" :error="$errors->first('box_qty')" />
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 p-4 space-y-4">
                <div class="text-sm font-semibold text-gray-900">{{ __('Доставка (общий объём)') }}</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div class="space-y-1.5">
                        <x-ui::label for="delivery_volume">{{ __('Объём, куб') }}</x-ui::label>
                        <x-ui::input id="delivery_volume" type="text" inputmode="decimal"
                            wire:model.live.debounce.2000ms="delivery_volume" :error="$errors->first('delivery_volume')"
                            placeholder="{{ __('Куб') }}" />
                    </div>
                    <div class="space-y-1.5">
                        <x-ui::label for="delivery_cn_rate">{{ __('Китай: цена за куб, $') }}</x-ui::label>
                        <x-ui::input id="delivery_cn_rate" type="text" inputmode="decimal"
                            wire:model.live.debounce.2000ms="delivery_cn_rate" :error="$errors->first('delivery_cn_rate')"
                            placeholder="{{ __('Цена/куб, $') }}" />
                    </div>
                    <div class="space-y-1.5">
                        <x-ui::label for="delivery_tj_rate">{{ __('TJ: цена за куб, $') }}</x-ui::label>
                        <x-ui::input id="delivery_tj_rate" type="text" inputmode="decimal"
                            wire:model.live.debounce.2000ms="delivery_tj_rate" :error="$errors->first('delivery_tj_rate')"
                            placeholder="{{ __('Цена/куб, $') }}" />
                    </div>
                    <div class="space-y-1.5">
                        <x-ui::label for="payment_method">{{ __('Оплата доставки TJ') }}</x-ui::label>
                        <select id="payment_method" wire:model="payment_method"
                            class="h-12 w-full rounded-xl bg-white border border-gray-200 px-3 text-base font-semibold text-gray-800 focus:outline-2 focus:outline-blue-600">
                            <option value="cash">{{ __('Наличными (списать из банка)') }}</option>
                            <option value="debt">{{ __('В долг (увеличить долг фирме)') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            @php
                $selectedProduct = collect($products)->firstWhere('id', $product_id);
                $units = $selectedProduct?->units_per_box ?: 0;
                $purchasePriceSanitized = (float) str_replace(',', '.', $purchase_price ?? 0);
                $deliveryVolumeSanitized = (float) str_replace(',', '.', $delivery_volume ?? 0);
                $deliveryCnRateSanitized = (float) str_replace(',', '.', $delivery_cn_rate ?? 0);
                $deliveryTjRateSanitized = (float) str_replace(',', '.', $delivery_tj_rate ?? 0);
                $deliveryCnTotal = $deliveryVolumeSanitized * $deliveryCnRateSanitized;
                $deliveryTjTotal = $deliveryVolumeSanitized * $deliveryTjRateSanitized;
                $boxTotal = $purchasePriceSanitized * $box_qty + $deliveryCnTotal + $deliveryTjTotal;
                $unitCost = $units > 0 && $box_qty > 0 ? $boxTotal / ($units * $box_qty) : 0;
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-gray-50 rounded-xl p-4">
                <div class="text-sm text-gray-600">
                    <div class="font-semibold text-gray-900">{{ __('Коробка: сумма, $') }}</div>
                    <div class="text-lg font-semibold text-gray-900">{{ number_format($boxTotal, 2) }}</div>
                    <div class="text-xs text-gray-500 mt-1">{{ __('Цена + доставка Китай + доставка Таджикистан') }}
                    </div>
                </div>
                <div class="text-sm text-gray-600">
                    <div class="font-semibold text-gray-900">{{ __('Себестоимость за 1 шт, $') }}</div>
                    <div class="text-lg font-semibold text-blue-600">{{ number_format($unitCost, 4) }}</div>
                    <div class="text-xs text-gray-500 mt-1">
                        {{ __('Рассчитано на основе количества в коробке:') }}
                        <span class="font-semibold">{{ $units ?: '—' }}</span>
                    </div>
                </div>
            </div>

            <div class="space-y-1.5">
                <x-ui::label for="files">{{ __('Документы (счёт, накладная и т.д.)') }}</x-ui::label>
                <input id="files" type="file" multiple wire:model="files"
                    class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-800 focus:outline-2 focus:outline-blue-600"
                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
                @if ($errors->first('files.*'))
                    <p class="text-sm text-red-600">{{ $errors->first('files.*') }}</p>
                @else
                    <p class="text-sm text-gray-500">{{ __('Можно прикрепить несколько файлов до 5 МБ каждый') }}</p>
                @endif
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <x-ui::button type="button" class="w-auto px-4 h-11 bg-red-500 hover:bg-red-400 text-white"
                    wire:click="$set('showModal', false)">
                    {{ __('Отмена') }}
                </x-ui::button>
                <x-ui::button type="submit" class="w-auto px-5 h-11" loading="{{ __('Сохраняем...') }}">
                    {{ __('Сохранить') }}
                </x-ui::button>
            </div>
        </form>
    </x-ui::modal>

    <x-ui::confirm-modal :show="$showConfirm" :title="__('Удалить закупку?')" :message="__('Это действие нельзя отменить.')" confirmAction="confirmDelete"
        cancelAction="$set('showConfirm', false)" />
</div>

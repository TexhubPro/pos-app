<x-layouts.dashboard>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <x-ui::heading class="text-2xl">{{ __('Продажа') }} #{{ $sale->id }}</x-ui::heading>
                <x-ui::subheading>{{ __('Просмотр информации о продаже') }}</x-ui::subheading>
            </div>
            <a href="{{ route('sales') }}"
                class="inline-flex items-center justify-center h-11 rounded-xl bg-blue-600 text-white px-4 font-semibold hover:bg-blue-500 transition">
                {{ __('Вернуться к продажам') }}
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="space-y-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm space-y-3">
                    <div class="text-sm font-semibold text-gray-900">{{ __('Клиент и товар') }}</div>
                    <div class="text-sm text-gray-700 space-y-1.5">
                        <div><span class="text-gray-500">{{ __('Дата:') }}</span>
                            <span class="font-semibold text-gray-900">{{ $sale->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                        <div><span class="text-gray-500">{{ __('Клиент:') }}</span>
                            <span class="font-semibold text-gray-900">{{ $sale->client?->name ?? '—' }}</span>
                        </div>
                        <div><span class="text-gray-500">{{ __('Телефон:') }}</span>
                            <span class="font-semibold text-gray-900">{{ $sale->client?->phone ?? '—' }}</span>
                        </div>
                        <div><span class="text-gray-500">{{ __('Товар:') }}</span>
                            <span class="font-semibold text-gray-900">{{ $sale->product?->name ?? '—' }}</span>
                        </div>
                        <div><span class="text-gray-500">{{ __('Категория:') }}</span>
                            <span class="font-semibold text-gray-900">{{ $sale->product?->category?->name ?? '—' }}</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm space-y-3">
                    <div class="text-sm font-semibold text-gray-900">{{ __('Количество и цены') }}</div>
                    <div class="grid grid-cols-2 gap-3 text-sm text-gray-700">
                        <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="text-gray-500">{{ __('Коробок') }}</div>
                            <div class="text-lg font-semibold text-gray-900">{{ $sale->box_qty }}</div>
                        </div>
                        <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="text-gray-500">{{ __('Штук всего') }}</div>
                            <div class="text-lg font-semibold text-gray-900">{{ $sale->total_units }}</div>
                        </div>
                        <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="text-gray-500">{{ __('Цена/шт, $') }}</div>
                            <div class="text-lg font-semibold text-gray-900">{{ number_format($sale->price, 2) }}</div>
                        </div>
                        <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="text-gray-500">{{ __('Сумма, $') }}</div>
                            <div class="text-xl font-bold text-blue-700">{{ number_format($sale->total_price, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm space-y-3">
                    <div class="text-sm font-semibold text-gray-900">{{ __('Оплата') }}</div>
                    <div class="text-sm text-gray-700 space-y-1.5">
                        <div><span class="text-gray-500">{{ __('Тип:') }}</span>
                            <span class="font-semibold text-gray-900">
                                @if ($sale->payment_type === 'cash')
                                    {{ __('Наличные') }}
                                @elseif($sale->payment_type === 'debt')
                                    {{ __('В долг') }}
                                @else
                                    {{ __('Смешанный') }}
                                @endif
                            </span>
                        </div>
                        <div><span class="text-gray-500">{{ __('Метод:') }}</span>
                            <span class="font-semibold text-gray-900">
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
                            </span>
                        </div>
                        <div><span class="text-gray-500">{{ __('Наличными, $:') }}</span>
                            <span class="font-semibold text-gray-900">{{ number_format($sale->cash_amount, 2) }}</span>
                        </div>
                        <div><span class="text-gray-500">{{ __('Долг, $:') }}</span>
                            <span class="font-semibold text-gray-900">{{ number_format($sale->debt_amount, 2) }}</span>
                        </div>
                        <div><span class="text-gray-500">{{ __('Комментарий:') }}</span>
                            <span class="font-semibold text-gray-900">{{ $sale->comment ?: '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>

<x-layouts.dashboard>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <x-ui::heading class="text-2xl">{{ __('Клиент') }}: {{ $client->name }}</x-ui::heading>
                <x-ui::subheading>{{ __('Просмотр информации о клиенте') }}</x-ui::subheading>
            </div>
            <a href="{{ route('clients') }}"
                class="inline-flex items-center justify-center h-11 rounded-xl bg-blue-600 text-white px-4 font-semibold hover:bg-blue-500 transition">
                {{ __('Вернуться к списку') }}
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm space-y-3">
                <div class="text-sm font-semibold text-gray-900">{{ __('Контакты') }}</div>
                <div class="text-sm text-gray-700 space-y-1.5">
                    <div><span class="text-gray-500">{{ __('Имя:') }}</span>
                        <span class="font-semibold text-gray-900">{{ $client->name }}</span>
                    </div>
                    <div><span class="text-gray-500">{{ __('Телефон:') }}</span>
                        <span class="font-semibold text-gray-900">{{ $client->phone }}</span>
                    </div>
                    <div><span class="text-gray-500">{{ __('Адрес:') }}</span>
                        <span class="font-semibold text-gray-900">{{ $client->address ?: '—' }}</span>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm space-y-3">
                <div class="text-sm font-semibold text-gray-900">{{ __('Финансы и заметки') }}</div>
                <div class="text-sm text-gray-700 space-y-1.5">
                    <div><span class="text-gray-500">{{ __('Долг, $:') }}</span>
                        <span class="font-semibold text-gray-900">{{ number_format($client->debt, 2) }}</span>
                    </div>
                    <div><span class="text-gray-500">{{ __('Комментарий:') }}</span>
                        <span class="font-semibold text-gray-900">{{ $client->comment ?: '—' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-3">
            <div class="flex items-center gap-3">
                <button class="tab-btn px-4 py-2 rounded-xl text-sm font-semibold bg-blue-600 text-white"
                    data-target="tab-payments">{{ __('История оплат') }}</button>
                <button class="tab-btn px-4 py-2 rounded-xl text-sm font-semibold bg-gray-100 text-gray-700"
                    data-target="tab-sales">{{ __('История покупок') }}</button>
            </div>

            <div class="tab-panel" id="tab-payments">
                <x-ui::table class="min-w-full">
                    <x-slot:head>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Дата') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Метод') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Сумма, $') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Комментарий') }}</th>
                        </tr>
                    </x-slot:head>
                    @forelse ($payments as $payment)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                                {{ $payment->created_at->format('d.m.Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                                @switch($payment->method)
                                    @case('card_milli')
                                        {{ __('Корти Милли') }}
                                        @break
                                    @case('dushanbe_city')
                                        {{ __('Душанбе Сити') }}
                                        @break
                                    @case('alif')
                                        {{ __('Алиф') }}
                                        @break
                                    @case('debt')
                                        {{ __('В долг') }}
                                        @break
                                    @default
                                        {{ __('Наличные') }}
                                @endswitch
                            </td>
                            @php
                                $isDebt = ($payment->method ?? '') === 'debt';
                                $signClass = $isDebt ? 'text-red-600' : 'text-green-600';
                                $sign = $isDebt ? '-' : '+';
                            @endphp
                            <td class="px-4 py-3 text-sm font-semibold whitespace-nowrap {{ $signClass }}">
                                {{ $sign }}{{ number_format($payment->amount, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap max-w-[14rem]">
                                <span class="truncate block">{{ $payment->comment ?: '—' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500">{{ __('Оплат пока нет') }}</td>
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
                                {{ __('Товар') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Кол-во (шт/кор)') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Итого, $') }}</th>
                        </tr>
                    </x-slot:head>
                    @forelse ($sales as $sale)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                                {{ $sale->created_at->format('d.m.Y') }}
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500">{{ __('Покупок пока нет') }}</td>
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
                    document.getElementById(targetId)?.classList.remove('hidden');
                });
            });
        });
    </script>
</x-layouts.dashboard>

@php
    $methodLabels = [
        'cash' => __('Наличные'),
        'card_milli' => __('Карта Milli'),
        'dushanbe_city' => __('Душанбе сити'),
        'alif' => 'Alif',
        'debt' => __('В долг'),
    ];
@endphp

<x-layouts.dashboard>
    <div class="space-y-6">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="space-y-1">
                <x-ui::heading class="text-2xl">{{ $firm->name }}</x-ui::heading>
                <x-ui::subheading>{{ __('Карточка поставщика и история оплат') }}</x-ui::subheading>
            </div>
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm px-5 py-3 text-right min-w-[220px]">
                <p class="text-sm font-semibold text-gray-600">{{ __('Наш долг') }}</p>
                <p class="text-2xl font-bold text-gray-900">${{ number_format($firm->debt, 2) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 space-y-2">
                <p class="text-sm font-semibold text-gray-500">{{ __('Телефон') }}</p>
                <p class="text-lg font-semibold text-gray-900">{{ $firm->phone ?: '—' }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 space-y-2 md:col-span-2">
                <p class="text-sm font-semibold text-gray-500">{{ __('Комментарий') }}</p>
                <p class="text-gray-800">{{ $firm->comment ?? '—' }}</p>
            </div>
        </div>

        <div class="space-y-3">
            <div class="flex items-center gap-3">
                <button class="tab-btn px-4 py-2 rounded-xl text-sm font-semibold bg-blue-600 text-white"
                    data-target="payments">{{ __('История оплат') }}</button>
                <button class="tab-btn px-4 py-2 rounded-xl text-sm font-semibold bg-gray-100 text-gray-700"
                    data-target="purchases">{{ __('Закупки у фирмы') }}</button>
            </div>

            <div class="tab-panel" id="payments">
                <x-ui::table class="min-w-full">
                    <x-slot:head>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Дата') }}
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Комментарий') }}
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Метод') }}
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Сумма, $') }}
                            </th>
                        </tr>
                    </x-slot:head>

                    @forelse ($payments as $payment)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                                {{ $payment->created_at->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800 whitespace-nowrap max-w-[18rem]">
                                <span class="truncate block">{{ $payment->comment ?: '—' }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                                {{ $methodLabels[$payment->method] ?? $payment->method ?? '—' }}
                            </td>
                            @php
                                $isDebt = ($payment->method ?? '') === 'debt';
                                $signClass = $isDebt ? 'text-red-600' : 'text-green-600';
                                $sign = $isDebt ? '-' : '+';
                            @endphp
                            <td class="px-4 py-3 text-sm font-semibold text-right whitespace-nowrap {{ $signClass }}">
                                {{ $sign }}{{ number_format($payment->amount, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500">{{ __('Оплат пока нет') }}</td>
                        </tr>
                    @endforelse
                </x-ui::table>
            </div>

            <div class="tab-panel hidden" id="purchases">
                <x-ui::table class="min-w-full">
                    <x-slot:head>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Дата') }}
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Товар') }}
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Коробок') }}
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Цена/кор., $') }}
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                                {{ __('Сумма, $') }}
                            </th>
                        </tr>
                    </x-slot:head>
                    @forelse ($purchases as $purchase)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                                {{ $purchase->created_at->format('d.m.Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800 whitespace-nowrap max-w-[18rem]">
                                <span class="truncate block">{{ $purchase->product?->name ?? '—' }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $purchase->box_qty ?? 0 }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                                {{ number_format($purchase->purchase_price, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm font-semibold text-right text-gray-900 whitespace-nowrap">
                                {{ number_format(($purchase->purchase_price ?? 0) * ($purchase->box_qty ?? 0), 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">{{ __('Закупок пока нет') }}</td>
                        </tr>
                    @endforelse
                </x-ui::table>
            </div>
        </div>
    </div>
</x-layouts.dashboard>

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

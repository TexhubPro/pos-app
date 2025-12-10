<div>
<div id="dashboard-root">
        <div class="space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="space-y-1">
                    <x-ui::heading class="text-2xl">{{ __('Дашборд') }}</x-ui::heading>
                    <x-ui::subheading>{{ __('Сводка продаж, закупок и финансов') }}</x-ui::subheading>
                </div>
                <div class="flex items-center gap-2 bg-white rounded-2xl border border-gray-200 shadow-sm p-1">
                    @foreach ($timeframes as $key => $label)
                        <button type="button" class="time-btn px-4 py-2 rounded-xl text-sm font-semibold"
                            data-key="{{ $key }}" data-active="{{ $key === 'week' ? '1' : '0' }}">
                            {{ __($label) }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <div class="stat-card bg-gradient-to-br from-blue-600 to-blue-500 text-white">
                    <p class="text-sm font-semibold opacity-80">{{ __('Выручка') }}</p>
                    <p class="text-3xl font-bold leading-tight" data-field="revenue">$0</p>
                    <span class="text-xs opacity-80">{{ __('Всего оплачено') }}</span>
                </div>
                <div class="stat-card bg-white border border-gray-200 text-gray-900">
                    <p class="text-sm font-semibold text-gray-600">{{ __('Закупки + доставка') }}</p>
                    <p class="text-2xl font-bold" data-field="purchases">$0</p>
                    <span class="text-xs text-gray-500">{{ __('Включая доставку:') }} <span
                            data-field="delivery">$0</span></span>
                </div>
                <div class="stat-card bg-white border border-gray-200 text-gray-900">
                    <p class="text-sm font-semibold text-gray-600">{{ __('Продажи (кол-во)') }}</p>
                    <p class="text-2xl font-bold" data-field="salesCount">0</p>
                    <span class="text-xs text-gray-500">{{ __('Сделок за период') }}</span>
                </div>
                <div class="stat-card bg-white border border-gray-200 text-gray-900">
                    <p class="text-sm font-semibold text-gray-600">{{ __('Чистая прибыль') }}</p>
                    <p class="text-2xl font-bold text-green-600" data-field="net">$0</p>
                    <span class="text-xs text-gray-500">{{ __('После расходов и доставок') }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <div class="stat-card bg-white border border-gray-200 text-gray-900">
                    <p class="text-sm font-semibold text-gray-600">{{ __('Сегодня: выручка') }}</p>
                    <p class="text-2xl font-bold text-blue-600">
                        {{ number_format($today['revenue'], 2, '.', ' ') }} $
                    </p>
                    <span class="text-xs text-gray-500">{{ __('Сделок') }}: {{ $today['salesCount'] }}</span>
                </div>
                <div class="stat-card bg-white border border-gray-200 text-gray-900">
                    <p class="text-sm font-semibold text-gray-600">{{ __('Сегодня: продажи шт') }}</p>
                    <p class="text-2xl font-bold">
                        {{ number_format($today['unitsSold'] ?? 0, 0, '.', ' ') }}
                    </p>
                    <span class="text-xs text-gray-500">{{ __('Товаров продано') }}</span>
                </div>
                <div class="stat-card bg-white border border-gray-200 text-gray-900">
                    <p class="text-sm font-semibold text-gray-600">{{ __('Сегодня: расходы') }}</p>
                    <p class="text-2xl font-bold text-red-600">
                        {{ number_format($today['expenses'], 2, '.', ' ') }} $
                    </p>
                    <span class="text-xs text-gray-500">{{ __('Включая закупки') }}: {{ number_format($today['purchases'], 2, '.', ' ') }} $</span>
                </div>
                <div class="stat-card bg-gradient-to-br from-emerald-500 to-emerald-400 text-white">
                    <p class="text-sm font-semibold opacity-90">{{ __('Сегодня: итог') }}</p>
                    <p class="text-3xl font-bold leading-tight">
                        {{ number_format($today['net'], 2, '.', ' ') }} $
                    </p>
                    <span class="text-xs opacity-90">{{ __('Выручка - расходы - закупки') }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <p class="text-lg font-semibold text-gray-900">{{ __('Динамика выручки') }}</p>
                        <span class="text-xs font-semibold text-gray-500">{{ __('Сравнение внутри периода') }}</span>
                    </div>
                    <div class="h-48 flex items-end gap-2" id="chart-bars">
                        <!-- bars injected by JS -->
                    </div>
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>{{ __('Начало периода') }}</span>
                        <span>{{ __('Конец периода') }}</span>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 space-y-3">
                    <p class="text-lg font-semibold text-gray-900">{{ __('Финансовый поток') }}</p>
                    <div class="space-y-3">
                        <div class="flow-row">
                            <div class="flex items-center gap-2">
                                <span class="dot bg-green-500"></span>
                                <p class="text-sm font-semibold text-gray-700">{{ __('Пополнения') }}</p>
                            </div>
                            <p class="text-base font-bold text-gray-900" data-field="deposits">$0</p>
                        </div>
                        <div class="flow-row">
                            <div class="flex items-center gap-2">
                                <span class="dot bg-red-500"></span>
                                <p class="text-sm font-semibold text-gray-700">{{ __('Расходы') }}</p>
                            </div>
                            <p class="text-base font-bold text-gray-900" data-field="expenses">$0</p>
                        </div>
                        <div class="flow-row">
                            <div class="flex items-center gap-2">
                                <span class="dot bg-blue-500"></span>
                                <p class="text-sm font-semibold text-gray-700">{{ __('Оплачено долгов') }}</p>
                            </div>
                            <p class="text-base font-bold text-gray-900" data-field="debtPaid">$0</p>
                        </div>
                        <div class="flow-row">
                            <div class="flex items-center gap-2">
                                <span class="dot bg-amber-500"></span>
                                <p class="text-sm font-semibold text-gray-700">{{ __('Взято в долг') }}</p>
                            </div>
                            <p class="text-base font-bold text-gray-900" data-field="debtTaken">$0</p>
                        </div>
                    </div>
                    <div class="mt-4 h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                        <div id="flow-progress" class="h-full bg-gradient-to-r from-green-500 via-blue-500 to-red-500"
                            style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <p class="text-lg font-semibold text-gray-900">{{ __('Топ товары') }}</p>
                        <span class="text-xs font-semibold text-gray-500">{{ __('Сумма / шт') }}</span>
                    </div>
                    <div class="divide-y divide-gray-100" id="top-products">
                        <!-- filled by JS -->
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <p class="text-lg font-semibold text-gray-900">{{ __('Топ клиенты') }}</p>
                        <span class="text-xs font-semibold text-gray-500">{{ __('Сумма / долг') }}</span>
                    </div>
                    <div class="divide-y divide-gray-100" id="top-clients">
                        <!-- filled by JS -->
                    </div>
                </div>
            </div>
        </div>

        <style>
            .stat-card {
                border-radius: 1rem;
                box-shadow: 0 12px 30px -16px rgba(15, 23, 42, 0.25);
                padding: 1rem;
            }

            .flow-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                border-radius: 0.9rem;
                border: 1px solid #e5e7eb;
                padding: 0.55rem 0.8rem;
                background: #f8fafc;
            }

            .dot {
                display: inline-block;
                width: 10px;
                height: 10px;
                border-radius: 9999px;
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const dataset = @json($data);
                const buttons = document.querySelectorAll('.time-btn');

                function format(num, prefix = '$', decimals = 0) {
                    return `${prefix}${Number(num).toLocaleString('ru-RU', { minimumFractionDigits: decimals, maximumFractionDigits: decimals })}`;
                }

                function renderBars(values) {
                    const container = document.getElementById('chart-bars');
                    container.innerHTML = '';
                    const max = Math.max(...values, 1);
                    values.forEach(v => {
                        const h = (v / max) * 100;
                        const bar = document.createElement('div');
                        bar.className =
                            'flex-1 rounded-lg bg-gradient-to-t from-blue-100 via-blue-400 to-blue-600';
                        bar.style.height = `${h}%`;
                        container.appendChild(bar);
                    });
                }

                function renderList(items, targetId, isClient = false) {
                    const wrap = document.getElementById(targetId);
                    wrap.innerHTML = '';
                    items.forEach(item => {
                        const row = document.createElement('div');
                        row.className = 'flex items-center justify-between py-3';
                        row.innerHTML = `
                    <div class="space-y-0.5">
                        <p class="text-sm font-semibold text-gray-900">${item.name}</p>
                        <p class="text-xs text-gray-500">${isClient ? '{{ __('Долг') }}: ' + format(item.debt, '$', 0) : '{{ __('Продано') }}: ' + item.qty + ' шт'}</p>
                    </div>
                    <p class="text-base font-bold text-gray-900">${format(item.sum, '$', 0)}</p>
                `;
                        wrap.appendChild(row);
                    });
                }

                function recalc(key) {
                    const d = dataset[key];
                    const fieldEls = document.querySelectorAll('[data-field]');
                    fieldEls.forEach(el => {
                        const field = el.dataset.field;
                        let val = d[field] ?? 0;
                        if (field === 'salesCount') {
                            el.textContent = Number(val).toLocaleString('ru-RU');
                        } else {
                            el.textContent = format(val, '$', 0);
                        }
                    });
                    const totalFlow = (d.deposits || 0) + (d.expenses || 0) + (d.debtPaid || 0) + (d.debtTaken || 0);
                    const flowEl = document.getElementById('flow-progress');
                    flowEl.style.width = totalFlow > 0 ? `${Math.min(100, ((d.deposits || 0) / totalFlow) * 100)}%` :
                        '0%';

                    renderBars(d.chart || []);
                    renderList(d.topProducts || [], 'top-products', false);
                    renderList(d.topClients || [], 'top-clients', true);
                }

                buttons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        buttons.forEach(b => {
                            b.dataset.active = '0';
                            b.classList.remove('bg-blue-600', 'text-white');
                            b.classList.add('bg-white', 'text-gray-700', 'border',
                                'border-gray-200');
                        });
                        btn.dataset.active = '1';
                        btn.classList.add('bg-blue-600', 'text-white');
                        btn.classList.remove('bg-white', 'text-gray-700', 'border', 'border-gray-200');
                        recalc(btn.dataset.key);
                    });
                });

                const defaultBtn = document.querySelector('.time-btn[data-key="week"]');
                if (defaultBtn) {
                    defaultBtn.click();
                } else {
                    recalc('week');
                }
            });
        </script>
    </div>
</div>

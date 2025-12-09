<div class="space-y-6">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="space-y-1">
            <x-ui::heading class="text-2xl">{{ __('Расходы') }}</x-ui::heading>
            <x-ui::subheading>{{ __('Учёт затрат с отражением в банке') }}</x-ui::subheading>
        </div>
        <div class="flex justify-end">
            <x-ui::button class="px-5" wire:click="$set('showModal', true)">{{ __('Добавить расход') }}</x-ui::button>
        </div>
    </div>

    @if (session('status'))
        <x-ui::alert type="success" :messages="[session('status')]" />
    @endif


    <x-ui::modal :show="$showModal" :title="__('Добавить расход')" closeAction="$set('showModal', false)" wire:key="expense-modal">
        <form class="space-y-3" wire:submit.prevent="submit">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-3">
                <div class="space-y-1.5">
                    <x-ui::label for="amount">{{ __('Сумма, $') }}</x-ui::label>
                    <x-ui::input id="amount" type="number" step="0.01" min="0" wire:model.live="amount"
                        :error="$errors->first('amount')" />
                </div>
                <div class="space-y-1.5">
                    <x-ui::label for="source">{{ __('Статья/категория') }}</x-ui::label>
                    <select id="source" wire:model="source"
                        class="h-12 w-full rounded-xl bg-white border border-gray-200 px-3 text-base font-semibold text-gray-800 focus:outline-2 focus:outline-blue-600">
                        <option value="">{{ __('Выберите категорию') }}</option>
                        <option value="rent">{{ __('Аренда') }}</option>
                        <option value="salary">{{ __('Зарплата') }}</option>
                        <option value="logistics">{{ __('Логистика / доставка') }}</option>
                        <option value="tax">{{ __('Налоги и сборы') }}</option>
                        <option value="inventory">{{ __('Закупка материалов') }}</option>
                        <option value="marketing">{{ __('Маркетинг') }}</option>
                        <option value="services">{{ __('Услуги / подписки') }}</option>
                        <option value="other">{{ __('Прочее') }}</option>
                    </select>
                    @if ($errors->first('source'))
                        <p class="text-sm text-red-600">{{ $errors->first('source') }}</p>
                    @endif
                </div>
                <div class="space-y-1.5">
                    <x-ui::label for="method">{{ __('Способ оплаты') }}</x-ui::label>
                    <select id="method" wire:model="method"
                        class="h-12 w-full rounded-xl bg-white border border-gray-200 px-3 text-base font-semibold text-gray-800 focus:outline-2 focus:outline-blue-600">
                        <option value="cash">{{ __('Наличные') }}</option>
                        <option value="card_milli">{{ __('Карта Milli') }}</option>
                        <option value="dushanbe_city">{{ __('Душанбе сити') }}</option>
                        <option value="alif">{{ __('Alif') }}</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <x-ui::label for="comment">{{ __('Комментарий') }}</x-ui::label>
                    <x-ui::input id="comment" type="text" wire:model="comment" :error="$errors->first('comment')"
                        placeholder="{{ __('Краткое описание') }}" />
                </div>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2">
                <x-ui::button type="button" class="bg-red-500 hover:bg-red-400" wire:click="$set('showModal', false)">
                    {{ __('Отмена') }}
                </x-ui::button>
                <x-ui::button type="submit">
                    {{ __('Списать расход') }}
                </x-ui::button>
            </div>
        </form>
    </x-ui::modal>

    <div class="space-y-3">
        <p class="text-lg font-semibold text-gray-900">{{ __('Последние расходы') }}</p>
        <x-ui::table class="min-w-full">
            <x-slot:head>
                <tr>
                    <th
                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                        {{ __('Дата') }}
                    </th>
                    <th
                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                        {{ __('Категория') }}
                    </th>
                    <th
                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                        {{ __('Метод') }}
                    </th>
                    <th
                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                        {{ __('Комментарий') }}
                    </th>
                    <th
                        class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                        {{ __('Сумма, $') }}
                    </th>
                </tr>
            </x-slot:head>

            @php
                $methodLabels = [
                    'cash' => __('Наличные'),
                    'card_milli' => __('Карта Milli'),
                    'dushanbe_city' => __('Душанбе сити'),
                    'alif' => 'Alif',
                ];
                $categoryLabels = [
                    'rent' => __('Аренда'),
                    'salary' => __('Зарплата'),
                    'logistics' => __('Логистика / доставка'),
                    'tax' => __('Налоги и сборы'),
                    'inventory' => __('Закупка материалов'),
                    'marketing' => __('Маркетинг'),
                    'services' => __('Услуги / подписки'),
                    'other' => __('Прочее'),
                ];
            @endphp

            @forelse ($expenses as $expense)
                <tr class="hover:bg-gray-50/60 transition">
                    <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                        {{ $expense->created_at->format('d.m.Y H:i') }}
                    </td>
                    <td class="px-4 py-3 text-sm font-semibold text-gray-900 whitespace-nowrap max-w-[14rem]">
                        <span class="truncate block">{{ $categoryLabels[$expense->source] ?? ($expense->source ?: '—') }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                        {{ $methodLabels[$expense->method] ?? ($expense->method ?? '—') }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap max-w-[16rem]">
                        <span class="truncate block">{{ $expense->comment ?: '—' }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm font-semibold text-right text-red-600 whitespace-nowrap">
                        -{{ number_format($expense->amount, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">{{ __('Расходов пока нет') }}</td>
                </tr>
            @endforelse
        </x-ui::table>
    </div>
</div>

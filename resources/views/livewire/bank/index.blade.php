<div class="space-y-6">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="space-y-1">
            <x-ui::heading class="text-2xl">{{ __('Банк') }}</x-ui::heading>
            <x-ui::subheading>{{ __('Баланс и движения средств') }}</x-ui::subheading>
        </div>
        <div class="grid lg:flex gap-3 flex-wrap w-full lg:w-fit">
            <div
                class="bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-2xl px-6 py-4 shadow-lg flex flex-col gap-1 min-w-[220px]">
                <p class="text-sm font-semibold opacity-90">{{ __('Текущий баланс') }}</p>
                <p class="text-3xl font-bold leading-tight">${{ number_format($this->balance, 2) }}</p>
            </div>
            <div
                class="bg-white border border-gray-200 rounded-2xl px-6 py-4 shadow-sm flex flex-col gap-1 min-w-[200px]">
                <p class="text-sm font-semibold text-gray-600">{{ __('Долг перед фирмами') }}</p>
                <p class="text-2xl font-bold text-gray-900">${{ number_format($this->firmDebt, 2) }}</p>
            </div>
            <div
                class="bg-white border border-gray-200 rounded-2xl px-6 py-4 shadow-sm flex flex-col gap-1 min-w-[200px]">
                <p class="text-sm font-semibold text-gray-600">{{ __('Долг клиентов перед нами') }}</p>
                <p class="text-2xl font-bold text-gray-900">${{ number_format($this->clientDebt, 2) }}</p>
            </div>
            <div
                class="bg-white border border-gray-200 rounded-2xl px-6 py-4 shadow-sm flex flex-col gap-1 min-w-[220px]">
                <p class="text-sm font-semibold text-gray-600">{{ __('Чистая прибыль (всего)') }}</p>
                <p class="text-2xl font-bold text-emerald-600">${{ number_format($this->totalProfit, 2, '.', ' ') }}</p>
            </div>
        </div>
    </div>

    @if (session('status'))
        <x-ui::alert type="success" :messages="[session('status')]" />
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 space-y-2">
            <p class="text-base font-semibold text-gray-900">{{ __('Вложение средств') }}</p>
            <p class="text-sm text-gray-600">{{ __('Пополнить баланс') }}</p>
            <x-ui::button class="w-full" wire:click="$set('showDepositModal', true)">
                {{ __('Добавить вклад') }}
            </x-ui::button>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 space-y-2">
            <p class="text-base font-semibold text-gray-900">{{ __('Снять/выдать') }}</p>
            <p class="text-sm text-gray-600">{{ __('Учитываем расход') }}</p>
            <x-ui::button class="w-full bg-red-500 hover:bg-red-400" wire:click="$set('showWithdrawModal', true)">
                {{ __('Учесть снятие') }}
            </x-ui::button>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 space-y-2">
            <p class="text-base font-semibold text-gray-900">{{ __('Оплата клиента') }}</p>
            <p class="text-sm text-gray-600">{{ __('Погашение долга') }}</p>
            <x-ui::button class="w-full" wire:click="$set('showClientModal', true)">
                {{ __('Добавить оплату') }}
            </x-ui::button>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 space-y-2">
            <p class="text-base font-semibold text-gray-900">{{ __('Оплата фирме') }}</p>
            <p class="text-sm text-gray-600">{{ __('Списание долга') }}</p>
            <x-ui::button class="w-full bg-red-500 hover:bg-red-400" wire:click="$set('showFirmModal', true)">
                {{ __('Списать оплату') }}
            </x-ui::button>
        </div>
    </div>

    <x-ui::modal :show="$showDepositModal" :title="__('Вложение средств')" closeAction="$set('showDepositModal', false)"
        wire:key="bank-deposit-modal">
        <form class="space-y-3" wire:submit.prevent="addDeposit">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="space-y-1.5">
                    <x-ui::label for="deposit_amount">{{ __('Сумма, $') }}</x-ui::label>
                    <x-ui::input id="deposit_amount" type="number" step="0.01" min="0"
                        wire:model.live="deposit_amount" :error="$errors->first('deposit_amount')" />
                </div>
                <div class="space-y-1.5">
                    <x-ui::label for="deposit_source">{{ __('От кого') }}</x-ui::label>
                    <select id="deposit_source" wire:model="deposit_source"
                        class="h-12 w-full rounded-xl bg-white border border-gray-200 px-3 text-base font-semibold text-gray-800 focus:outline-2 focus:outline-blue-600">
                        <option value="Салмон">Салмон</option>
                        <option value="Джамшед">Джамшед</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2">
                <x-ui::button type="button" class="bg-red-500 hover:bg-red-400"
                    wire:click="$set('showDepositModal', false)">
                    {{ __('Отмена') }}
                </x-ui::button>
                <x-ui::button type="submit">
                    {{ __('Добавить вклад') }}
                </x-ui::button>
            </div>
        </form>
    </x-ui::modal>

    <x-ui::modal :show="$showWithdrawModal" :title="__('Снять/выдать')" closeAction="$set('showWithdrawModal', false)"
        wire:key="bank-withdraw-modal">
        <form class="space-y-3" wire:submit.prevent="addWithdraw">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="space-y-1.5">
                    <x-ui::label for="withdraw_amount">{{ __('Сумма, $') }}</x-ui::label>
                    <x-ui::input id="withdraw_amount" type="number" step="0.01" min="0"
                        wire:model.live="withdraw_amount" :error="$errors->first('withdraw_amount')" />
                </div>
                <div class="space-y-1.5">
                    <x-ui::label for="withdraw_comment">{{ __('Комментарий') }}</x-ui::label>
                    <x-ui::input id="withdraw_comment" type="text" wire:model="withdraw_comment" :error="$errors->first('withdraw_comment')"
                        placeholder="{{ __('Например: расходы, перевод') }}" />
                </div>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2">
                <x-ui::button type="button" class="bg-red-500 hover:bg-red-400"
                    wire:click="$set('showWithdrawModal', false)">
                    {{ __('Отмена') }}
                </x-ui::button>
                <x-ui::button type="submit">
                    {{ __('Учесть снятие') }}
                </x-ui::button>
            </div>
        </form>
    </x-ui::modal>

    <x-ui::modal :show="$showClientModal" :title="__('Оплата клиента')" closeAction="$set('showClientModal', false)"
        wire:key="bank-client-modal">
        <form class="space-y-3" wire:submit.prevent="addClientPayment">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="space-y-1.5">
                    <x-ui::label for="client_id">{{ __('Клиент') }}</x-ui::label>
                    <select id="client_id" wire:model="client_id"
                        class="h-12 w-full rounded-xl bg-white border border-gray-200 px-3 text-base font-semibold text-gray-800 focus:outline-2 focus:outline-blue-600">
                        <option value="">{{ __('Выберите клиента') }}</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->first('client_id'))
                        <p class="text-sm text-red-600">{{ $errors->first('client_id') }}</p>
                    @endif
                </div>
                <div class="space-y-1.5">
                    <x-ui::label for="client_amount">{{ __('Сумма, $') }}</x-ui::label>
                    <x-ui::input id="client_amount" type="number" step="0.01" min="0"
                        wire:model.live="client_amount" :error="$errors->first('client_amount')" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="space-y-1.5">
                    <x-ui::label for="client_method">{{ __('Способ оплаты') }}</x-ui::label>
                    <select id="client_method" wire:model="client_method"
                        class="h-12 w-full rounded-xl bg-white border border-gray-200 px-3 text-base font-semibold text-gray-800 focus:outline-2 focus:outline-blue-600">
                        <option value="cash">{{ __('Наличные') }}</option>
                        <option value="card_milli">{{ __('Карта Milli') }}</option>
                        <option value="dushanbe_city">{{ __('Душанбе сити') }}</option>
                        <option value="alif">{{ __('Alif') }}</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <x-ui::label for="client_comment">{{ __('Комментарий') }}</x-ui::label>
                    <x-ui::input id="client_comment" type="text" wire:model="client_comment" :error="$errors->first('client_comment')"
                        placeholder="{{ __('Например: погашение долга') }}" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <x-ui::button type="button" class="bg-red-500 hover:bg-red-400"
                    wire:click="$set('showClientModal', false)">
                    {{ __('Отмена') }}
                </x-ui::button>
                <x-ui::button type="submit">
                    {{ __('Добавить оплату') }}
                </x-ui::button>
            </div>
        </form>
    </x-ui::modal>

    <x-ui::modal :show="$showFirmModal" :title="__('Оплата фирме')" closeAction="$set('showFirmModal', false)"
        wire:key="bank-firm-modal">
        <form class="space-y-3" wire:submit.prevent="addFirmPayment">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="space-y-1.5">
                    <x-ui::label for="firm_id">{{ __('Фирма') }}</x-ui::label>
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
                <div class="space-y-1.5">
                    <x-ui::label for="firm_amount">{{ __('Сумма, $') }}</x-ui::label>
                    <x-ui::input id="firm_amount" type="number" step="0.01" min="0"
                        wire:model.live="firm_amount" :error="$errors->first('firm_amount')" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="space-y-1.5">
                    <x-ui::label for="firm_method">{{ __('Способ оплаты') }}</x-ui::label>
                    <select id="firm_method" wire:model="firm_method"
                        class="h-12 w-full rounded-xl bg-white border border-gray-200 px-3 text-base font-semibold text-gray-800 focus:outline-2 focus:outline-blue-600">
                        <option value="cash">{{ __('Наличные') }}</option>
                        <option value="card_milli">{{ __('Карта Milli') }}</option>
                        <option value="dushanbe_city">{{ __('Душанбе сити') }}</option>
                        <option value="alif">{{ __('Alif') }}</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <x-ui::label for="firm_comment">{{ __('Комментарий') }}</x-ui::label>
                    <x-ui::input id="firm_comment" type="text" wire:model="firm_comment" :error="$errors->first('firm_comment')"
                        placeholder="{{ __('Например: оплата доставки') }}" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <x-ui::button type="button" class="bg-red-500 hover:bg-red-400"
                    wire:click="$set('showFirmModal', false)">
                    {{ __('Отмена') }}
                </x-ui::button>
                <x-ui::button type="submit">
                    {{ __('Списать оплату') }}
                </x-ui::button>
            </div>
        </form>
    </x-ui::modal>

    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <p class="text-lg font-semibold text-gray-900">{{ __('Последние операции') }}</p>
        </div>

        <x-ui::table class="min-w-full">
            <x-slot:head>
                <tr>
                    <th
                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                        {{ __('Дата') }}
                    </th>
                    <th
                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                        {{ __('Тип') }}
                    </th>
                    <th
                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                        {{ __('Детали') }}
                    </th>
                    <th
                        class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                        {{ __('Сумма, $') }}
                    </th>
                </tr>
            </x-slot:head>

            @php
            $typeLabels = [
                'deposit' => __('Вклад'),
                'withdraw' => __('Снятие'),
                'client_payment' => __('Оплата клиента'),
                'firm_payment' => __('Оплата фирме'),
                'expense' => __('Расход'),
            ];
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

        @forelse ($transactions as $tx)
            @php
                $sign = in_array($tx->type, ['withdraw', 'firm_payment', 'expense']) ? '-' : '+';
                $detail = $tx->comment;
                if (!$detail) {
                    $detail = $tx->type === 'expense' ? ($categoryLabels[$tx->source] ?? $tx->source) : $tx->source;
                }
                if (!$detail && $tx->client) {
                    $detail = $tx->client->name;
                }
                if (!$detail && $tx->firm) {
                        $detail = $tx->firm->name;
                    }
                @endphp
                <tr class="hover:bg-gray-50/60 transition">
                    <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                        {{ $tx->created_at->format('d.m.Y H:i') }}
                    </td>
                    <td class="px-4 py-3 text-sm font-semibold text-gray-900 whitespace-nowrap">
                        {{ $typeLabels[$tx->type] ?? $tx->type }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                        <span class="truncate block">
                            {{ $detail ?: '—' }}
                            @if ($tx->method)
                                · {{ $methodLabels[$tx->method] ?? $tx->method }}
                            @endif
                        </span>
                    </td>
                    <td
                        class="px-4 py-3 text-sm font-semibold text-right whitespace-nowrap {{ $sign === '+' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $sign }}{{ number_format($tx->amount, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">{{ __('Пока нет операций') }}</td>
                </tr>
            @endforelse
        </x-ui::table>
    </div>
</div>

<div class="space-y-6">
    <div class="flex items-start justify-between gap-3 flex-wrap">
        <div class="space-y-1">
            <x-ui::heading class="text-2xl">{{ __('Касса') }}</x-ui::heading>
            <x-ui::subheading>{{ __('Оформление продажи') }}</x-ui::subheading>
        </div>
        <a href="{{ route('sales') }}"
            class="inline-flex items-center justify-center h-11 rounded-xl bg-gray-100 text-gray-800 px-4 font-semibold hover:bg-gray-200 transition">
            {{ __('История продаж') }}
        </a>
    </div>

    @if (session('status'))
        <x-ui::alert type="success" :messages="[session('status')]" />
    @endif
    @error('quantity')
        <x-ui::alert type="danger" :messages="[$message]" />
    @enderror

    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm space-y-4">
        <form class="space-y-4" wire:submit.prevent="submit">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <x-ui::label for="client_id">{{ __('Клиент') }}</x-ui::label>
                    <select id="client_id" wire:model="client_id"
                        class="h-12 w-full rounded-xl bg-white border border-gray-200 px-3 text-base font-semibold text-gray-800 focus:outline-2 focus:outline-blue-600">
                        <option value="">{{ __('Выберите клиента') }}</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->phone }})</option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="space-y-1.5">
                    <x-ui::label for="product_id">{{ __('Товар') }}</x-ui::label>
                    <select id="product_id" wire:model="product_id"
                        class="h-12 w-full rounded-xl bg-white border border-gray-200 px-3 text-base font-semibold text-gray-800 focus:outline-2 focus:outline-blue-600">
                        <option value="">{{ __('Выберите товар') }}</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->category->name . ' - ' . $product->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="space-y-1.5">
                    <x-ui::label for="box_qty">{{ __('Коробок') }}</x-ui::label>
                    <x-ui::input id="box_qty" type="number" min="0" wire:model.live="box_qty" />
                </div>
                <div class="space-y-1.5">
                    <x-ui::label for="unit_qty">{{ __('Штук') }}</x-ui::label>
                    <x-ui::input id="unit_qty" type="number" min="0" wire:model.live="unit_qty" />
                </div>
                <div class="space-y-1.5">
                    <x-ui::label for="price_box">{{ __('Цена за коробку, $') }}</x-ui::label>
                    <x-ui::input id="price_box" type="text" inputmode="decimal"
                        wire:model.live.debounce.2000ms="price_box" />
                </div>
                <div class="space-y-1.5">
                    <x-ui::label for="price_unit">{{ __('Цена за 1 шт, $') }}</x-ui::label>
                    <x-ui::input id="price_unit" type="text" inputmode="decimal"
                        wire:model.live.debounce.2000ms="price_unit" />
                </div>
            </div>

            <div class="grid lg:grid-cols-2 gap-3">
                <div class="space-y-1.5">
                    <x-ui::label for="payment_type">{{ __('Способ оплаты') }}</x-ui::label>
                    <select id="payment_type" wire:model.live="payment_type"
                        class="h-12 w-full rounded-xl bg-white border border-gray-200 px-3 text-base font-semibold text-gray-800 focus:outline-2 focus:outline-blue-600">
                        <option value="cash">{{ __('Наличные') }}</option>
                        <option value="debt">{{ __('В долг') }}</option>
                        <option value="mixed">{{ __('Смешанный') }}</option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <x-ui::label for="payment_method">{{ __('Метод оплаты (касса)') }}</x-ui::label>
                    <select id="payment_method" wire:model.live="payment_method"
                        class="h-12 w-full rounded-xl bg-white border border-gray-200 px-3 text-base font-semibold text-gray-800 focus:outline-2 focus:outline-blue-600">
                        <option value="cash">{{ __('Наличные') }}</option>
                        <option value="card_milli">{{ __('Корти Милли') }}</option>
                        <option value="dushanbe_city">{{ __('Душанбе Сити') }}</option>
                        <option value="alif">{{ __('Алиф') }}</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <x-ui::label for="cash_amount">{{ __('Наличными, $') }}</x-ui::label>
                    <x-ui::input id="cash_amount" type="text" inputmode="decimal"
                        wire:model.live.debounce.2000ms="cash_amount"
                        placeholder="{{ __('Сколько клиент дал наличными') }}" />
                </div>
                <div class="space-y-1.5">
                    <x-ui::label for="debt_amount">{{ __('В долг, $') }}</x-ui::label>
                    <x-ui::input id="debt_amount" type="number" min="0" step="0.01"
                        class="bg-gray-100 text-gray-500" disabled wire:model="debt_amount" />
                    <p class="text-xs text-gray-500">{{ __('Остаток автоматически пойдёт в долг клиента') }}</p>
                </div>
            </div>

            <div class="space-y-1.5">
                <x-ui::label for="comment">{{ __('Комментарий') }}</x-ui::label>
                <textarea id="comment" wire:model.defer="comment"
                    class="w-full min-h-[80px] rounded-xl bg-white border border-gray-200 px-3 py-2 text-sm font-semibold text-gray-800 focus:outline-2 focus:outline-blue-600"
                    placeholder="{{ __('Заметки к продаже') }}"></textarea>
            </div>

            @php
                $selectedProduct = collect($products)->firstWhere('id', $product_id);
                $unitsPerBox = $selectedProduct?->units_per_box ?: 1;
                $priceBoxSanitized = (float) str_replace(',', '.', $price_box ?? 0);
                $priceUnitSanitized = (float) str_replace(',', '.', $price_unit ?? 0);
                $unitsFromBoxes = ($box_qty ?: 0) * $unitsPerBox;
                $unitQtyClean = $unit_qty;
                if ($box_qty > 0) {
                    $unitQtyClean = 0;
                }
                $totalUnits = max(0, $unitsFromBoxes + ($unitQtyClean ?: 0));
                $unitPrice =
                    $priceUnitSanitized > 0
                        ? $priceUnitSanitized
                        : ($priceBoxSanitized > 0 && $unitsPerBox > 0
                            ? $priceBoxSanitized / $unitsPerBox
                            : 0);
                $boxPrice = $priceBoxSanitized > 0 ? $priceBoxSanitized : $unitPrice * $unitsPerBox;
                $totalPrice = $unitPrice * $totalUnits;
            @endphp

            <div
                class="rounded-xl border border-blue-100 bg-gradient-to-r from-blue-50 to-white p-3 text-xs sm:text-sm text-gray-800 space-y-1.5 shadow-sm">
                <div class="grid grid-cols-2 gap-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('Коробок') }}</span>
                        <span class="font-semibold text-gray-900">{{ $box_qty }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('Штук всего') }}</span>
                        <span class="font-semibold text-gray-900">{{ $totalUnits }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('Цена/кор., $') }}</span>
                        <span class="font-semibold text-gray-900">{{ number_format($boxPrice, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('Цена/шт., $') }}</span>
                        <span class="font-semibold text-gray-900">{{ number_format($unitPrice, 2) }}</span>
                    </div>
                </div>
                <div class="h-px bg-blue-100"></div>
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('Итого, $') }}</span>
                    <span class="text-xl font-bold text-blue-700">{{ number_format($totalPrice, 2) }}</span>
                </div>
            </div>

            <div class="flex items-center justify-end">
                <x-ui::button type="submit" class="w-full">{{ __('Оформить продажу') }}</x-ui::button>
            </div>
        </form>
    </div>
</div>

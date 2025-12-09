<div class="space-y-6">
    <div class="flex items-start justify-between gap-3 flex-wrap">
        <div class="space-y-1">
            <x-ui::heading class="text-2xl">{{ __('Товары') }}</x-ui::heading>
            <x-ui::subheading>{{ __('Управляйте ассортиментом') }}</x-ui::subheading>
        </div>
        <div class="flex items-center gap-3 flex-wrap w-full sm:w-auto">
            <div class="relative flex-1 min-w-[200px] sm:min-w-[240px]">
                <input type="text" wire:model.live="search"
                    class="h-12 w-full rounded-xl bg-white border border-gray-200 px-3 text-sm font-semibold text-gray-800 placeholder:text-gray-400 focus:outline-2 focus:outline-blue-600"
                    placeholder="{{ __('Поиск по названию или штрих-коду') }}">
                <svg class="size-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 6.65 6.65a7.5 7.5 0 0 0 10.6 10.6Z" />
                </svg>
            </div>
            <select wire:model="category_id"
                class="h-12 w-full sm:w-52 rounded-xl bg-white border border-gray-200 px-3 text-sm font-semibold text-gray-800 focus:outline-2 focus:outline-blue-600">
                <option value="">{{ __('Все категории') }}</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            <x-ui::button wire:click="openCreate"
                class="w-full sm:w-auto px-5">{{ __('Добавить товар') }}</x-ui::button>
        </div>
    </div>

    @if (session('status'))
        <x-ui::alert type="success" :messages="[session('status')]" />
    @endif

    <x-ui::table class="min-w-full">
        <x-slot:head>
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide cursor-pointer whitespace-nowrap"
                    wire:click="sortBy('name')">
                    <div class="inline-flex items-center gap-1">
                        {{ __('Название') }}
                        <svg class="size-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m5 15 7-7 7 7" />
                        </svg>
                    </div>
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide w-20 cursor-pointer whitespace-nowrap"
                    wire:click="sortBy('quantity')">
                    {{ __('Кол-во') }}
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide w-24 cursor-pointer whitespace-nowrap"
                    wire:click="sortBy('box_count')">
                    {{ __('Коробок') }}
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide w-28 cursor-pointer whitespace-nowrap"
                    wire:click="sortBy('units_per_box')">
                    {{ __('Шт. в коробке') }}
                </th>
                <th
                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                    {{ __('Категория') }}</th>
                <th
                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                    {{ __('Штрих-код') }}</th>
                <th
                    class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide w-40 whitespace-nowrap">
                    {{ __('Действия') }}</th>
            </tr>
        </x-slot:head>

        @forelse ($products as $product)
            <tr class="hover:bg-gray-50/60 transition">
                <td class="px-4 py-3 text-sm font-semibold text-gray-800 flex items-center gap-3 whitespace-nowrap">
                    <span
                        class="h-10 w-10 rounded-xl bg-gray-100 overflow-hidden flex items-center justify-center shrink-0">
                        @if ($product->photo)
                            <img src="{{ $product->photo }}" alt="{{ $product->name }}"
                                class="h-full w-full object-cover">
                        @else
                            <svg class="size-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l5 5-5 5h18l-5-5 5-5z" />
                            </svg>
                        @endif
                    </span>
                    <span class="whitespace-nowrap">{{ $product->category->name . ' - ' . $product->name }}</span>
                </td>
                <td class="px-4 py-3 text-sm font-semibold text-gray-800 whitespace-nowrap">{{ $product->quantity }}
                </td>
                <td class="px-4 py-3 text-sm font-semibold text-gray-800 whitespace-nowrap">{{ $product->box_count }}
                </td>
                <td class="px-4 py-3 text-sm font-semibold text-gray-800 whitespace-nowrap">
                    {{ $product->units_per_box }}</td>
                <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                    {{ $product->category?->name ?? __('Без категории') }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $product->barcode ?: '—' }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('products.show', $product) }}"
                            class="inline-flex items-center justify-center h-10 w-10 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 transition"
                            title="{{ __('Просмотр') }}">
                            <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.5 12.5s3.5-6.5 9.5-6.5 9.5 6.5 9.5 6.5-3.5 6.5-9.5 6.5S2.5 12.5 2.5 12.5Z" />
                                <circle cx="12" cy="12.5" r="3" />
                            </svg>
                        </a>
                        <button type="button" wire:click="openEdit({{ $product->id }})"
                            class="inline-flex items-center justify-center h-10 w-10 rounded-xl bg-blue-600 text-white hover:bg-blue-500 transition">
                            <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.232 5.232a2.5 2.5 0 1 1 3.536 3.536L7.5 20.036 3 21l.964-4.5z" />
                            </svg>
                        </button>
                        <button type="button" wire:click="delete({{ $product->id }})"
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
                <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                    {{ __('Товаров пока нет') }}
                </td>
            </tr>
        @endforelse
    </x-ui::table>

    <x-ui::pagination :paginator="$products" />

    <x-ui::modal :show="$showModal" :title="$editingId ? __('Редактирование товара') : __('Новый товар')" wire:key="product-modal">
        <form class="space-y-4" wire:submit.prevent="save">
            <div class="space-y-1.5">
                <x-ui::label for="name">{{ __('Название') }}</x-ui::label>
                <x-ui::input id="name" type="text" wire:model.defer="name"
                    placeholder="{{ __('Введите название') }}" :error="$errors->first('name')" />
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <x-ui::label for="quantity">{{ __('Количество') }}</x-ui::label>
                    <x-ui::input id="quantity" type="number" min="0" wire:model.live="quantity"
                        placeholder="0" :error="$errors->first('quantity')" />
                </div>
                <div class="space-y-1.5">
                    <x-ui::label for="barcode">{{ __('Штрих-код') }}</x-ui::label>
                    <x-ui::input id="barcode" type="text" wire:model.defer="barcode" placeholder="EAN/UPC"
                        :error="$errors->first('barcode')" />
                </div>
            </div>
            <div class="space-y-1.5">
                <x-ui::label for="photo">{{ __('Фото товара') }}</x-ui::label>
                <input id="photo" type="file" wire:model="photoFile" accept="image/*"
                    class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-800 focus:outline-2 focus:outline-blue-600"
                    aria-describedby="photo-help">
                @if ($errors->first('photoFile'))
                    <p class="text-sm text-red-600">{{ $errors->first('photoFile') }}</p>
                @else
                    <p id="photo-help" class="text-sm text-gray-500">{{ __('PNG/JPG до 4 МБ') }}</p>
                @endif
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1.5">
                    <x-ui::label for="category_id">{{ __('Категория') }}</x-ui::label>
                    <select id="category_id" wire:model="category_id"
                        class="h-12 w-full rounded-xl bg-white border border-gray-200 px-3 text-base font-semibold text-gray-800 focus:outline-2 focus:outline-blue-600">
                        <option value="">{{ __('Без категории') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1.5">
                    <x-ui::label for="box_count">{{ __('Коробок в наличии') }}</x-ui::label>
                    <x-ui::input id="box_count" type="number" min="0"
                        wire:model.live.debounce.2000ms="box_count" placeholder="0" :error="$errors->first('box_count')" />
                </div>
                <div class="space-y-1.5">
                    <x-ui::label for="units_per_box">{{ __('Штук в коробке') }}</x-ui::label>
                    <x-ui::input id="units_per_box" type="number" min="0"
                        wire:model.live.debounce.2000ms="units_per_box" placeholder="0" :error="$errors->first('units_per_box')" />
                </div>
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

    <x-ui::confirm-modal :show="$showConfirm" :title="__('Удалить товар?')" :message="__('Это действие нельзя отменить.')" confirmAction="confirmDelete"
        cancelAction="$set('showConfirm', false)" />
</div>

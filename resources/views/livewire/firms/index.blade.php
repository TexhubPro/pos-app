<div class="space-y-6">
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div class="space-y-1">
            <x-ui::heading class="text-2xl">{{ __('Фирмы') }}</x-ui::heading>
            <x-ui::subheading>{{ __('Компании-поставщики') }}</x-ui::subheading>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            <div class="relative">
                <input type="text" wire:model.live="search"
                    class="h-12 w-52 rounded-xl bg-white border border-gray-200 px-3 text-sm font-semibold text-gray-800 placeholder:text-gray-400 focus:outline-2 focus:outline-blue-600"
                    placeholder="{{ __('Поиск по названию') }}">
                <svg class="size-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 6.65 6.65a7.5 7.5 0 0 0 10.6 10.6Z" />
                </svg>
            </div>
            <x-ui::button wire:click="openCreate" class="w-auto px-5">{{ __('Добавить фирму') }}</x-ui::button>
        </div>
    </div>

    @if (session('status'))
        <x-ui::alert type="success" :messages="[session('status')]" />
    @endif

    <x-ui::table class="min-w-full">
        <x-slot:head>
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                    {{ __('Название') }}</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                    {{ __('Телефон') }}</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                    {{ __('Наш долг, $') }}</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide w-32 whitespace-nowrap">
                    {{ __('Действия') }}</th>
            </tr>
        </x-slot:head>

        @forelse ($firms as $firm)
            <tr class="hover:bg-gray-50/60 transition">
                <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ $firm->name }}</td>
                <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $firm->phone ?: '—' }}</td>
                <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ number_format($firm->debt, 2) }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('firms.show', $firm) }}"
                            class="inline-flex items-center justify-center h-10 w-10 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 transition"
                            title="{{ __('Просмотр') }}">
                            <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.5 12.5s3.5-6.5 9.5-6.5 9.5 6.5 9.5 6.5-3.5 6.5-9.5 6.5S2.5 12.5 2.5 12.5Z" />
                                <circle cx="12" cy="12.5" r="3" />
                            </svg>
                        </a>
                        <button type="button" wire:click="openEdit({{ $firm->id }})"
                            class="inline-flex items-center justify-center h-10 w-10 rounded-xl bg-blue-600 text-white hover:bg-blue-500 transition">
                            <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.232 5.232a2.5 2.5 0 1 1 3.536 3.536L7.5 20.036 3 21l.964-4.5z" />
                            </svg>
                        </button>
                        <button type="button" wire:click="delete({{ $firm->id }})"
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
                <td colspan="4" class="px-4 py-6 text-center text-gray-500">{{ __('Фирм пока нет') }}</td>
            </tr>
        @endforelse
    </x-ui::table>

    <x-ui::pagination :paginator="$firms" />

    <x-ui::modal :show="$showModal" :title="$editingId ? __('Редактирование фирмы') : __('Новая фирма')" wire:key="firm-modal">
        <form class="space-y-4" wire:submit.prevent="save">
            <div class="space-y-1.5">
                <x-ui::label for="name">{{ __('Название компании') }}</x-ui::label>
                <x-ui::input id="name" type="text" wire:model.defer="name" :error="$errors->first('name')"
                    placeholder="{{ __('Введите название') }}" />
            </div>
            <div class="space-y-1.5">
                <x-ui::label for="phone">{{ __('Телефон компании') }}</x-ui::label>
                <x-ui::input id="phone" type="text" wire:model.defer="phone" :error="$errors->first('phone')"
                    placeholder="{{ __('Например, +992...') }}" />
            </div>
            <div class="space-y-1.5">
                <x-ui::label for="debt">{{ __('Наш долг, $') }}</x-ui::label>
                <x-ui::input id="debt" type="number" min="0" step="0.01" wire:model.defer="debt"
                    :error="$errors->first('debt')" />
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

    <x-ui::confirm-modal :show="$showConfirm" :title="__('Удалить фирму?')" :message="__('Это действие нельзя отменить.')"
        confirmAction="confirmDelete" cancelAction="$set('showConfirm', false)" />
</div>

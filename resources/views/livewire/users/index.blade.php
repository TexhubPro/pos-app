<div class="space-y-6">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="space-y-1">
            <x-ui::heading class="text-2xl">{{ __('Сотрудники') }}</x-ui::heading>
            <x-ui::subheading>{{ __('Управление пользователями системы') }}</x-ui::subheading>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            <div class="relative">
                <input type="text" wire:model.live="search"
                    class="h-12 w-60 rounded-xl bg-white border border-gray-200 px-3 text-sm font-semibold text-gray-800 placeholder:text-gray-400 focus:outline-2 focus:outline-blue-600"
                    placeholder="{{ __('Поиск по имени или телефону') }}">
                <svg class="size-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 6.65 6.65a7.5 7.5 0 0 0 10.6 10.6Z" />
                </svg>
            </div>
            <x-ui::button wire:click="openCreate" class="px-5">{{ __('Добавить сотрудника') }}</x-ui::button>
        </div>
    </div>

    @if (session('status'))
        <x-ui::alert type="success" :messages="[session('status')]" />
    @endif

    <x-ui::table class="min-w-full">
        <x-slot:head>
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap cursor-pointer"
                    wire:click="sortBy('name')">{{ __('Имя') }}</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                    {{ __('Телефон') }}</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">
                    {{ __('Создан') }}</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide w-32 whitespace-nowrap">
                    {{ __('Действия') }}</th>
            </tr>
        </x-slot:head>

        @forelse ($users as $user)
            <tr class="hover:bg-gray-50/60 transition">
                <td class="px-4 py-3 text-sm font-semibold text-gray-800 whitespace-nowrap">{{ $user->name }}</td>
                <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $user->phone }}</td>
                <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $user->created_at->format('d.m.Y') }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-2">
                        <button type="button" wire:click="openEdit({{ $user->id }})"
                            class="inline-flex items-center justify-center h-10 w-10 rounded-xl bg-blue-600 text-white hover:bg-blue-500 transition">
                            <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.232 5.232a2.5 2.5 0 1 1 3.536 3.536L7.5 20.036 3 21l.964-4.5z" />
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="px-4 py-6 text-center text-gray-500">{{ __('Сотрудников пока нет') }}</td>
            </tr>
        @endforelse
    </x-ui::table>

    <x-ui::pagination :paginator="$users" />

    <x-ui::modal :show="$showModal" :title="$editingId ? __('Редактирование сотрудника') : __('Новый сотрудник')" wire:key="user-modal">
        <form class="space-y-4" wire:submit.prevent="save">
            <div class="space-y-1.5">
                <x-ui::label for="name">{{ __('Имя') }}</x-ui::label>
                <x-ui::input id="name" type="text" wire:model.defer="name" :error="$errors->first('name')" required />
            </div>
            <div class="space-y-1.5">
                <x-ui::label for="phone">{{ __('Телефон') }}</x-ui::label>
                <x-ui::input id="phone" type="text" wire:model.defer="phone" :error="$errors->first('phone')" required />
            </div>
            <div class="space-y-1.5">
                <x-ui::label for="password">{{ $editingId ? __('Пароль (если нужно поменять)') : __('Пароль') }}</x-ui::label>
                <x-ui::input id="password" type="password" wire:model.defer="password" :error="$errors->first('password')" />
            </div>
            <div class="flex items-center justify-end gap-3 pt-2">
                <x-ui::button type="button" class="bg-red-500 hover:bg-red-400" wire:click="$set('showModal', false)">
                    {{ __('Отмена') }}
                </x-ui::button>
                <x-ui::button type="submit" class="px-5">{{ __('Сохранить') }}</x-ui::button>
            </div>
        </form>
    </x-ui::modal>
</div>

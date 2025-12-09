<x-layouts.dashboard>
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <x-ui::heading class="text-2xl">{{ __('Настройки профиля') }}</x-ui::heading>
                <x-ui::subheading>{{ __('Обновите телефон, пароль и аватар') }}</x-ui::subheading>
            </div>
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center justify-center h-11 rounded-xl bg-gray-100 text-gray-800 px-4 font-semibold hover:bg-gray-200 transition">
                {{ __('Назад') }}
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-5">
            <form class="space-y-4" method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <x-ui::label for="name">{{ __('Имя') }}</x-ui::label>
                        <x-ui::input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                            required />
                    </div>
                    <div class="space-y-1.5">
                        <x-ui::label for="phone">{{ __('Номер телефона') }}</x-ui::label>
                        <x-ui::input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}"
                            required />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <x-ui::label for="password">{{ __('Новый пароль') }}</x-ui::label>
                        <x-ui::input id="password" name="password" type="password" autocomplete="new-password" />
                    </div>
                    <div class="space-y-1.5">
                        <x-ui::label for="password_confirmation">{{ __('Подтверждение пароля') }}</x-ui::label>
                        <x-ui::input id="password_confirmation" name="password_confirmation" type="password"
                            autocomplete="new-password" />
                    </div>
                </div>

                <div class="space-y-1.5">
                    <x-ui::label>{{ __('Аватар') }}</x-ui::label>
                    <div class="flex items-center gap-3">
                        @php
                            $avatarPath = $user->avatar ? asset('storage/' . $user->avatar) : 'https://api.dicebear.com/9.x/initials/svg?seed=' . urlencode($user->name ?? 'User');
                        @endphp
                        <img src="{{ $avatarPath }}" alt="Avatar"
                            class="h-14 w-14 rounded-full object-cover ring-2 ring-blue-200">
                        <input type="file" name="avatar" accept="image/*"
                            class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-800 focus:outline-2 focus:outline-blue-600">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <x-ui::button type="submit" class="px-5">{{ __('Сохранить изменения') }}</x-ui::button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>

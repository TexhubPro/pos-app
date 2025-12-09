<div class="min-h-screen flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-lg">
        <div class="bg-white/80 backdrop-blur shadow-xl ring-1 ring-blue-50 rounded-3xl p-8 space-y-8">
            <div class="space-y-3 text-center">
                <div class="inline-flex items-center justify-center h-14 w-14 rounded-2xl bg-blue-100 text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-7" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 12v.01" />
                        <path d="M3 21h18" />
                        <path d="M12 3c3.866 0 7 2.239 7 5v3c0 2.761-3.134 5-7 5s-7-2.239-7-5V8c0-2.761 3.134-5 7-5Z" />
                    </svg>
                </div>
                <x-ui::heading level="h1" class="text-3xl">{{ __('Вход в аккаунт') }}</x-ui::heading>
                <x-ui::subheading class="text-base text-gray-600">
                    {{ __('Введите номер телефона и пароль, чтобы продолжить работу.') }}
                </x-ui::subheading>
            </div>

            <form wire:submit="login" class="space-y-5">
                <div class="space-y-1.5">
                    <x-ui::label for="phone">{{ __('Номер телефона') }}</x-ui::label>
                    <x-ui::input id="phone" type="text" wire:model="phone" placeholder="931234567"
                        :error="$errors->first('phone')" />
                </div>

                <div class="space-y-1.5">
                    <x-ui::label for="password">{{ __('Пароль') }}</x-ui::label>
                    <x-ui::input id="password" type="password" wire:model="password"
                        placeholder="{{ __('Минимум 8 символов') }}" :error="$errors->first('password')" />
                </div>

                <div class="flex items-center justify-between text-sm">
                    <x-ui::toggle wire:model="remember" label="{{ __('Запомнить меня') }}" />
                    <a href="#" class="font-semibold text-blue-600 hover:text-blue-700">
                        {{ __('Забыли пароль?') }}
                    </a>
                </div>

                <x-ui::button type="submit" loading="{{ __('Входим...') }}">
                    {{ __('Войти') }}
                </x-ui::button>
            </form>

            @if ($errors->has('global'))
                <x-ui::alert type="error" title="{{ __('Проверьте данные') }}" :messages="$errors->get('global')" />
            @endif
        </div>
    </div>
</div>

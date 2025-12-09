<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Login extends Component
{
    public string $phone = '';
    public string $password = '';
    public bool $remember = false;

    public function login(): void
    {
        $this->validate([
            'phone' => ['required', 'regex:/^[0-9]{9}$/'],
            'password' => [
                'required',
                'min:8',
                'regex:/^[A-Za-z0-9!@#$%^&*()_\\-+=\\[\\]{};:\'",.<>\\/\\?\\|`~\\\\]+$/',
            ],
        ], [
            'phone.required' => 'Введите номер телефона',
            'phone.regex' => 'Номер телефона должен содержать ровно 9 цифр',
            'password.required' => 'Введите пароль',
            'password.min' => 'Пароль должен быть не менее 8 символов',
            'password.regex' => 'Используйте только латинские буквы, цифры и символы',
        ]);

        if (Auth::attempt(['phone' => $this->phone, 'password' => $this->password], $this->remember)) {
            request()->session()->regenerate();
            $this->redirectIntended('/dashboard', navigate: true);
            return;
        }

        $this->addError('global', __('Неверный номер телефона или пароль'));
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.auth.login');
    }
}

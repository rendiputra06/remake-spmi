<?php

namespace App\Filament\Pages\Auth;

use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Login extends BaseLogin
{
    /**
     * Get the title of the page.
     */
    public function getTitle(): string|Htmlable
    {
        return __('SPMI - Login');
    }

    /**
     * Get the URL to redirect to after successful login.
     */
    protected function getRedirectUrl(): string
    {
        return Filament::getUrl();
    }

    /**
     * Quick login for local development.
     *
     * @param string $email
     * @param string $password
     * @return void
     */
    public function quickLogin(string $email, string $password): void
    {
        if (!app()->environment('local')) {
            return;
        }

        $data = [
            'email' => $email,
            'password' => $password,
        ];

        $validator = Validator::make($data, [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            Notification::make()
                ->title('Kredensial tidak valid.')
                ->danger()
                ->send();
            return;
        }

        if (!Auth::attempt($data)) {
            Notification::make()
                ->title('Kredensial tidak valid.')
                ->danger()
                ->send();
            return;
        }

        session()->regenerate();

        Notification::make()
            ->title('Login berhasil!')
            ->success()
            ->send();

        $this->redirect($this->getRedirectUrl());
    }
}

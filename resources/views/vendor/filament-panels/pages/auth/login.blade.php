<x-filament-panels::page.simple>
    @if (filament()->hasRegistration())
    <x-slot name="subheading">
        {{ __('filament-panels::pages/auth/login.actions.register.before') }}

        {{ $this->registerAction }}
    </x-slot>
    @endif

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    <x-filament-panels::form id="form" wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()" />
    </x-filament-panels::form>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}

    @if (app()->environment('local'))
    <div class="mt-8">
        <h3 class="text-center font-medium text-gray-600 dark:text-gray-400">Quick Login (Local Only)</h3>
        <div class="flex flex-wrap justify-center gap-3 mt-4">
            @php
            $quickLogins = [
            ['name' => 'Super Admin', 'email' => 'superadmin@example.com'],
            ['name' => 'Admin', 'email' => 'admin@example.com'],
            ['name' => 'Kepala LPM', 'email' => 'lpm@example.com'],
            ['name' => 'Auditor', 'email' => 'auditor@example.com'],
            ['name' => 'Rektor', 'email' => 'rektor@example.com'],
            ['name' => 'Dekan', 'email' => 'dekan@example.com'],
            ['name' => 'Kaprodi', 'email' => 'kaprodi@example.com'],
            ];
            @endphp

            @foreach ($quickLogins as $user)
            <button
                type="button"
                wire:click="quickLogin('{{ $user['email'] }}', 'password')"
                class="flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                {{ $user['name'] }}
            </button>
            @endforeach
        </div>
    </div>
    @endif
</x-filament-panels::page.simple>
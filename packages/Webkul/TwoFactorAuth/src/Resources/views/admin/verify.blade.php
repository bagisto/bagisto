@php
    $twoFactorEnabled = core()->getConfigData('general.two_factor_auth.settings.enabled');
@endphp

<x-admin::layouts.anonymous>
    <x-slot:title>
        Two Factor Authentication
    </x-slot>

    <div class="flex h-[100vh] items-center justify-center">
        <div class="flex flex-col items-center gap-5">

            @if ($logo = core()->getConfigData('general.design.admin_logo.logo_image'))
                <img class="h-10 w-[110px]" src="{{ Storage::url($logo) }}" alt="{{ config('app.name') }}" />
            @else
                <img class="w-max" src="{{ bagisto_asset('images/logo.svg') }}" alt="{{ config('app.name') }}" />
            @endif

            @if ($twoFactorEnabled)
                <div class="box-shadow flex min-w-[320px] flex-col rounded-md bg-white dark:bg-gray-900">
                    <p class="p-4 text-xl font-bold text-gray-800 dark:text-white text-center">
                        {{ __('Verify Two-Factor Authentication') }}
                    </p>

                    <div class="border-t p-4 dark:border-gray-800 flex flex-col gap-4">
                        <p class="text-sm text-gray-600 dark:text-gray-300 text-center">
                            {{ __('Enter the 6-digit code from your authenticator app to continue.') }}
                        </p>

                        <x-admin::form :action="route('admin.twofactor.verifyTwoFactorCode')" method="POST" class="w-full">
                            @csrf
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    {{ __('Verification Code') }}
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    class="w-full"
                                    id="code"
                                    name="code"
                                    rules="required|numeric|digits:6"
                                    :label="__('Verification Code')"
                                    :placeholder="__('Enter 6-digit code')"
                                />

                                <x-admin::form.control-group.error control-name="code" />
                            </x-admin::form.control-group>

                            <div class="mt-4 flex items-center justify-end gap-4">
                                <a
                                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                                    href="{{ route('admin.twofactor.session.destroy') }}"
                                >
                                    {{ __('Back') }}
                                </a>
                            
                                <button
                                    class="cursor-pointer rounded-md border border-blue-700 bg-blue-600 px-3.5 py-1.5 font-semibold text-gray-50"
                                    aria-label="{{ __('Verify Code') }}"
                                >
                                    {{ __('Verify Code') }}
                                </button>
                            </div>

                        </x-admin::form>
                    </div>
                </div>

                <div class="text-sm font-normal">
                    @lang('admin::app.users.sessions.powered-by-description', [
                        'bagisto' => '<a class="text-blue-600 hover:underline" href="https://bagisto.com/en/">Bagisto</a>',
                        'webkul' => '<a class="text-blue-600 hover:underline" href="https://webkul.com/">Webkul</a>',
                    ])
                </div>

            @else
                <div class="p-4 text-gray-600 dark:text-gray-300 text-center">
                    {{ __('Verify Two-Factor Authentication is currently disabled by the admin.') }}
                </div>
            @endif
        </div>
    </div>
</x-admin::layouts.anonymous>

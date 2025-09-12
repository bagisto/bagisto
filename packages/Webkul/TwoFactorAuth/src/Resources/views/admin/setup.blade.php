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

            <div class="box-shadow flex min-w-[320px] flex-col rounded-md bg-white dark:bg-gray-900">

                <p class="p-4 text-xl font-bold text-gray-800 dark:text-white">
                    {{ __('Enable Two-Factor Authentication') }}
                </p>

                <div class="border-y p-4 dark:border-gray-800 flex flex-col items-center gap-4">
                    <p class="text-sm text-gray-600 dark:text-gray-300 text-center">
                        {{ __('Scan this QR code in your Google Authenticator app, then enter the 6-digit code below.') }}
                    </p>

                    <div class="flex justify-center">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($qrCodeUrl) !!}
                    </div>

                    <x-admin::form :action="route('admin.twofactor.enable')" method="POST" class="w-full">
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

                        <div class="mt-4 flex justify-end gap-4">
                            <a
                                class="transparent-button hover:bg-gray-200 px-3.5 py-1.5 font-semibold dark:text-white dark:hover:bg-gray-800"
                                href="{{ route('admin.twofactor.configuration.back') }}"
                            >
                                {{ __('Back') }}
                            </a>

                            <button
                                class="cursor-pointer rounded-md border border-blue-700 bg-blue-600 px-3.5 py-1.5 font-semibold text-gray-50"
                                aria-label="{{ __('Verify & Enable') }}"
                            >
                                {{ __('Verify & Enable') }}
                            </button>
                        </div>
                    </x-admin::form>
                </div>
            </div>
        </div>
    </div>
</x-admin::layouts.anonymous>

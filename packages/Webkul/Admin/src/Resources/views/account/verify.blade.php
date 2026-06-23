<x-admin::layouts.anonymous>
    <x-slot:title>
        @lang('admin::app.users.verify.title')
    </x-slot>

    <div class="flex h-[100vh] items-center justify-center">
        <div class="flex flex-col items-center gap-5">
            @if ($logo = core()->getConfigData('general.design.admin_logo.logo_image'))
                <img 
                    class="h-10 w-[110px]" 
                    src="{{ Storage::url($logo) }}" 
                    alt="{{ config('app.name') }}" 
                />
            @else
                <img 
                    class="w-max" 
                    src="{{ bagisto_asset('images/logo.svg') }}" 
                    alt="{{ config('app.name') }}" 
                />
            @endif

            <div class="box-shadow flex min-w-[320px] flex-col rounded-md bg-white dark:bg-gray-900">
                <p class="p-4 text-xl font-bold text-gray-800 dark:text-white text-center">
                    @lang('admin::app.users.verify.title')
                </p>

                <div class="border-t p-4 dark:border-gray-800 flex flex-col gap-4">
                    <p class="text-sm text-gray-600 dark:text-gray-300 text-center">
                        @lang('admin::app.users.verify.enter-code')
                    </p>

                    <x-admin::form 
                        :action="route('admin.two_factor.verifyTwoFactorCode')" 
                        method="POST" 
                        class="w-full"
                    >
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.users.verify.code-label')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                class="w-full"
                                id="code"
                                name="code"
                                rules="required|numeric|digits:6"
                                :label="trans('admin::app.users.verify.code-label')"
                                :placeholder="trans('admin::app.users.verify.code-placeholder')"
                            />

                            <x-admin::form.control-group.error control-name="code" />
                        </x-admin::form.control-group>

                        <div class="mt-4 flex items-center justify-end gap-4">
                            <!-- Admin Logout -->
                            <x-admin::form
                                method="DELETE"
                                action="{{ route('admin.session.destroy') }}"
                                id="adminLogout"
                            >
                            </x-admin::form>
                        
                            <button
                                type="button"
                                class="cursor-pointer px-4 py-2 text-sm text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-950 sm:px-5 sm:text-base"
                                onclick="event.preventDefault(); document.getElementById('adminLogout').submit();"
                            >
                                @lang('admin::app.users.verify.back')
                            </button>

                            <button
                                class="cursor-pointer rounded-md border border-blue-700 bg-blue-600 px-3.5 py-1.5 font-semibold text-gray-50"
                                aria-label="@lang('admin::app.users.verify.verify-code')"
                            >
                                @lang('admin::app.users.verify.verify-code')
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
        </div>
    </div>
</x-admin::layouts.anonymous>

<x-admin::layouts.anonymous>
    <x-slot:title>
        @lang('admin::app.users.verify.title')
    </x-slot>

    <div class="flex h-[100vh] items-center justify-center px-4">
        <div class="flex w-full max-w-[420px] flex-col items-center gap-6">
            <!-- Logo -->
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

            <!-- Card -->
            <div class="box-shadow w-full overflow-hidden rounded-xl bg-white dark:bg-gray-900">
                <!-- Header -->
                <div class="flex flex-col items-center gap-2 px-8 pt-8 text-center">
                    <!-- Lock Badge -->
                    <div class="mb-2 flex h-14 w-14 items-center justify-center rounded-full bg-blue-50 dark:bg-blue-950">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.8"
                            stroke="currentColor"
                            class="h-7 w-7 text-blue-600 dark:text-blue-400"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"
                            />
                        </svg>
                    </div>

                    <p class="text-xl font-bold text-gray-800 dark:text-white">
                        @lang('admin::app.users.verify.title')
                    </p>

                    <p class="text-sm leading-relaxed text-gray-500 dark:text-gray-300">
                        @lang('admin::app.users.verify.enter-code')
                    </p>
                </div>

                <!-- Verification Form -->
                <x-admin::form
                    :action="route('admin.two_factor.verify.store')"
                    method="POST"
                    class="w-full"
                >
                    <div class="px-8 py-6">
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required !text-xs !font-semibold !uppercase !tracking-wide !text-gray-500 dark:!text-gray-300">
                                @lang('admin::app.users.verify.code-label')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                class="text-center font-semibold !py-3 !text-xl !tracking-[0.35em] placeholder:!tracking-normal placeholder:text-base placeholder:font-normal"
                                id="code"
                                name="code"
                                rules="required|numeric|digits:6"
                                inputmode="numeric"
                                maxlength="6"
                                autocomplete="one-time-code"
                                autofocus
                                :label="trans('admin::app.users.verify.code-label')"
                                :placeholder="trans('admin::app.users.verify.code-placeholder')"
                            />

                            <x-admin::form.control-group.error control-name="code" />
                        </x-admin::form.control-group>

                        <!-- Verify Button -->
                        <button
                            type="submit"
                            class="primary-button mt-2 w-full !justify-center !py-2.5 text-base"
                            aria-label="@lang('admin::app.users.verify.verify-code')"
                        >
                            @lang('admin::app.users.verify.verify-code')
                        </button>
                    </div>
                </x-admin::form>

                <!-- Back / Logout -->
                <div class="border-t px-8 py-4 text-center dark:border-gray-800">
                    <x-admin::form
                        method="DELETE"
                        action="{{ route('admin.session.destroy') }}"
                        id="adminLogout"
                    >
                    </x-admin::form>

                    <button
                        type="button"
                        class="text-sm font-medium text-gray-500 transition-all hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400"
                        onclick="event.preventDefault(); document.getElementById('adminLogout').submit();"
                    >
                        @lang('admin::app.users.verify.back')
                    </button>
                </div>
            </div>

            <!-- Powered By -->
            <div class="text-sm font-normal">
                @lang('admin::app.users.sessions.powered-by-description', [
                    'bagisto' => '<a class="text-blue-600 hover:underline" href="https://bagisto.com/en/">Bagisto</a>',
                    'webkul' => '<a class="text-blue-600 hover:underline" href="https://webkul.com/">Webkul</a>',
                ])
            </div>
        </div>
    </div>
</x-admin::layouts.anonymous>

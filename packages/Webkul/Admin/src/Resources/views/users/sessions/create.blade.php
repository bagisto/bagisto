<x-admin::layouts.anonymous>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.users.sessions.title')
    </x-slot>

    <div class="flex justify-center items-center h-[100vh]">
        <div class="flex flex-col gap-5 items-center">
            <!-- Logo -->            
            @if ($logo = core()->getConfigData('general.design.admin_logo.logo_image'))
                <img
                    class="w-[110px] h-10"
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

            <div class="flex flex-col min-w-[300px] bg-white dark:bg-gray-900 rounded-md box-shadow">
                <!-- Login Form -->
                <x-admin::form :action="route('admin.session.store')">
                    <p class="p-4 text-xl text-gray-800 dark:text-white font-bold">
                        @lang('admin::app.users.sessions.title')
                    </p>

                    <div class="p-4 border-y dark:border-gray-800">
                        <!-- Email -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.users.sessions.email')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control 
                                type="email" 
                                class="w-[254px] max-w-full" 
                                id="email"
                                name="email" 
                                rules="required|email" 
                                :label="trans('admin::app.users.sessions.email')"
                                :placeholder="trans('admin::app.users.sessions.email')"
                            />

                            <x-admin::form.control-group.error control-name="email" />
                        </x-admin::form.control-group>

                        <!-- Password -->
                        <x-admin::form.control-group class="relative w-full">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.users.sessions.password')
                            </x-admin::form.control-group.label>
                    
                            <x-admin::form.control-group.control 
                                type="password" 
                                class="w-[254px] max-w-full ltr:pr-10 rtl:pl-10" 
                                id="password"
                                name="password" 
                                rules="required|min:6" 
                                :label="trans('admin::app.users.sessions.password')"
                                :placeholder="trans('admin::app.users.sessions.password')"
                            />
                    
                            <span 
                                class="icon-view text-2xl cursor-pointer absolute top-[42px] -translate-y-2/4 ltr:right-2 rtl:left-2"
                                onclick="switchVisibility()"
                                id="visibilityIcon"
                                role="presentation"
                                tabindex="0"
                            >
                            </span>
                    
                            <x-admin::form.control-group.error control-name="password" />
                        </x-admin::form.control-group>
                    </div>

                    <div class="flex justify-between items-center p-4">
                        <!-- Forgot Password Link -->
                        <a 
                            class="text-xs text-blue-600 font-semibold leading-6 cursor-pointer"
                            href="{{ route('admin.forget_password.create') }}"
                        >
                            @lang('admin::app.users.sessions.forget-password-link')
                        </a>

                        <!-- Submit Button -->
                        <button
                            class="px-3.5 py-1.5 bg-blue-600 border border-blue-700 rounded-md text-gray-50 font-semibold cursor-pointer"
                            aria-label="{{ trans('admin::app.users.sessions.submit-btn')}}"
                        >
                            @lang('admin::app.users.sessions.submit-btn')
                        </button>
                    </div>
                </x-admin::form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function switchVisibility() {
                let passwordField = document.getElementById("password");
                let visibilityIcon = document.getElementById("visibilityIcon");

                passwordField.type = passwordField.type === "password" ? "text" : "password";
                visibilityIcon.classList.toggle("icon-view-close");
            }
        </script>
    @endpush
</x-admin::layouts.anonymous>
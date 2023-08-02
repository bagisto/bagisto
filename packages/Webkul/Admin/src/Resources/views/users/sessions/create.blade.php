<x-admin::layouts.anonymous>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('admin::app.users.sessions.title')
    </x-slot:title>

    <div class="flex justify-center items-center h-[100vh]">
        <div class="flex flex-col gap-[20px] items-center">
            {{-- Logo --}}
            <img 
                class="w-max" 
                src="{{ bagisto_asset('images/logo.png') }}" 
                alt="Bagisto Logo"
            >

            <div class="flex flex-col bg-white border border-gray-300 rounded-[6px] box-shadow">
                {{-- Login Form --}}
                <x-shop::form :action="route('admin.session.store')">
                    <div class="p-[16px]  ">
                        <p class="text-[20px] text-gray-800 font-bold ">
                            @lang('admin::app.users.sessions.title')
                        </p>
                    </div>

                    <div class="p-[16px] border-t-[1px] border-b-[1px] border-gray-300">
                        <div class="mb-[10px]">
                            {{-- Email --}}
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.users.sessions.email')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="email"
                                    name="email" 
                                    id="email"
                                    class="w-[252px] max-w-full" 
                                    rules="required|email" 
                                    :label="trans('admin::app.users.sessions.email')"
                                    :placeholder="trans('admin::app.users.sessions.email')"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="email"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                        </div>
                        <div class="mb-[10px]">
                            {{-- Password --}}
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.users.sessions.password')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="password"
                                    name="password" 
                                    id="password"
                                    class="w-[252px] max-w-full" 
                                    rules="required|min:6" 
                                    :label="trans('admin::app.users.sessions.password')"
                                    :placeholder="trans('admin::app.users.sessions.password')"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="password"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>
                        </div>
                    </div>
                    <div class="flex justify-between items-center p-[16px]">
                        {{-- Forgot Password Link --}}
                        <a 
                            class="text-[12px] text-blue-600 font-semibold leading-[24px] cursor-pointer"
                            href="{{ route('admin.forget_password.create') }}"
                        >
                            @lang('admin::app.users.sessions.forget-password-link')
                        </a>
                        <button 
                            class="px-[14px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer">
                            @lang('admin::app.users.sessions.submit-btn')
                        </button>
                    </div>
                </x-shop::form>
            </div>
        </div>
    </div>
</x-admin::layouts.anonymous>
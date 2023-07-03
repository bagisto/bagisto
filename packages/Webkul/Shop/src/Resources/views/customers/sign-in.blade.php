<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    <div class="container mt-20 max-1180:px-[20px]">
        {{-- Company Logo --}}
        <div class="flex items-center gap-x-[54px] max-[1180px]:gap-x-[35px]">
            <a
                href="{{ route('shop.home.index') }}" 
                class="bs-logo bg-[position:-5px_-3px] bs-main-sprite w-[131px] h-[29px] inline-block m-[0_auto_20px_auto]"
            >
            </a>
        </div>
        
        {{-- Form Container --}}
        <div
            class="w-full max-w-[870px] m-auto border border-[#E9E9E9] px-[90px] py-[60px] rounded-[12px] max-md:px-[30px] max-md:py-[30px]"
        >
            <h1 class="text-[40px] font-dmserif max-sm:text-[25px]">
                @lang('shop::app.customers.login-form.page-title')
            </h1>

            <p class="text-[#7D7D7D] text-[20px] mt-[15px] max-sm:text-[16px]">
                @lang('shop::app.customers.login-form.form-login-text')
            </p>

            {!! view_render_event('bagisto.shop.customers.login.before') !!}

            <div class="rounded mt-[60px] max-sm:mt-[30px]">
                <x-shop::form :action="route('shop.customer.session.create')">
                    <x-shop::form.control-group class="mb-4">
                        <x-shop::form.control-group.label>
                            @lang('shop::app.customers.login-form.email')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="email"
                            name="email"
                            value=""
                            rules="required|email"
                            label="Email"
                            placeholder="email@example.com"
                        >
                        </x-shop::form.control-group.control>

                        <x-shop::form.control-group.error
                            control-name="email"
                        >
                        </x-shop::form.control-group.error>
                    </x-shop::form.control-group>

                    <x-shop::form.control-group class="mb-6">
                        <x-shop::form.control-group.label>
                            @lang('shop::app.customers.login-form.password')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="password"
                            name="password"
                            value=""
                            id="password"
                            rules="required|min:6"
                            label="Password"
                            placeholder="Password"
                        >
                        </x-shop::form.control-group.control>

                        <x-shop::form.control-group.error
                            control-name="password"
                        >
                        </x-shop::form.control-group.error>
                    </x-shop::form.control-group>

                    <div class="flex justify-between">
                        <div class="text-[##7D7D7D] flex items-center gap-[6px]">
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.control
                                    type="checkbox"
                                    name="show_password"
                                    onclick="switchVisibility()"
                                >
                                    <span class="select-none  text-[16] text-[#7d7d7d] max-sm:text-[12px]">
                                        @lang('shop::app.customers.login-form.show-password')
                                    </span>
                                </x-shop::form.control-group.control>
                            </x-shop::form.control-group>
                        </div>

                        <div class="block">
                            <a
                                href="{{ route('shop.customers.forgot_password.create') }}"
                                class="text-[16px] cursor-pointer text-black max-sm:text-[12px]"
                            >
                                <span>
                                    @lang('shop::app.customers.login-form.forgot_pass')
                                </span>
                            </a>
                        </div>
                    </div>

                    {!! view_render_event('bagisto.shop.customers.login_form_controls.after') !!}

                    <div class="mt-[20px]">
                        {!! Captcha::render() !!}
                    </div>

                    <div class="flex gap-[36px] flex-wrap mt-[30px] items-center">
                        <button
                            class="m-0 ml-[0px] block mx-auto w-full bg-navyBlue text-white text-[16px] max-w-[374px] font-medium py-[16px] px-[43px] rounded-[18px] text-center"
                            type="submit"
                        >
                            @lang('shop::app.customers.login-form.button_title')
                        </button>

                        {!! view_render_event('bagisto.shop.customers.login.after') !!}

                        {!! view_render_event('bagisto.shop.customers.login_form_controls.before') !!}
                    </div>
                </x-shop::form>
            </div>

            <p class="text-[#7D7D7D] font-medium mt-[20px]">
                @lang('shop::app.customers.login-form.new-customer')

                <a
                    class="text-navyBlue"
                    href="{{ route('shop.customers.register.index') }}"
                >
                    @lang('shop::app.customers.login-form.create-your-account')
                </a>
            </p>
        </div>

        <p class="mt-[30px] mb-[15px] text-center text-[#7d7d7d] text-xs">
            @lang('shop::app.customers.login-form.footer')
        </p>
    </div>

    @push('scripts')
        {!! Captcha::renderJS() !!}

        <script>
            function switchVisibility() {
                let passwordField = document.getElementById("password");

                passwordField.type = passwordField.type === "password"
                    ? "text"
                    : "password";
            }
        </script>
    @endpush
</x-shop::layouts>

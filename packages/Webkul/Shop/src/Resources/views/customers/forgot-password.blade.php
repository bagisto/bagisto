<x-shop::layouts>
    <div class="container mt-20 max-1180:px-[20px]">
        <div
            class="w-full max-w-[870px] m-auto border border-[#E9E9E9] px-[90px] py-[60px] rounded-[12px] max-md:px-[30px] max-md:py-[30px]"
        >
            <h1 class="text-[40px] font-dmserif max-sm:text-[25px]">
                @lang('shop::app.customer.forgot-password.title')
            </h1>

            <p class="text-[#7D7D7D] text-[20px] mt-[15px] max-sm:text-[16px]">
                @lang('shop::app.customer.forgot-password.forgot-password-text')
            </p>

            {!! view_render_event('bagisto.shop.customers.forget_password.before') !!}

            <x-shop::form
                :action="route('shop.customer.forgot_password.store')"
                class="rounded mt-[60px] max-sm:mt-[30px]"
            >

                {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.before') !!}

                <x-shop::form.control-group class="mb-4">
                    <x-shop::form.control-group.label>
                        @lang('shop::app.customer.login-form.email')
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

                {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.before') !!}

                <x-shop::form.control-group>

                    {!! Captcha::render() !!}

                </x-shop::form.control-group>

                <div class="flex gap-[36px] flex-wrap mt-[30px] items-center">
                    <button
                        class="m-0 ml-[0px] block mx-auto w-full bg-navyBlue text-white text-[16px] max-w-[374px] font-medium py-[16px] px-[43px] rounded-[18px] text-center"
                        type="submit"
                    >
                        @lang('shop::app.customer.forgot-password.submit')
                    </button>
                </div>

                {!! view_render_event('bagisto.shop.customers.forget_password.after') !!}

            </x-shop::form>

        </div>

        <p class="mt-[30px] mb-[15px] text-center text-[#7d7d7d] text-xs">
            @lang('shop::app.customer.login-form.footer')
        </p>
    </div>

    @push('scripts')

        {!! Captcha::renderJS() !!}
    
    @endpush
</x-shop::layouts>
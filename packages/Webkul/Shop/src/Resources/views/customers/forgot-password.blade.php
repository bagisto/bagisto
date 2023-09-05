<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('shop::app.customers.forgot-password.title')
    </x-slot>

    <div class="container mt-20 max-1180:px-[20px]">
        {{-- Company Logo --}}
        <div class="flex gap-x-[54px] items-center max-[1180px]:gap-x-[35px]">
            <a
                href="{{ route('shop.home.index') }}" 
                class="m-[0_auto_20px_auto]"
            >
                <img src="{{ bagisto_asset('images/logo.svg') }}">
            </a>
        </div>
        
        {{-- Form Container --}}
        <div
            class="w-full max-w-[870px] m-auto px-[90px] py-[60px] border border-[#E9E9E9] rounded-[12px] max-md:px-[30px] max-md:py-[30px]"
        >
            <h1 class="text-[40px] font-dmserif max-sm:text-[25px]">
                @lang('shop::app.customers.forgot-password.title')
            </h1>

            <p class="mt-[15px] text-[#7D7D7D] text-[20px] max-sm:text-[16px]">
                @lang('shop::app.customers.forgot-password.forgot-password-text')
            </p>

            {!! view_render_event('bagisto.shop.customers.forget_password.before') !!}

            <div class="mt-[60px] rounded max-sm:mt-[30px]">
                <x-shop::form :action="route('shop.customers.forgot_password.store')">
                    {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.before') !!}

                    <x-shop::form.control-group class="mb-4">
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.customers.login-form.email')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="email"
                            name="email"
                            class="!p-[20px_25px] rounded-lg"
                            value=""
                            rules="required|email"
                            :label="trans('shop::app.customers.login-form.email')"
                            placeholder="email@example.com"
                        >
                        </x-shop::form.control-group.control>

                        <x-shop::form.control-group.error
                            control-name="email"
                        >
                        </x-shop::form.control-group.error>
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.before') !!}

                    <div>

                        {!! Captcha::render() !!}

                    </div>

                    <div class="flex gap-[36px] flex-wrap mt-[30px] items-center">
                        <button
                            class="bs-primary-button block w-full max-w-[374px] m-0 ml-[0px] mx-auto px-[43px] py-[16px] rounded-[18px] text-[16px] text-center"
                            type="submit"
                        >
                            @lang('shop::app.customers.forgot-password.submit')
                        </button>
                    </div>

                    <p class="mt-[20px] text-[#7D7D7D] font-medium">
                        @lang('shop::app.customers.forgot-password.back')
                        
                        <a class="text-navyBlue" 
                            href="{{ route('shop.customer.session.index') }}"
                        >
                            @lang('shop::app.customers.forgot-password.sign-in-button')
                        </a>
                    </p>

                    {!! view_render_event('bagisto.shop.customers.forget_password.after') !!}

                </x-shop::form>
            </div>
        </div>

        <p class="mt-[30px] mb-[15px] text-[#7d7d7d] text-xs text-center">
            @lang('shop::app.customers.forgot-password.footer')
        </p>
    </div>

    @push('scripts')

        {!! Captcha::renderJS() !!}
    
    @endpush
</x-shop::layouts>
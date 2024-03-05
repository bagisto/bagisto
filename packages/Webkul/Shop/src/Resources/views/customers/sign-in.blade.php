<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="@lang('shop::app.customers.login-form.page-title')"/>

    <meta name="keywords" content="@lang('shop::app.customers.login-form.page-title')"/>
@endPush

<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.login-form.page-title')
    </x-slot>

    <div class="container mt-20 max-1180:px-5">
        {!! view_render_event('bagisto.shop.customers.login.logo.before') !!}

        <!-- Company Logo -->
        <div class="flex gap-x-14 items-center max-[1180px]:gap-x-9">
            <a
                href="{{ route('shop.home.index') }}"
                class="m-[0_auto_20px_auto]"
                aria-label="@lang('shop::app.customers.login-form.bagisto')"
            >
                <img
                    src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                    alt="{{ config('app.name') }}"
                    width="131"
                    height="29"
                >
            </a>
        </div>

        {!! view_render_event('bagisto.shop.customers.login.logo.after') !!}

        <!-- Form Container -->
        <div
            class="w-full max-w-[870px] m-auto px-[90px] p-16 border border-[#E9E9E9] rounded-xl max-md:px-8 max-md:py-8"
        >
            <h1 class="text-4xl font-dmserif max-sm:text-2xl">
                @lang('shop::app.customers.login-form.page-title')
            </h1>

            <p class="mt-4 text-[#6E6E6E] text-xl max-sm:text-base">
                @lang('shop::app.customers.login-form.form-login-text')
            </p>

            {!! view_render_event('bagisto.shop.customers.login.before') !!}

            <div class="mt-14 rounded max-sm:mt-8">
                <x-shop::form :action="route('shop.customer.session.create')">

                    {!! view_render_event('bagisto.shop.customers.login_form_controls.before') !!}

                    <!-- Email -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.customers.login-form.email')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="email"
                            class="!p-[20px_25px] rounded-lg"
                            name="email"
                            rules="required|email"
                            value=""
                            :label="trans('shop::app.customers.login-form.email')"
                            placeholder="email@example.com"
                            aria-label="@lang('shop::app.customers.login-form.email')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="email" />
                    </x-shop::form.control-group>

                    <!-- Password -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.customers.login-form.password')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="password"
                            class="!p-[20px_25px] rounded-lg"
                            id="password"
                            name="password"
                            rules="required|min:6"
                            value=""
                            :label="trans('shop::app.customers.login-form.password')"
                            :placeholder="trans('shop::app.customers.login-form.password')"
                            aria-label="@lang('shop::app.customers.login-form.password')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="password" />
                    </x-shop::form.control-group>

                    <div class="flex justify-between">
                        <div class="select-none items-center flex gap-1.5">
                            <input
                                type="checkbox"
                                id="show-password"
                                class="hidden peer"
                                onchange="switchVisibility()"
                            />

                            <label
                                class="icon-uncheck text-2xl text-navyBlue peer-checked:icon-check-box peer-checked:text-navyBlue cursor-pointer"
                                for="show-password"
                            ></label>

                            <label
                                class="text-base text-[#6E6E6E] max-sm:text-xs ltr:pl-0 rtl:pr-0 select-none cursor-pointer"
                                for="show-password"
                            >
                                @lang('shop::app.customers.login-form.show-password')
                            </label>
                        </div>

                        <div class="block">
                            <a
                                href="{{ route('shop.customers.forgot_password.create') }}"
                                class="text-base cursor-pointer text-black max-sm:text-xs"
                            >
                                <span>
                                    @lang('shop::app.customers.login-form.forgot-pass')
                                </span>
                            </a>
                        </div>
                    </div>

                    <!-- Captcha -->
                    @if (core()->getConfigData('customer.captcha.credentials.status'))
                        <div class="flex mt-5">
                            {!! Captcha::render() !!}
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="flex gap-9 flex-wrap mt-8 items-center">
                        <button
                            class="primary-button block w-full max-w-[374px] py-4 px-11 m-0 ltr:ml-0 rtl:mr-0 mx-auto rounded-2xl text-base text-center"
                            type="submit"
                        >
                            @lang('shop::app.customers.login-form.button-title')
                        </button>

                        {!! view_render_event('bagisto.shop.customers.login_form_controls.after') !!}
                    </div>
                </x-shop::form>
            </div>

            {!! view_render_event('bagisto.shop.customers.login.after') !!}

            <p class="mt-5 text-[#6E6E6E] font-medium">
                @lang('shop::app.customers.login-form.new-customer')

                <a
                    class="text-navyBlue"
                    href="{{ route('shop.customers.register.index') }}"
                >
                    @lang('shop::app.customers.login-form.create-your-account')
                </a>
            </p>
        </div>

        <p class="mt-8 mb-4 text-center text-[#6E6E6E] text-xs">
            @lang('shop::app.customers.login-form.footer', ['current_year'=> date('Y') ])
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

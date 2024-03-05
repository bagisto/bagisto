<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="@lang('shop::app.customers.forgot-password.title')"/>

    <meta name="keywords" content="@lang('shop::app.customers.forgot-password.title')"/>
@endPush

<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.forgot-password.title')
    </x-slot>

    <div class="container mt-20 max-1180:px-5">
        {!! view_render_event('bagisto.shop.customers.forget_password.logo.before') !!}

        <!-- Company Logo -->
        <div class="flex gap-x-14 items-center max-[1180px]:gap-x-9">
            <a
                href="{{ route('shop.home.index') }}"
                class="m-[0_auto_20px_auto]"
                aria-label="@lang('shop::app.customers.forgot-password.bagisto')"
            >
                <img
                    src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                    alt="{{ config('app.name') }}"
                    width="131"
                    height="29"
                >
            </a>
        </div>

        {!! view_render_event('bagisto.shop.customers.forget_password.logo.after') !!}

        <!-- Form Container -->
        <div
            class="w-full max-w-[870px] m-auto px-[90px] p-16 border border-[#E9E9E9] rounded-xl max-md:px-8 max-md:py-8"
        >
            <h1 class="text-4xl font-dmserif max-sm:text-2xl">
                @lang('shop::app.customers.forgot-password.title')
            </h1>

            <p class="mt-4 text-[#6E6E6E] text-xl max-sm:text-base">
                @lang('shop::app.customers.forgot-password.forgot-password-text')
            </p>

            {!! view_render_event('bagisto.shop.customers.forget_password.before') !!}

            <div class="mt-14 rounded max-sm:mt-8">
                <x-shop::form :action="route('shop.customers.forgot_password.store')">
                    {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.before') !!}

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

                    {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.email.after') !!}

                    <div>

                        {!! Captcha::render() !!}

                    </div>

                    <div class="flex gap-9 flex-wrap mt-8 items-center">
                        <button
                            class="primary-button block w-full max-w-[374px] m-0 ltr:ml-0 rtl:mr-0 mx-auto px-11 py-4 rounded-2xl text-base text-center"
                            type="submit"
                        >
                            @lang('shop::app.customers.forgot-password.submit')
                        </button>
                    </div>

                    <p class="mt-5 text-[#6E6E6E] font-medium">
                        @lang('shop::app.customers.forgot-password.back')

                        <a class="text-navyBlue"
                            href="{{ route('shop.customer.session.index') }}"
                        >
                            @lang('shop::app.customers.forgot-password.sign-in-button')
                        </a>
                    </p>

                    {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.after') !!}

                </x-shop::form>
            </div>

            {!! view_render_event('bagisto.shop.customers.forget_password.after') !!}

        </div>

        <p class="mt-8 mb-4 text-[#6E6E6E] text-xs text-center">
            @lang('shop::app.customers.forgot-password.footer', ['current_year'=> date('Y') ])
        </p>
    </div>

    @push('scripts')

        {!! Captcha::renderJS() !!}

    @endpush
</x-shop::layouts>

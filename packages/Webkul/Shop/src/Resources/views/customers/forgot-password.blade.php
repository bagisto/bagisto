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

    <div class="container mt-20 max-1180:px-5 max-md:mt-12">
        {!! view_render_event('bagisto.shop.customers.forget_password.logo.before') !!}

        <!-- Company Logo -->
        <div class="flex items-center gap-x-14 max-[1180px]:gap-x-9">
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
            class="m-auto w-full max-w-[870px] rounded-xl border border-zinc-200 p-16 px-[90px] max-md:px-8 max-md:py-8 max-sm:border-none max-sm:p-0"
        >
            <h1 class="font-dmserif text-4xl max-md:text-3xl max-sm:text-xl">
                @lang('shop::app.customers.forgot-password.title')
            </h1>

            <p class="mt-4 text-xl text-zinc-500 max-sm:mt-0 max-sm:text-sm">
                @lang('shop::app.customers.forgot-password.forgot-password-text')
            </p>

            {!! view_render_event('bagisto.shop.customers.forget_password.before') !!}

            <div class="mt-14 rounded max-sm:mt-8">
                <x-shop::form :action="route('shop.customers.forgot_password.store')">
                    {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.before') !!}

                    <!-- Email -->
                    <x-shop::form.control-group class="max-sm:mb-1.5">
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.customers.login-form.email')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="email"
                            class="px-6 py-4 max-sm:py-1.5"
                            name="email"
                            rules="required|email"
                            value=""
                            :label="trans('shop::app.customers.login-form.email')"
                            placeholder="email@example.com"
                            :aria-label="trans('shop::app.customers.login-form.email')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="email" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.email.after') !!}

                    <div>

                        {!! \Webkul\Customer\Facades\Captcha::render() !!}

                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex flex-wrap items-center gap-9 max-sm:mt-0 max-sm:justify-center max-sm:text-center">
                        <button
                            class="primary-button m-0 mx-auto block w-full max-w-[374px] rounded-2xl px-11 py-4 text-center text-base max-md:max-w-full max-md:rounded-lg max-md:py-3 max-sm:py-1.5 max-sm:text-sm ltr:ml-0 rtl:mr-0"
                            type="submit"
                        >
                            @lang('shop::app.customers.forgot-password.submit')
                        </button>
                    </div>

                    <p class="mt-5 font-medium text-zinc-500 max-sm:text-center max-sm:text-sm">
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

        <p class="mb-4 mt-8 text-center text-xs text-zinc-500">
            @lang('shop::app.customers.forgot-password.footer', ['current_year'=> date('Y') ])
        </p>
    </div>

    @push('scripts')

        {!! \Webkul\Customer\Facades\Captcha::renderJS() !!}

    @endpush
</x-shop::layouts>

<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('shop::app.customers.reset-password.title')
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
                @lang('shop::app.customers.reset-password.title')
            </h1>

            {!! view_render_event('bagisto.shop.customers.reset_password.before') !!}

            <div class="mt-[60px] rounded max-sm:mt-[30px]">
                <x-shop::form :action="route('shop.customers.reset_password.store')" >
                    <x-shop::form.control-group.control
                        type="hidden"
                        name="token"
                        :value="$token"
                    >
                    </x-shop::form.control-group.control>

                    {!! view_render_event('bagisto.shop.customers.reset_password_form_controls.before') !!}

                    <x-shop::form.control-group class="mb-4">
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.customers.reset-password.email')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="email"
                            name="email"
                            class="!p-[20px_25px] rounded-lg"
                            :value="old('email')"
                            id="email"
                            rules="required|email"
                            :label="trans('shop::app.customers.reset-password.email')"
                            placeholder="email@example.com"
                        >
                        </x-shop::form.control-group.control>

                        <x-shop::form.control-group.error
                            control-name="email"
                        >
                        </x-shop::form.control-group.error>
                    </x-shop::form.control-group>

                    <x-shop::form.control-group class="mb-6">
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.customers.reset-password.password')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="password"
                            name="password"
                            class="!p-[20px_25px] rounded-lg"
                            value=""
                            ref="password"
                            rules="required|min:6"
                            :label="trans('shop::app.customers.reset-password.password')"
                            :placeholder="trans('shop::app.customers.reset-password.password')"
                        >
                        </x-shop::form.control-group.control>

                        <x-shop::form.control-group.error
                            control-name="password"
                        >
                        </x-shop::form.control-group.error>
                    </x-shop::form.control-group>

                    <x-shop::form.control-group class="mb-6">
                        <x-shop::form.control-group.label>
                            @lang('shop::app.customers.reset-password.confirm-password')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="password"
                            name="password_confirmation"
                            class="!p-[20px_25px] rounded-lg"
                            value=""
                            rules="confirmed:@password"
                            :label="trans('shop::app.customers.reset-password.confirm-password')"
                            :placeholder="trans('shop::app.customers.reset-password.confirm-password')"
                        >
                        </x-shop::form.control-group.control>

                        <x-shop::form.control-group.error
                            control-name="password"
                        >
                        </x-shop::form.control-group.error>
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.reset_password_form_controls.before') !!}

                    <div class="flex gap-[36px] flex-wrap mt-[30px] items-center">
                        <button
                            class="bs-primary-button block w-full max-w-[374px] py-[16px] px-[43px] m-0 ml-[0px] mx-auto rounded-[18px] text-[16px] text-center"
                            type="submit"
                        >
                            @lang('shop::app.customers.reset-password.submit-btn-title')
                        </button>
                    </div>

                </x-shop::form>
            </div>

            {!! view_render_event('bagisto.shop.customers.reset_password.before') !!}

        </div>

        <p class="mt-[30px] mb-[15px] text-center text-[#7d7d7d] text-xs">
            @lang('shop::app.customers.reset_password.footer')
        </p>
    </div>
</x-shop::layouts>

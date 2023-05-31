<x-shop::layouts>
    <div class="container mt-20 max-1180:px-[20px]">
        <div
            class="w-full max-w-[870px] m-auto border border-[#E9E9E9] px-[90px] py-[60px] rounded-[12px] max-md:px-[30px] max-md:py-[30px]"
        >
            <h1 class="text-[40px] font-dmserif max-sm:text-[25px]">
                @lang('shop::app.customer.reset-password.title')
            </h1>

            {!! view_render_event('bagisto.shop.customers.reset_password.before') !!}

            <x-shop::form
                :action="route('shop.customers.reset_password.store')"
                class="rounded mt-[60px] max-sm:mt-[30px]"
            >
                <x-shop::form.control-group.control
                    type="hidden"
                    name="token"
                    :value="$token"       
                >
                </x-shop::form.control-group.control>

                {!! view_render_event('bagisto.shop.customers.reset_password_form_controls.before') !!}

                <x-shop::form.control-group class="mb-4">
                    <x-shop::form.control-group.label>
                        @lang('shop::app.customer.reset-password.email')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="email"
                        name="email"
                        :value="old('email')"
                        id="email" 
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
                        @lang('shop::app.customer.reset-password.password')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="password"
                        name="password"
                        value=""
                        ref="password"
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

                <x-shop::form.control-group class="mb-6">
                    <x-shop::form.control-group.label>
                        @lang('shop::app.customer.reset-password.confirm-password')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="password"
                        name="password_confirmation"
                        value=""
                        rules="confirmed:@password"
                        label="Confirm Password"
                        placeholder="Confirm Password"
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
                        class="m-0 ml-[0px] block mx-auto w-full bg-navyBlue text-white text-[16px] max-w-[374px] font-medium py-[16px] px-[43px] rounded-[18px] text-center"
                        type="submit"
                    >
                        @lang('shop::app.customer.reset-password.submit-btn-title')
                    </button>
                </div>

            </x-shop::form>

            {!! view_render_event('bagisto.shop.customers.reset_password.before') !!}

        </div>

        <p class="mt-[30px] mb-[15px] text-center text-[#7d7d7d] text-xs">
            @lang('shop::app.customer.login-form.footer')
        </p>
    </div>
</x-shop::layouts>
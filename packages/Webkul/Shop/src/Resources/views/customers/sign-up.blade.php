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
                @lang('shop::app.customers.signup-form.page-title')
            </h1>

			<p class="text-[#7D7D7D] text-[20px] mt-[15px] max-sm:text-[16px]"> 
                @lang('shop::app.customers.signup-form.form-signup-text')
            </p>

            <div class="rounded mt-[60px] max-sm:mt-[30px]">
                <x-shop::form :action="route('shop.customers.register.store')">
                    {!! view_render_event('bagisto.shop.customers.signup_form_controls.before') !!}

                    <x-shop::form.control-group class="mb-4">
                        <x-shop::form.control-group.label>
                            @lang('shop::app.customers.signup-form.firstname')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            name="first_name"
                            :value="old('last_name')"
                            rules="required"
                            label="First Name"
                            placeholder="First Name"
                        >
                        </x-shop::form.control-group.control>

                        <x-shop::form.control-group.error
                            control-name="first_name"
                        >
                        </x-shop::form.control-group.error>
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.signup_form_controls.firstname.after') !!}

                    <x-shop::form.control-group class="mb-4">
                        <x-shop::form.control-group.label>
                            @lang('shop::app.customers.signup-form.lastname')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            name="last_name"
                            :value="old('last_name')"
                            rules="required"
                            label="Last Name"
                            placeholder="Last Name"
                        >
                        </x-shop::form.control-group.control>

                        <x-shop::form.control-group.error
                            control-name="last_name"
                        >
                        </x-shop::form.control-group.error>
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.signup_form_controls.lastname.after') !!}

                    <x-shop::form.control-group class="mb-4">
                        <x-shop::form.control-group.label>
                            @lang('shop::app.customers.signup-form.email')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="email"
                            name="email"
                            :value="old('email')"
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

                    {!! view_render_event('bagisto.shop.customers.signup_form_controls.email.after') !!}

                    <x-shop::form.control-group class="mb-6">
                        <x-shop::form.control-group.label>
                            @lang('shop::app.customers.signup-form.password')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="password"
                            name="password"
                            :value="old('password')"
                            rules="required|min:6"
                            ref="password"
                            label="Password"
                            placeholder="Password"
                        >
                        </x-shop::form.control-group.control>

                        <x-shop::form.control-group.error
                            control-name="password"
                        >
                        </x-shop::form.control-group.error>
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.signup_form_controls.password.after') !!}

                    <x-shop::form.control-group class="mb-6">
                        <x-shop::form.control-group.label>
                            @lang('shop::app.customers.signup-form.confirm_pass')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="password"
                            name="password_confirmation"
                            value=""
                            rules="confirmed:@password"
                            label="Password"
                            placeholder="Confirm Password"
                        >
                        </x-shop::form.control-group.control>

                        <x-shop::form.control-group.error
                            control-name="password_confirmation"
                        >
                        </x-shop::form.control-group.error>
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.signup_form_controls.password_confirmation.after') !!}


                    <div class="mb-[20px]">
                        {!! Captcha::render() !!}
                    </div>

                    @if (core()->getConfigData('customer.settings.newsletter.subscription'))
                        <div class="flex justify-between">
                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.control
                                        type="checkbox"
                                        name="is_subscribed"
                                        id="checkbox2"
                                    >
                                    <span
                                        class="select-none  text-[16] text-navyBlue max-sm:text-[12px]"
                                    > 
                                        @lang('shop::app.customers.signup-form.subscribe-to-newsletter')
                                    </span>
                                    </x-shop::form.control-group.control>
                                </x-shop::form.control-group>
                        </div>
                    @endif

                    {!! view_render_event('bagisto.shop.customers.signup_form_controls.after') !!}

                    <div class="flex gap-[36px] flex-wrap mt-[30px] items-center">
                        <button
                            class="m-0 ml-[0px] block mx-auto w-full bg-navyBlue text-white text-[16px] max-w-[374px] font-medium py-[16px] px-[43px] rounded-[18px] text-center"
                            type="submit"
                        >
                            @lang('shop::app.customers.signup-form.button_title')
                        </button>

                        <div class="flex gap-[15px] flex-wrap">
                            <a
                                href="" class="bg-[position:0px_-274px] bs-main-sprite w-[40px] h-[40px]"
                                aria-label="Facebook"
                            >
                            </a>

                            <a
                                href=""
                                class="bg-[position:-40px_-274px] bs-main-sprite w-[40px] h-[40px]"
                                aria-label="Twitter"
                            >
                            </a>

                            <a
                                href="" class="bg-[position:-80px_-274px] bs-main-sprite w-[40px] h-[40px]"
                                aria-label="Pintrest"
                            >
                            </a>

                            <a
                                href=""
                                class="bg-[position:-120px_-274px] bs-main-sprite w-[40px] h-[40px]"
                                aria-label="LinkedIn"
                            >
                            </a>
                        </div>
                    </div>
                </x-shop::form>
            </div>

			<p class="text-[#7D7D7D] font-medium mt-[20px]">
                @lang('shop::app.customers.signup-form.account_exists')
                
                <a class="text-navyBlue" 
                    href="{{ route('shop.customer.session.index') }}"
                >
                    @lang('shop::app.customers.signup-form.sign-in-button')
                </a>
            </p>
		</div>

        <p class="mt-[30px]  mb-[15px] text-center text-[#7d7d7d] text-xs">
            @lang('shop::app.customer.signup-form.footer')
        </p>
	</div>

    @push('scripts')
        {!! Captcha::renderJS() !!}
    @endpush
</x-shop::layouts>

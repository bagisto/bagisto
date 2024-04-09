<!-- Page Layout -->
<x-shop::layouts>
    <!-- Page Content -->
    <div class="container mt-8 px-[60px] max-lg:px-8">
        <!-- Form Container -->
		<div class="w-full max-w-[870px] m-auto px-[90px] p-16 border border-[#E9E9E9] rounded-xl max-md:px-8 max-md:py-8">
			<h1 class="text-4xl font-dmserif max-sm:text-2xl">
                @lang('Contact Us')
            </h1>

			<p class="mt-4 text-[#6E6E6E] text-xl max-sm:text-base">
                @lang('Jot us a note, and we’ll get back to you as quickly as possible.')
            </p>

            <div class="mt-14 rounded max-sm:mt-8">
                <x-shop::form :action="route('shop.contact_us.store')">
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('Name')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            class="!p-[20px_25px] rounded-lg"
                            name="name"
                            rules="required"
                            :value="old('name')"
                            :label="trans('name')"
                            :placeholder="trans('Name')"
                            aria-label="@lang('name')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="name" />
                    </x-shop::form.control-group>

                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('Email')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="email"
                            class="!p-[20px_25px] rounded-lg"
                            name="email"
                            rules="required|email"
                            :value="old('email')"
                            :label="trans('email')"
                            :placeholder="trans('Email')"
                            aria-label="@lang('email')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="email" />
                    </x-shop::form.control-group>

                    <x-shop::form.control-group class="mb-6">
                        <x-shop::form.control-group.label>
                            @lang('Phone Number')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="number"
                            class="!p-[20px_25px] rounded-lg"
                            name="contact"
                            :value="old('contact')"
                            :label="trans('contact')"
                            :placeholder="trans('Phone Number')"
                            aria-label="@lang('contact')"
                        />

                        <x-shop::form.control-group.error control-name="contact" />
                    </x-shop::form.control-group>

                    <x-shop::form.control-group class="mb-6">
                        <x-shop::form.control-group.label class="required">
                            @lang('What’s on your mind?')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="textarea"
                            class="!p-[20px_25px] rounded-lg"
                            name="message"
                            rules="required"
                            :label="trans('message')"
                            :placeholder="trans('Describe Here')"
                            aria-label="@lang('message')"
                            aria-required="true"
                            rows="10"
                        />

                        <x-shop::form.control-group.error control-name="message" />
                    </x-shop::form.control-group>

                    @if (core()->getConfigData('customer.captcha.credentials.status'))
                        <div class="flex mb-5">
                            {!! Captcha::render() !!}
                        </div>
                    @endif

                    <div class="flex gap-9 flex-wrap items-center mt-8">
                        <button
                            class="primary-button block w-full max-w-[374px] py-4 px-11 mx-auto m-0 ltr:ml-0 rtl:mr-0 rounded-2xl text-base text-center"
                            type="submit"
                        >
                            @lang('Submit')
                        </button>
                    </div>
                </x-shop::form>
            </div>
		</div>
    </div>
</x-shop::layouts>
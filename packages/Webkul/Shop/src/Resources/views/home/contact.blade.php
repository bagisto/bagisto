<!-- Page Layout -->
<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.home.contact.title')
    </x-slot>

    <!-- Page Content -->
    <div class="container mt-8 px-[60px] max-lg:px-8">
        <!-- Form Container -->
		<div class="w-full max-w-[870px] m-auto px-[90px] p-16 border border-[#E9E9E9] rounded-xl max-md:px-8 max-md:py-8">
			<h1 class="text-4xl font-dmserif max-sm:text-2xl">
                @lang('shop::app.home.contact.title')
            </h1>

			<p class="mt-4 text-[#6E6E6E] text-xl max-sm:text-base">
                @lang('shop::app.home.contact.about')
            </p>

            <div class="mt-14 rounded max-sm:mt-8">
                <x-shop::form :action="route('shop.home.contact.store')">
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.home.contact.name')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            name="name"
                            rules="required"
                            :value="old('name')"
                            :label="trans('shop::app.home.contact.name')"
                            :placeholder="trans('shop::app.home.contact.name')"
                            aria-label="@lang('shop::app.home.contact.name')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="name" />
                    </x-shop::form.control-group>

                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.home.contact.email')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="email"
                            class="!p-[20px_25px] rounded-lg"
                            name="email"
                            rules="required|email"
                            :value="old('email')"
                            :label="trans('shop::app.home.contact.email')"
                            :placeholder="trans('shop::app.home.contact.email')"
                            aria-label="@lang('shop::app.home.contact.email')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="email" />
                    </x-shop::form.control-group>

                    <x-shop::form.control-group class="mb-6">
                        <x-shop::form.control-group.label>
                            @lang('shop::app.home.contact.phone-number')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="number"
                            name="contact"
                            :value="old('contact')"
                            :label="trans('shop::app.home.contact.phone-number')"
                            :placeholder="trans('shop::app.home.contact.phone-number')"
                            aria-label="@lang('shop::app.home.contact.phone-number')"
                        />

                        <x-shop::form.control-group.error control-name="contact" />
                    </x-shop::form.control-group>

                    <x-shop::form.control-group class="mb-6">
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.home.contact.desc')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="textarea"
                            name="message"
                            rules="required"
                            :label="trans('shop::app.home.contact.message')"
                            :placeholder="trans('shop::app.home.contact.describe-here')"
                            aria-label="@lang('shop::app.home.contact.message')"
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
                            @lang('shop::app.home.contact.submit')
                        </button>
                    </div>
                </x-shop::form>
            </div>
		</div>
    </div>

    @push('scripts')
        {!! Captcha::renderJS() !!}
    @endpush
</x-shop::layouts>
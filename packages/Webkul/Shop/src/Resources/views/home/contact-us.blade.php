<!-- Page Layout -->
<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.home.contact.title')
    </x-slot>

    <div class="container mt-8 max-1180:px-5 max-md:mt-6 max-md:px-4">
        <!-- Form Container -->
		<div class="m-auto w-full max-w-[870px] rounded-xl border border-zinc-200 p-16 px-[90px] max-md:px-8 max-md:py-8 max-sm:border-none max-sm:p-0">
			<h1 class="font-dmserif text-4xl max-md:text-3xl max-sm:text-xl">
                @lang('shop::app.home.contact.title')
            </h1>

			<p class="mt-4 text-xl text-zinc-500 max-sm:mt-1 max-sm:text-sm">
                @lang('shop::app.home.contact.about')
            </p>

            <div class="mt-14 rounded max-sm:mt-8">
                <!-- Contact Form -->
                <x-shop::form :action="route('shop.home.contact_us.send_mail')">
                    <!-- Name -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.home.contact.name')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            class="px-6 py-5 max-md:py-3 max-sm:py-3.5"
                            name="name"
                            rules="required"
                            :value="old('name')"
                            :label="trans('shop::app.home.contact.name')"
                            :placeholder="trans('shop::app.home.contact.name')"
                            :aria-label="trans('shop::app.home.contact.name')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="name" />
                    </x-shop::form.control-group>

                    <!-- Email -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.home.contact.email')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="email"
                            class="px-6 py-5 max-md:py-3 max-sm:py-3.5"
                            name="email"
                            rules="required|email"
                            :value="old('email')"
                            :label="trans('shop::app.home.contact.email')"
                            :placeholder="trans('shop::app.home.contact.email')"
                            :aria-label="trans('shop::app.home.contact.email')"
                            aria-required="true"
                        />

                        <x-shop::form.control-group.error control-name="email" />
                    </x-shop::form.control-group>

                    <!-- Contact -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label>
                            @lang('shop::app.home.contact.phone-number')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            class="px-6 py-5 max-md:py-3 max-sm:py-3.5"
                            name="contact"
                            rules="phone"
                            :value="old('contact')"
                            :label="trans('shop::app.home.contact.phone-number')"
                            :placeholder="trans('shop::app.home.contact.phone-number')"
                            :aria-label="trans('shop::app.home.contact.phone-number')"
                        />

                        <x-shop::form.control-group.error control-name="contact" />
                    </x-shop::form.control-group>

                    <!-- Message -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.home.contact.desc')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="textarea"
                            class="px-6 py-5 max-md:py-3 max-sm:py-3.5"
                            name="message"
                            rules="required"
                            :label="trans('shop::app.home.contact.message')"
                            :placeholder="trans('shop::app.home.contact.describe-here')"
                            :aria-label="trans('shop::app.home.contact.message')"
                            aria-required="true"
                            rows="10"
                        />

                        <x-shop::form.control-group.error control-name="message" />
                    </x-shop::form.control-group>

                    <!-- Re captcha -->
                    @if (core()->getConfigData('customer.captcha.credentials.status'))
                        <div class="mb-5 flex">
                            {!! \Webkul\Customer\Facades\Captcha::render() !!}
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="mt-8 flex flex-wrap items-center gap-9 max-sm:justify-center max-sm:text-center">
                        <button
                            class="primary-button m-0 mx-auto block w-full max-w-[374px] rounded-2xl px-11 py-4 text-center text-base max-md:max-w-full max-md:rounded-lg max-md:py-3 max-sm:py-1.5 ltr:ml-0 rtl:mr-0"
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
        {!! \Webkul\Customer\Facades\Captcha::renderJS() !!}
    @endpush
</x-shop::layouts>

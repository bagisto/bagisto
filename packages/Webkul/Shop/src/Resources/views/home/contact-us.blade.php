<!-- Page Layout -->
<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.home.contact.title')
    </x-slot>
    <!-- Page Content -->
    <div class="container-fluid bg-rainbow">
        <div class="container py-5">
            <div class="flex mx-auto justify-center py-6 text-2xl items-center">
                {{ $page->page_title }}
            </div>
        </div>
        <div class="container-fluid bg-white py-5">
            <div class="container">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-span-6 md:col-span-1">
                        <div class="w-fit p-4">
                            <a class="relative inline-block" href="#">
                                <img src="{{ bagisto_asset('images/custom/contact-'.app()->getLocale().'.webp') }}" alt="" width="700" height="600">
                                <div class="absolute inset-0 bg-black opacity-0 hover:opacity-30 transition-opacity duration-500">&nbsp;</div>
                            </a>
                        </div>
                    </div>
                    <div class="col-span-6 md:col-span-1">
                        <h5 class="mt-5 text-5xl txt_blue font-bold text-pelorous-500">
                            @lang('shop::app.home.contact.headline')
                        </h5>
                        <p class="my-8 text-gray-600">
                            @lang('shop::app.home.contact.subheadline')
                        </p>
                        <div class="grid grid-cols-2 mt-3">
                        @foreach(__('shop::app.home.contact.details') as $key => $value)
                            <div class="col">
                                <div class="row my-5">
                                    <div class="text-md font-bold text-pelorous-500">
                                        @lang('shop::app.home.contact.titles.'.$key)
                                    </div>
                                    <p class="text-1x1 text-gray-500">{{ $value }}</p>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="container mt-8 max-1180:px-5">
                <!-- Form Container -->
                <div class="m-auto w-full border border-zinc-200 p-16 px-[90px] max-md:px-8 max-md:py-8">
                    <h1 class="font-dmserif text-center text-4xl max-sm:text-2xl">
                        @lang('shop::app.home.contact.title')
                    </h1>

                    <p class="mt-4 text-xl text-center text-zinc-500 max-sm:text-base">
                        @lang('shop::app.home.contact.about')
                    </p>

                    <div class="mt-14 max-sm:mt-8">
                        <!-- Contact Form -->
                        <x-shop::form :action="route('shop.home.contact_us.send_mail')">
                            <!-- Name -->
                            <div class="grid grid-cols-1 md:grid-cols-2">
                                <div class="col pr-10">
                                    <x-shop::form.control-group>
                                        <x-shop::form.control-group.label class="required">
                                            @lang('shop::app.home.contact.name')
                                        </x-shop::form.control-group.label>

                                        <x-shop::form.control-group.control
                                            type="text"
                                            class="px-6 py-5"
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
                                            class="px-6 py-5"
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
                                    class="px-6 py-5"
                                    name="contact"
                                    rules="phone"
                                    :value="old('contact')"
                                    :label="trans('shop::app.home.contact.phone-number')"
                                    :placeholder="trans('shop::app.home.contact.phone-number')"
                                    :aria-label="trans('shop::app.home.contact.phone-number')"
                                />

                                <x-shop::form.control-group.error control-name="contact" />
                            </x-shop::form.control-group>
                                </div>
                                <div class="col">
                            <!-- Message -->
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="required">
                                    @lang('shop::app.home.contact.desc')
                                </x-shop::form.control-group.label>

                                <x-shop::form.control-group.control
                                    type="textarea"
                                    class="px-6 py-5"
                                    name="message"
                                    rules="required"
                                    :label="trans('shop::app.home.contact.message')"
                                    :placeholder="trans('shop::app.home.contact.describe-here')"
                                    :aria-label="trans('shop::app.home.contact.message')"
                                    aria-required="true"
                                    rows="13"
                                />

                                <x-shop::form.control-group.error control-name="message" />
                            </x-shop::form.control-group>

                            <!-- Recaptcha -->
                            @if (core()->getConfigData('customer.captcha.credentials.status'))
                                <div class="mb-5 flex">
                                    {!! Captcha::render() !!}
                                </div>
                            @endif
                                </div>
                            </div>
                            <!-- Submit Button -->
                            <div class="mt-8 flex flex-wrap items-center gap-9">
                                <button
                                    class="primary-button m-0 mx-auto block w-full max-w-[374px] px-11 text-center text-base ltr:ml-0 rtl:mr-0"
                                    type="submit"
                                >
                                    @lang('shop::app.home.contact.submit')
                                </button>
                            </div>
                        </x-shop::form>
                    </div>
                </div>
            </div>
		</div>
    </div>

    <iframe class="w-full aspect-auto" src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d5918.3457285808345!2d24.737001815743607!3d42.12518242586096!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sbg!2sbg!4v1683470067925!5m2!1sbg!2sbg" width="100%" height="400" frameborder="0"></iframe>

    @push('scripts')
        {!! Captcha::renderJS() !!}
    @endpush
</x-shop::layouts>

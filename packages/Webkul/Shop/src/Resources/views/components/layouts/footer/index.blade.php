{!! view_render_event('bagisto.shop.layout.footer.before') !!}
@inject('themeCustomizationRepository', 'Webkul\Theme\Repositories\ThemeCustomizationRepository')
@php
    $customization = $themeCustomizationRepository->findOneWhere([
            'type'       => 'footer_links',
            'status'     => 1,
            'channel_id' => core()->getCurrentChannel()->id,
        ]);
@endphp


<footer class="bg-footer bg-cover bg-no-repeat bg-center mt-7 ">
    <div class="container mx-auto">
        <div class="row mt-5 px-4 md:px-8 lg:px-12">
            <div class="card shadow-lg p-5 border bg-white rounded-lg">
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- First card -->
                        <div class="items-center col-span-1 md:col-span-1">
                            <a href="tel:@lang('shop::app.components.layouts.footer.phone')" class="card border-0 p-4 flex items-center">
                                <div class="flex-none items-center justify-center w-[60px] h-[60px] rounded-full border border-white fill-pelorous-600 p-2.5 bg-pelorous-600 transition hover:bg-pelorous-500">
                                    <span class="material-icons text-4xl text-white">phone</span>
                                </div>
                                <div class="flex-grow ml-4 items-center">
                                    <h5 class="card-title">@lang('shop::app.components.layouts.footer.phone-label')</h5>
                                    <p class="card-text">@lang('shop::app.components.layouts.footer.phone')</p>
                                </div>
                            </a>
                        </div>
                        <!-- Second card -->
                        <div class="items-center col-span-1 md:col-span-1">
                            <div class="items-center border-0 p-3 flex">
                                <div class="flex-none items-center justify-center w-[60px] h-[60px] rounded-full border border-white fill-pelorous-600 p-2.5 bg-pelorous-600 transition hover:bg-pelorous-500">
                                    <span class="material-icons text-4xl text-white">location_on</span>
                                </div>
                                <div class="flex-grow ml-4 items-center">
                                    <h5>@lang('shop::app.components.layouts.footer.address1')</h5>
                                    <p>@lang('shop::app.components.layouts.footer.address2')</p>
                                </div>
                            </div>
                        </div>
                        <!-- Third card -->
                        <div class="items-center col-span-1 md:col-span-1">
                            <a href="mailto:@lang('shop::app.components.layouts.footer.mail')" class="card border-0 p-4 flex">
                                <div class="flex-none items-center justify-center w-[60px] h-[60px] rounded-full border border-white fill-pelorous-600 p-2.5 bg-pelorous-600 transition hover:bg-pelorous-500">
                                    <span class="material-icons text-4xl text-white">mail</span>
                                </div>

                                <div class="flex-grow ml-4 items-center">
                                    <h5>@lang('shop::app.components.layouts.footer.mail-label')</h5>
                                    <p>@lang('shop::app.components.layouts.footer.mail')</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.before') !!}

            <!-- News Letter subscription -->
            @if (core()->getConfigData('customer.settings.newsletter.subscription'))
                <div class="md:col-span-12 justify-center">
                    <p class="text-3xl leading-[45px] text-navyBlue font-medium mt-5 mb-5 text-center" role="heading" aria-level="2" style="font-family:roboto; ">
                        @lang('shop::app.components.layouts.footer.newsletter-text')
                    </p>
                </div>
            @endif

            {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.after') !!}

        </div>
        <div class="row">
            <div class="md:col-span-12 sm:justify-start">
                <div class="md:flex sm:flex-none justify-center mt-5 sm:mt-0">
                    <x-shop::form
                        :action="route('shop.subscription.store')"
                        class="flex-row items-start sm:items-center sm:justify-center sm:w-full md:w-96"
                    >
                        <div class="relative m-auto">
                            <x-shop::form.control-group.control
                                type="email"
                                class="w-full border-solid border-gray-100 bg-white px-5 text-xs font-medium"
                                name="email"
                                rules="required|email"
                                label="Email"
                                :aria-label="trans('shop::app.components.layouts.footer.email')"
                                placeholder="email@example.com"
                            />

                            <x-shop::form.control-group.error control-name="email" />

                            <button
                                type="submit"
                                class="absolute top-0 flex w-max items-center bg-pelorous-800 px-7 py-2.5 text-white bold font-medium ltr:right-0 rtl:left-0"
                            >
                                @lang('shop::app.components.layouts.footer.subscribe')
                            </button>
                        </div>
                    </x-shop::form>
                </div>
            </div>
        </div>
        <div class="row mt-8">
            <!-- Row 2 -->
            <div class="col-md-6 offset-md-3 text-center mt-5">
                @if ($customization?->options)
                    @foreach ($customization->options as $footerLinkSection)
                        <div class="flex flex-wrap justify-center divide-x divide-pelorous-700">
                            @php
                                usort($footerLinkSection, function ($a, $b) {
                                    return $a['sort_order'] - $b['sort_order'];
                                });
                            @endphp

                            @foreach ($footerLinkSection as $link)
                                <a class="my-2 py-0 px-3 text-pelorous-800 text-gray-900 hover:text-pelorous-600" href="{{ $link['url'] }}">{{ $link['title'] }}</a>
                            @endforeach
                        </div>
                    @endforeach
                    @endif
            </div>
        </div>
        <div class="row mt-8">
            <!-- Row 3 -->
            <div class="col-md-8 offset-md-2 text-center">
                <div class="flex justify-center">
                    <a href="#"
                       class="flex items-center justify-center rounded-full p-3 bg-blue-500 hover:bg-purple-500 transition-colors duration-300"
                       style="width: 40px; height: 40px;">
                        <i class="fab fa-facebook text-white text-xl"></i>
                    </a>
                    <a href="#"
                       class="flex items-center justify-center rounded-full p-3 bg-gradient-to-br from-purple-500 to-pink-500 hover:from-pink-500 hover:to-purple-500 transition-colors duration-300 ml-4"
                       style="width: 40px; height: 40px;">
                        <i class="fab fa-instagram text-white text-xl"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="row mt-8">
            <!-- Row 4 -->
            <div class="grid grid-cols-3 pb-4 mx-auto">
                <div class="text-center place-content-end md:text-left mb-4 md:mb-0">
                    {!! view_render_event('bagisto.shop.layout.footer.footer_text.before') !!}

                    <p class="text-sm text-gray-500 ml-5 mt-3">
                        @lang('shop::app.components.layouts.footer.footer-text', ['current_year'=> date('Y') ])
                    </p>

                    {!! view_render_event('bagisto.shop.layout.footer.footer_text.after') !!}
                </div>
                <div class="text-center mb-4 md:mb-0">
                    <img
                        src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                        width="320"
                        class="mx-auto block"
                        alt="{{ config('app.name') }}"
                    >
                </div>
                <div class="flex md:justify-end items-end space-x-4">
                    <img src="{{ bagisto_asset('images/pay/visa.webp') }}" class="w-12 h-10" alt="Visa">
                    <img src="{{ bagisto_asset('images/pay/mastercard.webp') }}" class="w-12 h-10" alt="Mastercard">
                </div>
            </div>
        </div>
    </div>
</footer>

{!! view_render_event('bagisto.shop.layout.footer.after') !!}

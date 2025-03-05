{!! view_render_event('bagisto.shop.layout.footer.before') !!}

<!--
    The category repository is injected directly here because there is no way
    to retrieve it from the view composer, as this is an anonymous component.
-->
@inject('themeCustomizationRepository', 'Webkul\Theme\Repositories\ThemeCustomizationRepository')

<!--
    This code needs to be refactored to reduce the amount of PHP in the Blade
    template as much as possible.
-->
@php
    $channel = core()->getCurrentChannel();

    $customization = $themeCustomizationRepository->findOneWhere([
        'type'       => 'footer_links',
        'status'     => 1,
        'theme_code' => $channel->theme,
        'channel_id' => $channel->id,
    ]);
@endphp
<footer class="mt-20 max-991:mt-10 bg-secondary max-sm:mt-10 text-white pt-[100px] pb-10 max-991:pt-10 max-991:pb-6">
    <div class="container max-lg:px-5">
        <div class="grid grid-cols-3 xl:flex gap-[40px] max-991:grid-cols-2 max-md:grid-cols-1">
            <div class="xl:w-[372px]">
                <div class="inner">
                    <p class="mb-7">
                        <a
                            href="{{ route('shop.home.index') }}"
                            aria-label="@lang('shop::app.components.layouts.header.bagisto')"
                        >
                            <img
                                src="{{ core()->getCurrentChannel()->logo_url ?? mix('assets/images/logo-white.svg') }}"
                                width="131"
                                height="29"
                                alt="{{ config('app.name') }}"
                            >
                        </a>
                    </p>
                    <p class="max-w-[270px]">
                        Cras varius ante vel euismod placerat. Sed mattis orci et interdum volutpat. Phasellus a nulla vel augue ullamcorper lacinia a ut metus.
                    </p>
                </div>
            </div>
            <div class="xl:w-[372px]">
                <h2 class="uppercase font-bold mb-7">Contact Us</h2>
                <div class="max-w-[310px]">
                    <p class="mb-5">
                        SH 06 building UDIC Westlake, Phú Thượng ward,
                        Tây Hồ district, Hà Nội, Việt Nam
                    </p>
                    <p class="mb-5">
                        Mail:<a href="mailto:hotline@cdi.ai.vn" class="underline">hotline@cdi.ai.vn</a>
                    </p>
                    <p class="mb-5">
                        Tel: <a href="tel:0878416668">087.841.6668</a>
                    </p>
                </div>
            </div>
            <div class="flex-grow xl:pl-5">
                <h2 class="uppercase font-bold mb-7">Quick Links</h2>
                @if ($customization?->options)
                    <div class="grid grid-cols-2 max-sm:grid-cols-1 gap-10 max-sm:gap-4">
                        @foreach ($customization->options as $footerLinkSection)
                            <ul class="grid gap-2 text-sm max-sm:gap-4">
                                @php
                                    usort($footerLinkSection, function ($a, $b) {
                                        return $a['sort_order'] - $b['sort_order'];
                                    });
                                @endphp

                                @foreach ($footerLinkSection as $link)
                                    <li>
                                        <a class="text-gray-200 hover:text-white" href="{{ $link['url'] }}">
                                            {{ $link['title'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-20 mb-12 border-t border-white opacity-[20%] max-991:mt-10 max-991:mb-6"></div>

        <div class="flex">
            <div class="copyright">
                @lang('shop::app.components.layouts.footer.footer-text', ['current_year'=> date('Y') ])
            </div>
        </div>
    </div>
</footer>

{{--<footer class="mt-9 bg-secondary max-sm:mt-10">--}}
{{--    <div class="container">--}}
{{--        <div class="flex pt-10 pb-10 justify-between gap-x-6 gap-y-8 max-1060:flex-col-reverse max-md:gap-5 max-md:p-8 max-sm:px-4 max-sm:py-5">--}}
{{--            <!-- For Desktop View -->--}}
{{--            <div class="flex flex-wrap items-start gap-24 max-1180:gap-6 max-1060:hidden">--}}
{{--                @if ($customization?->options)--}}
{{--                    @foreach ($customization->options as $footerLinkSection)--}}
{{--                        <ul class="grid gap-5 text-sm">--}}
{{--                            @php--}}
{{--                                usort($footerLinkSection, function ($a, $b) {--}}
{{--                                    return $a['sort_order'] - $b['sort_order'];--}}
{{--                                });--}}
{{--                            @endphp--}}

{{--                            @foreach ($footerLinkSection as $link)--}}
{{--                                <li>--}}
{{--                                    <a class="text-gray-200 hover:text-white" href="{{ $link['url'] }}">--}}
{{--                                        {{ $link['title'] }}--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                            @endforeach--}}
{{--                        </ul>--}}
{{--                    @endforeach--}}
{{--                @endif--}}
{{--            </div>--}}

{{--            <!-- For Mobile view -->--}}
{{--            <x-shop::accordion--}}
{{--                :is-active="false"--}}
{{--                class="hidden !w-full rounded-xl !border-2 !border-[#e9decc] max-1060:block max-sm:rounded-lg"--}}
{{--            >--}}
{{--                <x-slot:header class="rounded-t-lg bg-[#F1EADF] font-medium max-md:p-2.5 max-sm:px-3 max-sm:py-2 max-sm:text-sm">--}}
{{--                    @lang('shop::app.components.layouts.footer.footer-content')--}}
{{--                    </x-slot>--}}

{{--                    <x-slot:content class="flex justify-between !bg-transparent !p-4">--}}
{{--                        @if ($customization?->options)--}}
{{--                            @foreach ($customization->options as $footerLinkSection)--}}
{{--                                <ul class="grid gap-5 text-sm">--}}
{{--                                    @php--}}
{{--                                        usort($footerLinkSection, function ($a, $b) {--}}
{{--                                            return $a['sort_order'] - $b['sort_order'];--}}
{{--                                        });--}}
{{--                                    @endphp--}}

{{--                                    @foreach ($footerLinkSection as $link)--}}
{{--                                        <li>--}}
{{--                                            <a--}}
{{--                                                href="{{ $link['url'] }}"--}}
{{--                                                class="text-sm font-medium max-sm:text-xs">--}}
{{--                                                {{ $link['title'] }}--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
{{--                                    @endforeach--}}
{{--                                </ul>--}}
{{--                                @endforeach--}}
{{--                                @endif--}}
{{--                                </x-slot>--}}
{{--            </x-shop::accordion>--}}

{{--            {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.before') !!}--}}

{{--            <!-- News Letter subscription -->--}}
{{--            @if (core()->getConfigData('customer.settings.newsletter.subscription'))--}}
{{--                <div class="grid gap-2.5">--}}
{{--                    <p--}}
{{--                        class="max-w-[288px] text-3xl italic leading-[45px] text-navyBlue max-md:text-2xl max-sm:text-lg"--}}
{{--                        role="heading"--}}
{{--                        aria-level="2"--}}
{{--                    >--}}
{{--                        @lang('shop::app.components.layouts.footer.newsletter-text')--}}
{{--                    </p>--}}

{{--                    <p class="text-xs">--}}
{{--                        @lang('shop::app.components.layouts.footer.subscribe-stay-touch')--}}
{{--                    </p>--}}

{{--                    <div>--}}
{{--                        <x-shop::form--}}
{{--                            :action="route('shop.subscription.store')"--}}
{{--                            class="mt-2.5 rounded max-sm:mt-0"--}}
{{--                        >--}}
{{--                            <div class="relative w-full">--}}
{{--                                <x-shop::form.control-group.control--}}
{{--                                    type="email"--}}
{{--                                    class="block w-[420px] max-w-full rounded-xl border-2 border-[#e9decc] bg-[#F1EADF] px-5 py-4 text-base max-1060:w-full max-md:p-3.5 max-sm:mb-0 max-sm:rounded-lg max-sm:border-2 max-sm:p-2 max-sm:text-sm"--}}
{{--                                    name="email"--}}
{{--                                    rules="required|email"--}}
{{--                                    label="Email"--}}
{{--                                    :aria-label="trans('shop::app.components.layouts.footer.email')"--}}
{{--                                    placeholder="email@example.com"--}}
{{--                                />--}}

{{--                                <x-shop::form.control-group.error control-name="email"/>--}}

{{--                                <button--}}
{{--                                    type="submit"--}}
{{--                                    class="absolute top-1.5 flex w-max items-center rounded-xl bg-white px-7 py-2.5 font-medium hover:bg-zinc-100 max-md:top-1 max-md:px-5 max-md:text-xs max-sm:mt-0 max-sm:rounded-lg max-sm:px-4 max-sm:py-2 ltr:right-2 rtl:left-2"--}}
{{--                                >--}}
{{--                                    @lang('shop::app.components.layouts.footer.subscribe')--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        </x-shop::form>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endif--}}

{{--            {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.after') !!}--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="flex justify-between bg-[#F1EADF] px-[60px] py-3.5 max-md:justify-center max-sm:px-5">--}}
{{--        {!! view_render_event('bagisto.shop.layout.footer.footer_text.before') !!}--}}

{{--        <p class="text-sm text-zinc-600 max-md:text-center">--}}
{{--            @lang('shop::app.components.layouts.footer.footer-text', ['current_year'=> date('Y') ])--}}
{{--        </p>--}}

{{--        {!! view_render_event('bagisto.shop.layout.footer.footer_text.after') !!}--}}
{{--    </div>--}}
{{--</footer>--}}

{!! view_render_event('bagisto.shop.layout.footer.after') !!}

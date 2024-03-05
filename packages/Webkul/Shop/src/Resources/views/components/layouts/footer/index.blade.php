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
    $customization = $themeCustomizationRepository->findOneWhere([
        'type'       => 'footer_links',
        'status'     => 1,
        'channel_id' => core()->getCurrentChannel()->id,
    ]);
@endphp

<footer class="mt-9 bg-lightOrange max-sm:mt-8">
    <div class="flex gap-x-6 gap-y-8 justify-between p-[60px] max-1060:flex-wrap max-1060:flex-col-reverse max-sm:px-4">
        <div class="flex gap-24 items-start flex-wrap max-1180:gap-6 max-1060:justify-between">
            @if ($customization?->options)
                @foreach ($customization->options as $footerLinkSection)
                    <ul class="grid gap-5 text-sm">
                        @php
                            usort($footerLinkSection, function ($a, $b) {
                                return $a['sort_order'] - $b['sort_order'];
                            });
                        @endphp

                        @foreach ($footerLinkSection as $link)
                            <li>
                                <a href="{{ $link['url'] }}">
                                    {{ $link['title'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endforeach
            @endif
        </div>

        {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.before') !!}

        <!-- News Letter subscription -->
        @if (core()->getConfigData('customer.settings.newsletter.subscription'))
            <div class="grid gap-2.5">
                <p
                    class="max-w-[288px] leading-[45px] text-3xl italic text-navyBlue"
                    role="heading"
                    aria-level="2"
                >
                    @lang('shop::app.components.layouts.footer.newsletter-text')
                </p>

                <p class="text-xs">
                    @lang('shop::app.components.layouts.footer.subscribe-stay-touch')
                </p>

                <x-shop::form
                    :action="route('shop.subscription.store')"
                    class="mt-2.5 rounded max-sm:mt-8"
                >
                    <div class="relative w-full">
                        <x-shop::form.control-group.control
                            type="email"
                            class="blockw-[420px] max-w-full px-5 py-5 p-28 bg-[#F1EADF] border-[2px] border-[#E9DECC] rounded-xl text-xs font-medium max-1060:w-full"
                            name="email"
                            rules="required|email"
                            label="Email"
                            :aria-label="trans('shop::app.components.layouts.footer.email')"
                            placeholder="email@example.com"
                        />

                        <x-shop::form.control-group.error control-name="email" />

                        <button
                            type="submit"
                            class=" absolute flex items-center top-2 w-max px-7 py-3.5 bg-white rounded-xl text-xs font-medium rtl:left-2 ltr:right-2"
                        >
                            @lang('shop::app.components.layouts.footer.subscribe')
                        </button>
                    </div>
                </x-shop::form>
            </div>
        @endif

        {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.after') !!}
    </div>

    <div class="flex justify-between  px-[60px] py-3.5 bg-[#F1EADF]">
        {!! view_render_event('bagisto.shop.layout.footer.footer_text.before') !!}

        <p class="text-sm text-[#4D4D4D]">
            @lang('shop::app.components.layouts.footer.footer-text', ['current_year'=> date('Y') ])
        </p>

        {!! view_render_event('bagisto.shop.layout.footer.footer_text.after') !!}
    </div>
</footer>

{!! view_render_event('bagisto.shop.layout.footer.after') !!}

{!! view_render_event('bagisto.shop.layout.footer.before') !!}

{{--
    The category repository is injected directly here because there is no way
    to retrieve it from the view composer, as this is an anonymous component.
--}}
@inject('themeCustomizationRepository', 'Webkul\Shop\Repositories\ThemeCustomizationRepository')

{{--
    This code needs to be refactored to reduce the amount of PHP in the Blade
    template as much as possible.
--}}
@php
    $customization = $themeCustomizationRepository->findOneWhere([
        'type'       => 'footer_links',
        'status'     => 1,
        'channel_id' => core()->getCurrentChannel()->id,
    ]); 
@endphp

<footer class="mt-[36px] bg-lightOrange  max-sm:mt-[30px]">
    @if ($customization)
        <div class="flex gap-x-[25px] gap-y-[30px] justify-between p-[60px] max-1060:flex-wrap max-1060:flex-col-reverse max-sm:px-[15px]">
            <div class="flex gap-[85px] items-start flex-wrap max-1180:gap-[25px] max-1060:justify-between">
                @if ($customization->options)
                    @foreach ($customization->options as $footerLinkSection)
                        <ul class="grid gap-[20px] text-[14px]">
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
            
            {{-- News Letter subscription --}}
            @if(core()->getConfigData('customer.settings.newsletter.subscription'))
                <div class="grid gap-[10px]">
                    <p class="max-w-[288px] leading-[45px] text-[30px] italic text-navyBlue">
                        @lang('shop::app.components.layouts.footer.newsletter-text')
                    </p>

                    <p class="text-[12px]">
                        @lang('shop::app.components.layouts.footer.subscribe-stay-touch')
                    </p>

                    <x-shop::form
                        :action="route('shop.subscription.store')"
                        class="mt-[10px] rounded max-sm:mt-[30px]"
                    >
                        <label for="organic-search" class="sr-only">Search</label>

                        <div class="relative w-full">

                        <x-shop::form.control-group.control
                            type="email"
                            name="email"
                            class=" blockw-[420px] max-w-full px-[20px] py-[20px] pr-[110px] bg-[#F1EADF] border-[2px] border-[#E9DECC] rounded-[12px] text-xs font-medium max-1060:w-full"
                            rules="required|email"
                            label="Email"
                            placeholder="email@example.com"
                        >
                        </x-shop::form.control-group.control>

                        <x-shop::form.control-group.error
                            control-name="email"
                        >
                        </x-shop::form.control-group.error>

                            <button
                                type="submit"
                                class=" absolute flex items-center top-[8px] w-max px-[26px] py-[13px] bg-white rounded-[12px] text-[12px] font-medium rtl:left-[8px] ltr:right-[8px]"
                            >
                                @lang('shop::app.components.layouts.footer.subscribe')
                            </button>
                        </div>
                    </x-shop::form>
                </div>
            @endif
        </div>
    @endif

    <div class="flex justify-between  px-[60px] py-[13px] bg-[#F1EADF]">
        <p class="text-[14px] text-[#4D4D4D]">
            @lang('shop::app.components.layouts.footer.footer-text')
        </p>
    </div>
</footer>

{!! view_render_event('bagisto.shop.layout.footer.after') !!}

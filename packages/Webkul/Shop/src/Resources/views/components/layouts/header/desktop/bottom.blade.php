{{--
    The category repository is injected directly here because there is no way
    to retrieve it from the view composer, as this is an anonymous component.
--}}
@inject('categoryRepository', 'Webkul\Category\Repositories\CategoryRepository')

{{--
    This code needs to be refactored to reduce the amount of PHP in the Blade
    template as much as possible.
--}}
@php
    $categories = $categoryRepository->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id);

    $showCompare = (bool) core()->getConfigData('general.content.shop.compare_option');

    $showWishlist = (bool) core()->getConfigData('general.content.shop.wishlist_option');
@endphp

<div
    class="w-full flex justify-between min-h-[78px] px-[60px] border border-t-0 border-b-[1px] border-l-0 border-r-0 max-1180:px-[30px]"
>
    {{--
        This section will provide categories for the first, second, and third levels. If
        additional levels are required, users can customize them according to their needs.
    --}}
    {{-- Left Nagivation Section --}}
    <div class="flex items-center gap-x-[40px] pt-[28px] max-[1180px]:gap-x-[20px]">
        <a
            href="{{ route('shop.home.index') }}" 
            class="place-self-start -mt-[4px]"
        >
            <img src="{{ bagisto_asset('images/logo.svg') }}">
        </a>

        <div class="flex items-center">
            {{--
                For active category: `text-sm border-t-0 border-b-[2px] border-l-0 border-r-0 border-navyBlue`.
            --}}
            @foreach ($categories as $firstLevelCategory)
                <div class="relative group border-b-[4px] border-transparent hover:border-b-[4px] hover:border-navyBlue">
                    <span>
                        <a
                            href="{{ $firstLevelCategory->url }}"
                            class="inline-block pb-[21px] px-[20px] uppercase"
                        >
                            {{ $firstLevelCategory->name }}
                        </a>
                    </span>

                    @if ($firstLevelCategory->children->isNotEmpty())
                        <div
                            class="w-max absolute top-[49px] max-h-[580px] max-w-[1260px] p-[35px] z-[1] overflow-auto overflow-x-auto bg-white shadow-[0_6px_6px_1px_rgba(0,0,0,.3)] border border-b-0 border-l-0 border-r-0 border-t-[1px] border-[#F3F3F3] pointer-events-none opacity-0 transition duration-300 ease-out translate-y-1 group-hover:pointer-events-auto group-hover:opacity-100 group-hover:translate-y-0 group-hover:ease-in group-hover:duration-200 ltr:-left-[35px] rtl:-right-[35px]"
                        >
                            <div class="flex aigns gap-x-[70px] justify-between">
                                @foreach ($firstLevelCategory->children->chunk(2) as $pair)
                                    <div class="grid grid-cols-[1fr] gap-[20px] content-start w-full flex-auto min-w-max max-w-[150px]">
                                        @foreach ($pair as $secondLevelCategory)
                                            <p class="text-navyBlue font-medium">
                                                <a href="{{ $secondLevelCategory->url }}">
                                                    {{ $secondLevelCategory->name }}
                                                </a>
                                            </p>

                                            @if ($secondLevelCategory->children->isNotEmpty())
                                                <ul class="grid grid-cols-[1fr] gap-[12px]">
                                                    @foreach ($secondLevelCategory->children as $thirdLevelCategory)
                                                        <li class="text-[14px] font-medium text-[#7D7D7D]">
                                                            <a href="{{ $thirdLevelCategory->url }}">
                                                                {{ $thirdLevelCategory->name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Right Nagivation Section --}}
    <div class="flex gap-x-[35px] items-center max-lg:gap-x-[30px] max-[1100px]:gap-x-[25px]">
        {{-- Search Bar Container --}}
        <form 
            action="{{ route('shop.search.index') }}" 
            class="flex items-center max-w-[445px]"
        >
            <label
                for="organic-search"
                class="sr-only"
            >
                @lang('shop::app.components.layouts.header.search')
            </label>

            <div class="relative w-full">
                <div class="icon-search flex items-center  absolute ltr:left-[12px] rtl:right-[12px] top-[10px] text-[22px] pointer-events-none"></div>

                <input
                    type="text"
                    name="query"
                    value="{{ request('query') }}"
                    class="block w-full px-[44px] py-[13px] bg-[#F5F5F5] rounded-lg text-gray-900 text-xs font-medium transition-all border border-transparent hover:border-gray-400 focus:border-gray-400"
                    placeholder="Search for products"
                    required
                >

                <button
                    type="button"
                    class="icon-camera flex items-center absolute top-[10px] ltr:right-[12px] rtl:left-[12px] pr-3 text-[22px]"
                >
                </button>
            </div>
        </form>

        {{-- Right Navigation Links --}}
        <div class="flex gap-x-[35px] mt-[5px] max-lg:gap-x-[30px] max-[1100px]:gap-x-[25px]">
            {{-- Compare --}}
            <a href="{{ route('shop.compare.index') }}">
                <span class="icon-compare inline-block text-[24px] cursor-pointer"></span>
            </a>

            {{-- Mini cart --}}
            @include('shop::checkout.cart.mini-cart')

            {{-- user profile --}}
            <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
                <x-slot:toggle>
                    <span class="icon-users inline-block text-[24px] cursor-pointer"></span>
                </x-slot:toggle>

                {{-- Guest Dropdown --}}
                @guest('customer')
                    <x-slot:content>
                        <div class="grid gap-[10px]">
                            <p class="text-[20px] font-dmserif">
                                @lang('shop::app.components.layouts.header.welcome-guest')
                            </p>

                            <p class="text-[14px]">
                                @lang('shop::app.components.layouts.header.dropdown-text')
                            </p>
                        </div>

                        <p class="w-full mt-[12px] py-2px border border-[#E9E9E9]"></p>

                        <div class="flex gap-[16px] mt-[25px]">
                            <a
                                href="{{ route('shop.customer.session.create') }}"
                                class="bs-primary-button block w-max px-[29px] mx-auto m-0 ml-[0px] rounded-[18px] text-base text-center"
                            >
                                @lang('shop::app.components.layouts.header.sign-in')
                            </a>

                            <a
                                href="{{ route('shop.customers.register.index') }}"
                                class="bs-secondary-button block w-max m-0 ml-[0px] mx-auto px-[29px] border-2 rounded-[18px] text-base text-center"
                            >
                                @lang('shop::app.components.layouts.header.sign-up')
                            </a>
                        </div>
                    </x-slot:content>
                @endguest

                {{-- Customers Dropdown --}}
                @auth('customer')
                    <x-slot:content class="!p-[0px]">
                        <div class="grid gap-[10px] p-[20px] pb-0">
                            <p class="text-[20px] font-dmserif">
                                @lang('shop::app.components.layouts.header.welcome')’
                                {{ auth()->guard('customer')->user()->first_name }}
                            </p>

                            <p class="text-[14px]">
                                @lang('shop::app.components.layouts.header.dropdown-text')
                            </p>
                        </div>

                        <p class="w-full mt-[12px] py-2px border border-[#E9E9E9]"></p>

                        <div class="grid gap-[4px] mt-[10px] pb-[10px]">
                            <a
                                class="px-5 py-2 text-[16px] hover:bg-gray-100 cursor-pointer"
                                href="{{ route('shop.customers.account.profile.index') }}"
                            >
                                @lang('shop::app.components.layouts.header.profile')
                            </a>

                            <a
                                class="px-5 py-2 text-[16px] hover:bg-gray-100 cursor-pointer"
                                href="{{ route('shop.customers.account.orders.index') }}"
                            >
                                @lang('shop::app.components.layouts.header.orders')
                            </a>

                            @if ($showWishlist)
                                <a
                                    class="px-5 py-2 text-[16px] hover:bg-gray-100 cursor-pointer"
                                    href="{{ route('shop.customers.account.wishlist.index') }}"
                                >
                                    @lang('shop::app.components.layouts.header.wishlist')
                                </a>
                            @endif

                            {{--Customers logout--}}
                            @auth('customer')
                                <x-shop::form
                                    method="DELETE"
                                    action="{{ route('shop.customer.session.destroy') }}"
                                    id="customerLogout"
                                >
                                </x-shop::form>

                                <a
                                    class="px-5 py-2 text-[16px] hover:bg-gray-100 cursor-pointer"
                                    href="{{ route('shop.customer.session.destroy') }}"
                                    onclick="event.preventDefault(); document.getElementById('customerLogout').submit();"
                                >
                                    @lang('shop::app.components.layouts.header.logout')
                                </a>
                            @endauth
                        </div>
                    </x-slot:content>
                @endauth
            </x-shop::dropdown>
        </div>
    </div>
</div>

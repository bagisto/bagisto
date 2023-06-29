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
    class="w-full flex justify-between px-[60px] border border-t-0 border-b-[1px] border-l-0 border-r-0"
>
    {{--
        This section will provide categories for the first, second, and third levels. If
        additional levels are required, users can customize them according to their needs.
    --}}
    <div class="flex items-center gap-x-[40px] pt-[28px] max-[1180px]:gap-x-[35px]">
        <a
            href="{{ route('shop.home.index') }}" 
            class="bs-logo bg-[position:-5px_-3px] bs-main-sprite w-[131px] h-[29px] inline-block place-self-start -mt-[4px]"
        >
        </a>

        <div class="flex items-center gap-x-[45px]  max-[1180px]:gap-x-[30px]">
            {{--
                For active category: `text-sm border-t-0 border-b-[2px] border-l-0 border-r-0 border-navyBlue`.
            --}}
            @foreach ($categories as $firstLevelCategory)
                <div class="relative group border-b-[4px] border-transparent hover:border-b-[4px] hover:border-navyBlue">
                    <span>
                        <a
                            href="{{ $firstLevelCategory->url }}"
                            class="pb-[21px] px-[15px] inline-block"
                        >
                            {{ $firstLevelCategory->name }}
                        </a>
                    </span>

                    @if ($firstLevelCategory->children->isNotEmpty())
                        <div
                            class="hidden group-hover:block max-h-[580px] max-w-[1260px] overflow-auto overflow-x-auto -left-[35px] w-max absolute top-[49px] bg-white p-[35px] border border-b-0 border-l-0 border-r-0 border-t-[1px] border-[#F3F3F3] z-[1] shadow-[0_6px_6px_1px_rgba(0,0,0,.3)]"
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

    <div class="flex items-center gap-x-[35px] max-lg:gap-x-[30px] max-[1100px]:gap-x-[25px]">
        <form class="flex items-center max-w-[445px]">
            <label
                for="organic-search"
                class="sr-only"
            >
                @lang('shop::app.components.layouts.header.search')
            </label>

            <div class="relative w-full">
                <div
                    class="icon-search text-[22px] absolute left-[12px] top-[12px] flex items-center pointer-events-none">
                </div>

                <input
                    type="text"
                    class="bg-[#F5F5F5] rounded-lg block w-full px-11 py-3.5 text-gray-900 text-xs font-medium"
                    placeholder="Search for products"
                    required
                >

                <button
                    type="button"
                    class="icon-camera text-[22px] absolute top-[12px] right-[12px] flex items-center pr-3"
                >
                </button>
            </div>
        </form>

        <div class="flex gap-x-[35px] max-lg:gap-x-[30px] max-[1100px]:gap-x-[25px] mt-[5px]">
            <a href="{{ route('shop.compare.index') }}">
                <span class="icon-compare text-[24px] inline-block cursor-pointer"></span>
            </a>

            @include('shop::checkout.cart.mini-cart')

            <x-shop::dropdown position="bottom-right">
                <x-slot:toggle>
                    <span class="icon-users text-[24px] inline-block cursor-pointer"></span>
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

                        <p class="py-2px border border-[#E9E9E9] mt-[12px] w-full"></p>

                        <div class="flex gap-[16px] mt-[25px]">
                            <a
                                href="{{ route('shop.customer.session.create') }}"
                                class="m-0 ml-[0px] block mx-auto bg-navyBlue text-white text-base w-max font-medium py-[15px] px-[29px] rounded-[18px] text-center cursor-pointer"
                            >
                                @lang('shop::app.components.layouts.header.sign-in')
                            </a>

                            <a
                                href="{{ route('shop.customers.register.index') }}"
                                class="m-0 ml-[0px] block mx-auto bg-white border-2 border-navyBlue text-navyBlue text-base w-max font-medium py-[14px] px-[29px] rounded-[18px] text-center cursor-pointer"
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

                        <p class="py-2px border border-[#E9E9E9] mt-[12px] w-full"></p>

                        <div class="grid gap-[4px] mt-[10px] pb-[10px]">
                            <a
                                class="text-[16px] px-5 py-2 cursor-pointer hover:bg-gray-100"
                                href="{{ route('shop.customers.account.profile.index') }}"
                            >
                                @lang('shop::app.components.layouts.header.profile')
                            </a>

                            <a
                                class="text-[16px] px-5 py-2 cursor-pointer hover:bg-gray-100"
                                href="{{ route('shop.customers.account.orders.index') }}"
                            >
                                @lang('shop::app.components.layouts.header.orders')
                            </a>

                            @if ($showWishlist)
                                <a
                                    class="text-[16px] px-5 py-2 cursor-pointer hover:bg-gray-100"
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
                                    class="text-[16px] px-5 py-2 cursor-pointer hover:bg-gray-100"
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

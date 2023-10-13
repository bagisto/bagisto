{{--
    This code needs to be refactored to reduce the amount of PHP in the Blade
    template as much as possible.
--}}
@php
    $showCompare = (bool) core()->getConfigData('general.content.shop.compare_option');

    $showWishlist = (bool) core()->getConfigData('general.content.shop.wishlist_option');
@endphp

<div class="gap-[15px] flex-wrap px-[15px] pt-[25px] hidden max-lg:flex max-lg:mb-[15px]">
    <div class="w-full flex justify-between items-center">
        {{-- Left Navigation --}}
        <div class="flex items-center gap-x-[5px]">
            <x-shop::drawer
                position="left"
                width="80%"
            >
                <x-slot:toggle>
                    <span class="icon-hamburger text-[24px] cursor-pointer"></span>
                </x-slot:toggle>

                <x-slot:header>
                    <div class="flex justify-between items-center">
                        <a href="{{ route('shop.home.index') }}">
                            <img
                                src="{{ bagisto_asset('images/logo.svg') }}"
                                alt="Bagisto"
                                width="131"
                                height="29"
                            >
                        </a>
                    </div>
                </x-slot:header>

                <x-slot:content>
                    {{-- Account Profile Hero Section --}}
                    <div class="grid grid-cols-[auto_1fr] gap-[15px] items-center mb-[15px] p-[10px] border border-[#E9E9E9] rounded-[12px]">
                        <div class="">
                            <img
                                src="{{ auth()->user()?->image_url ??  bagisto_asset('images/user-placeholder.png') }}"
                                class="w-[60px] h-[60px] rounded-full"
                            >
                        </div>

                        @guest('customer')
                            <a
                                href="{{ route('shop.customer.session.create') }}"
                                class="flex text-[16px] font-medium"
                            >
                                @lang('Sign up or Login')

                                <i class="icon-double-arrow text-[24px] ml-[10px]"></i>
                            </a>
                        @endguest

                        @auth('customer')
                            <div class="flex flex-col gap-[10px] justify-between">
                                <p class="text-[25px] font-mediums">Hello! {{ auth()->user()?->first_name }}</p>

                                <p class="text-[#6E6E6E] ">{{ auth()->user()?->email }}</p>
                            </div>
                        @endauth
                    </div>

                    {{-- Mobile category view --}}
                    <v-mobile-category></v-mobile-category>

                    {{-- Localization & Currency Section --}}
                    <div class="absolute w-full flex bottom-0 left-0 bg-white shadow-lg p-4 gap-x-[20px] justify-between items-center mb-[15px]">
                        <x-shop::dropdown position="top-left">
                            <!-- Dropdown Toggler -->
                            <x-slot:toggle>
                                <div class="w-full flex gap-[10px] justify-between items-center cursor-pointer">
                                    <span>
                                        {{ core()->getCurrentCurrency()->symbol . ' ' . core()->getCurrentCurrencyCode() }}
                                    </span>

                                    <span class="icon-arrow-down text-[24px]"></span>
                                </div>
                            </x-slot:toggle>

                            <!-- Dropdown Content -->
                            <x-slot:content class="!p-[0px]">
                                <v-currency-switcher></v-currency-switcher>
                            </x-slot:content>
                        </x-shop::dropdown>

                        <x-shop::dropdown position="top-right">
                            <x-slot:toggle>
                                {{-- Dropdown Toggler --}}
                                <div class="w-full flex gap-[10px] justify-between items-center cursor-pointer">
                                    <img
                                        src="{{ ! empty(core()->getCurrentLocale()->logo_url)
                                                ? core()->getCurrentLocale()->logo_url
                                                : bagisto_asset('images/default-language.svg')
                                            }}"
                                        class="h-full"
                                        alt="Default locale"
                                        width="24"
                                        height="16"
                                    />

                                    <span>
                                        {{ core()->getCurrentChannel()->locales()->orderBy('name')->where('code', app()->getLocale())->value('name') }}
                                    </span>

                                    <span class="icon-arrow-down text-[24px]"></span>
                                </div>
                            </x-slot:toggle>

                            <!-- Dropdown Content -->
                            <x-slot:content class="!p-[0px]">
                                <v-locale-switcher></v-locale-switcher>
                            </x-slot:content>
                        </x-shop::dropdown>
                    </div>
                </x-slot:content>

                <x-slot:footer></x-slot:footer>
            </x-shop::drawer>

            <a
                href="{{ route('shop.home.index') }}"
                class="max-h-[30px]"
                aria-label="Bagisto"
            >
                <img
                    src="{{ bagisto_asset('images/logo.svg') }}"
                    alt="Bagisto"
                    width="131"
                    height="29"
                >
            </a>
        </div>

        {{-- Right Navigation --}}
        <div>
            <div class="flex  items-center gap-x-[20px]">
                @if($showCompare)
                    <a
                        href="{{ route('shop.compare.index') }}"
                        aria-label="Compare "
                    >
                        <span class="icon-compare text-[24px] cursor-pointer"></span>
                    </a>
                @endif

                @include('shop::checkout.cart.mini-cart')

                <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
                    <x-slot:toggle>
                        <span class="icon-users text-[24px] cursor-pointer"></span>
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
                                    class="block w-max mx-auto m-0 ml-[0px] py-[15px] px-[29px] bg-navyBlue rounded-[18px] text-white text-base font-medium text-center cursor-pointer"
                                >
                                    @lang('shop::app.components.layouts.header.sign-in')
                                </a>

                                <a
                                    href="{{ route('shop.customers.register.index') }}"
                                    class="block w-max mx-auto m-0 ml-[0px] py-[14px] px-[29px] bg-white border-2 border-navyBlue rounded-[18px] text-navyBlue text-base font-medium  text-center cursor-pointer"
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

    {{-- Serach Catalog Form --}}
    <form action="{{ route('shop.search.index') }}" class="flex items-center w-full">
        <label for="organic-search" class="sr-only">Search</label>

        <div class="relative w-full">
            <div
                class="icon-search flex items-center absolute left-[12px] top-[12px] text-[25px] pointer-events-none">
            </div>

            <input
                type="text"
                class="block w-full px-11 py-3.5 border border-['#E3E3E3'] rounded-xl text-gray-900 text-xs font-medium"
                name="query"
                value="{{ request('query') }}"
                placeholder="@lang('shop::app.components.layouts.header.search-text')"
                required
            >

            <button
                type="button"
                class="icon-camera flex items-center absolute top-[12px] right-[12px] pr-3 text-[22px]"
                aria-label="Search"
            >
            </button>
        </div>
    </form>
</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-mobile-category-template">
        <div>
            <template v-for="(category) in categories">
                <div class="flex justify-between items-center border border-b-[1px] border-l-0 border-r-0 border-t-0 border-[#f3f3f5]">
                    <a
                        :href="category.url"
                        class="flex items-center justify-between pb-[20px] mt-[20px]"
                        v-text="category.name"
                    >
                    </a>

                    <span
                        class="text-[24px] cursor-pointer"
                        :class="{'icon-arrow-down': category.isOpen, 'icon-arrow-right': ! category.isOpen}"
                        @click="toggle(category)"
                    >
                    </span>
                </div>

                <div
                    class="grid gap-[8px]"
                    v-if="category.isOpen"
                >
                    <ul v-if="category.children.length">
                        <li v-for="secondLevelCategory in category.children">
                            <div class="flex justify-between items-center ml-3 border border-b-[1px] border-l-0 border-r-0 border-t-0 border-[#f3f3f5]">
                                <a
                                    :href="secondLevelCategory.url"
                                    class="flex items-center justify-between pb-[20px] mt-[20px]"
                                    v-text="secondLevelCategory.name"
                                >
                                </a>

                                <span
                                    class="text-[24px] cursor-pointer"
                                    :class="{
                                        'icon-arrow-down': secondLevelCategory.category_show,
                                        'icon-arrow-right': ! secondLevelCategory.category_show
                                    }"
                                    @click="secondLevelCategory.category_show = ! secondLevelCategory.category_show"
                                >
                                </span>
                            </div>

                            <div v-if="secondLevelCategory.category_show">
                                <ul v-if="secondLevelCategory.children.length">
                                    <li v-for="thirdLevelCategory in secondLevelCategory.children">
                                        <div class="flex justify-between items-center ml-3 border border-b-[1px] border-l-0 border-r-0 border-t-0 border-[#f3f3f5]">
                                            <a
                                                :href="thirdLevelCategory.url"
                                                class="flex items-center justify-between mt-[20px] ml-3 pb-[20px]"
                                                v-text="thirdLevelCategory.name"
                                            >
                                            </a>
                                        </div>
                                    </li>
                                </ul>

                                <span
                                    class="ml-2"
                                    v-else
                                >
                                    @lang('No category found.')
                                </span>
                            </div>
                        </li>
                    </ul>

                    <span
                        class="ml-2"
                        v-else
                    >
                        @lang('No category found.')
                    </span>
                </div>
            </template>
        </div>
    </script>

    <script type="module">
        app.component('v-mobile-category', {
            template: '#v-mobile-category-template',

            data() {
                return  {
                    categories: [],
                }
            },

            mounted() {
                this.get();
            },

            methods: {
                get() {
                    this.$axios.get("{{ route('shop.api.categories.tree') }}")
                        .then(response => {
                            this.categories = response.data.data;
                        }).catch(error => {
                            console.log(error);
                        });
                },

                toggle(selectedCategory) {
                    this.categories = this.categories.map((category) => ({
                        ...category,
                        isOpen: category.id === selectedCategory.id ? ! category.isOpen : false,
                    }));
                },
            },
        });
    </script>
@endPushOnce

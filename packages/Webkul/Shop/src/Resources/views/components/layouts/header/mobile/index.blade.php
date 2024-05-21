<!--
    This code needs to be refactored to reduce the amount of PHP in the Blade
    template as much as possible.
-->
@php
    $showCompare = (bool) core()->getConfigData('general.content.shop.compare_option');

    $showWishlist = (bool) core()->getConfigData('general.content.shop.wishlist_option');
@endphp

<div class="hidden flex-wrap gap-4 px-4 pb-4 pt-6 max-lg:flex">
    <div class="flex w-full items-center justify-between">
        <!-- Left Navigation -->
        <div class="flex items-center gap-x-1.5">
            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.before') !!}
            
            <x-shop::drawer
                position="left"
                width="100%"
            >
                <x-slot:toggle>
                    <span class="icon-hamburger cursor-pointer text-2xl"></span>
                </x-slot>

                <x-slot:header>
                    <div class="flex items-center justify-between">
                        <a href="{{ route('shop.home.index') }}">
                            <img
                                src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                                alt="{{ config('app.name') }}"
                                width="131"
                                height="29"
                            >
                        </a>
                    </div>
                </x-slot>

                <x-slot:content>
                    <!-- Account Profile Hero Section -->
                    <div class="mb-4 grid grid-cols-[auto_1fr] items-center gap-4 rounded-xl border border-zinc-200 p-2.5 max-sm:mt-4">
                        <div>
                            <img
                                src="{{ auth()->user()?->image_url ??  bagisto_asset('images/user-placeholder.png') }}"
                                class="h-[60px] w-[60px] rounded-full max-sm:rounded-full"
                            >
                        </div>

                        @guest('customer')
                            <a
                                href="{{ route('shop.customer.session.create') }}"
                                class="flex text-base font-medium"
                            >
                                @lang('Sign up or Login')

                                <i class="icon-double-arrow text-2xl ltr:ml-2.5 rtl:mr-2.5"></i>
                            </a>
                        @endguest

                        @auth('customer')
                            <div class="flex flex-col justify-between gap-2.5">
                                <p class="font-mediums text-2xl max-sm:text-xl">Hello! {{ auth()->user()?->first_name }}</p>

                                <p class="text-zinc-500 max-sm:text-sm">{{ auth()->user()?->email }}</p>
                            </div>
                        @endauth
                    </div>

                    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.categories.before') !!}

                    <!-- Mobile category view -->
                    <v-mobile-category></v-mobile-category>

                    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.categories.after') !!}
                </x-slot>

                <x-slot:footer></x-slot>
            </x-shop::drawer>

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.after') !!}

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.logo.before') !!}

            <a
                href="{{ route('shop.home.index') }}"
                class="max-h-[30px]"
                aria-label="@lang('shop::app.components.layouts.header.bagisto')"
            >
                <img
                    src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                    alt="{{ config('app.name') }}"
                    width="131"
                    height="29"
                >
            </a>
            
            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.logo.after') !!}
        </div>

        <!-- Right Navigation -->
        <div>
            <div class="flex items-center gap-x-5 max-sm:gap-x-4">
                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.compare.before') !!}

                @if($showCompare)
                    <a
                        href="{{ route('shop.compare.index') }}"
                        aria-label="@lang('shop::app.components.layouts.header.compare')"
                    >
                        <span class="icon-compare cursor-pointer text-2xl"></span>
                    </a>
                @endif

                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.compare.after') !!}

                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.mini_cart.before') !!}

                @include('shop::checkout.cart.mini-cart')

                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.mini_cart.after') !!}

                <!-- Guest Dropdown -->
                @guest('customer')
                    <a
                        href="{{ route('shop.customer.session.create') }}"
                        aria-label="@lang('shop::app.components.layouts.header.account')"
                    >
                        <span class="icon-users cursor-pointer text-2xl"></span>
                    </a>
                @endguest

                <!-- Customers Dropdown -->
                @auth('customer')
                    <a
                        href="{{ route('shop.customers.account.index') }}"
                        aria-label="@lang('shop::app.components.layouts.header.account')"
                    >
                        <span class="icon-users cursor-pointer text-2xl"></span>
                    </a>
                @endauth
            </div>
        </div>
    </div>

    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.search.before') !!}

    <!-- Serach Catalog Form -->
    <form action="{{ route('shop.search.index') }}" class="flex w-full items-center">
        <label 
            for="organic-search" 
            class="sr-only"
        >
            @lang('shop::app.components.layouts.header.search')
        </label>

        <div class="relative w-full">
            <div class="icon-search pointer-events-none absolute top-3 flex items-center text-2xl max-sm:top-2 max-sm:text-xl ltr:left-3 rtl:right-3"></div>

            <input
                type="text"
                class="block w-full rounded-xl border border-['#E3E3E3'] px-11 py-3.5 text-xs font-medium text-gray-900 max-sm:rounded-lg max-sm:px-10 max-sm:py-2.5"
                name="query"
                value="{{ request('query') }}"
                placeholder="@lang('shop::app.components.layouts.header.search-text')"
                required
            >

            @if (core()->getConfigData('general.content.shop.image_search'))
                @include('shop::search.images.index')
            @endif
        </div>
    </form>

    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.search.after') !!}

</div>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-mobile-category-template"
    >
        <div>
            <template v-for="(category) in categories">
                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.category.before') !!}

                <div class="flex items-center justify-between border border-b border-l-0 border-r-0 border-t-0 border-zinc-100">
                    <a
                        :href="category.url"
                        class="mt-5 flex items-center justify-between"
                    >
                        @{{ category.name }}
                    </a>

                    <span
                        class="cursor-pointer text-2xl"
                        :class="{'icon-arrow-down': category.isOpen, 'icon-arrow-right': ! category.isOpen}"
                        @click="toggle(category)"
                    >
                    </span>
                </div>

                <div
                    class="grid gap-2"
                    v-if="category.isOpen"
                >
                    <ul v-if="category.children.length">
                        <li v-for="secondLevelCategory in category.children">
                            <div class="flex items-center justify-between border border-b border-l-0 border-r-0 border-t-0 border-zinc-100 ltr:ml-3 rtl:mr-3">
                                <a
                                    :href="secondLevelCategory.url"
                                    class="mt-5 flex items-center justify-between pb-5"
                                >
                                    @{{ secondLevelCategory.name }}
                                </a>

                                <span
                                    class="cursor-pointer text-2xl"
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
                                        <div class="flex items-center justify-between border border-b border-l-0 border-r-0 border-t-0 border-zinc-100 ltr:ml-3 rtl:mr-3">
                                            <a
                                                :href="thirdLevelCategory.url"
                                                class="mt-5 flex items-center justify-between pb-5 ltr:ml-3 rtl:mr-3"
                                            >
                                                @{{ thirdLevelCategory.Name }}
                                            </a>
                                        </div>
                                    </li>
                                </ul>

                                <span
                                    class="ltr:ml-2 rtl:mr-2"
                                    v-else
                                >
                                    @lang('shop::app.components.layouts.header.no-category-found')
                                </span>
                            </div>
                        </li>
                    </ul>

                    <span
                        class="mt-2 ltr:ml-2 rtl:mr-2"
                        v-else
                    >
                        @lang('shop::app.components.layouts.header.no-category-found')
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.category.after') !!}
            </template>
        </div>

        <!-- Localization & Currency Section -->
        <div class="w-full border-t bg-white">
            <div class="fixed bottom-0 z-10 grid w-full max-w-full grid-cols-[1fr_auto_1fr] items-center justify-items-center border-t border-zinc-200 bg-white px-5 ltr:left-0 rtl:right-0">
                <!-- Filter Drawer -->
                <x-shop::drawer
                    position="bottom"
                    width="100%"
                >
                    <!-- Drawer Toggler -->
                    <x-slot:toggle>
                        <div
                            class="flex cursor-pointer items-center gap-x-2.5 px-2.5 py-3.5 text-base font-medium uppercase max-sm:py-3"
                            role="button"
                        >
                            {{ core()->getCurrentCurrency()->symbol . ' ' . core()->getCurrentCurrencyCode() }}
                        </div>
                    </x-slot>

                    <!-- Drawer Header -->
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <p class="text-lg font-semibold">
                                @lang('Currencies')
                            </p>
                        </div>
                    </x-slot>

                    <!-- Drawer Content -->
                    <x-slot:content>
                        <v-currency-switcher></v-currency-switcher>
                    </x-slot>
                </x-shop::drawer>

                <!-- Seperator -->
                <span class="h-5 w-0.5 bg-zinc-200"></span>

                <!-- Sort Drawer -->
                <x-shop::drawer
                    position="bottom"
                    width="100%"
                >
                    <!-- Drawer Toggler -->
                    <x-slot:toggle>
                        <div
                            class="flex cursor-pointer items-center gap-x-2.5 px-2.5 py-3.5 text-base font-medium uppercase max-sm:py-3"
                            role="button"
                        >
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

                            {{ core()->getCurrentChannel()->locales()->orderBy('name')->where('code', app()->getLocale())->value('name') }}
                        </div>
                    </x-slot>

                    <!-- Drawer Header -->
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <p class="text-lg font-semibold">
                                @lang('Locales')
                            </p>
                        </div>
                    </x-slot>

                    <!-- Drawer Content -->
                    <x-slot:content>
                        <v-locale-switcher></v-locale-switcher>
                    </x-slot>
                </x-shop::drawer>
            </div>
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

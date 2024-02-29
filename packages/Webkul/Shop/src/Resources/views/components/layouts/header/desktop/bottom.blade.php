{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.before') !!}

<div class="w-full flex justify-between min-h-[78px] px-[60px] border border-t-0 border-b border-l-0 border-r-0 max-1180:px-8">
    <!--
        This section will provide categories for the first, second, and third levels. If
        additional levels are required, users can customize them according to their needs.
    -->
    <!-- Left Nagivation Section -->
    <div class="flex items-center gap-x-10 max-[1180px]:gap-x-5">
        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.logo.before') !!}

        <a
            href="{{ route('shop.home.index') }}"
            aria-label="@lang('shop::app.components.layouts.header.bagisto')"
        >
            <img
                src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                width="131"
                height="29"
                alt="{{ config('app.name') }}"
            >
        </a>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.logo.after') !!}

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.category.before') !!}

        <v-desktop-category>
            <div class="flex gap-5 items-center">
                <span
                    class="shimmer w-20 h-6 rounded"
                    role="presentation"
                ></span>
                <span
                    class="shimmer w-20 h-6 rounded"
                    role="presentation"
                ></span>
                <span
                    class="shimmer w-20 h-6 rounded"
                    role="presentation"
                ></span>
            </div>
        </v-desktop-category>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.category.after') !!}
    </div>

    <!-- Right Nagivation Section -->
    <div class="flex gap-x-9 items-center max-lg:gap-x-8 max-[1100px]:gap-x-6">

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.before') !!}

        <!-- Search Bar Container -->
        <div class="relative w-full">
            <form
                action="{{ route('shop.search.index') }}"
                class="flex items-center max-w-[445px]"
                role="search"
            >
                <label
                    for="organic-search"
                    class="sr-only"
                >
                    @lang('shop::app.components.layouts.header.search')
                </label>

                <div class="icon-search flex items-center  absolute ltr:left-3 rtl:right-3 top-2.5 text-xl pointer-events-none"></div>

                <input
                    type="text"
                    name="query"
                    value="{{ request('query') }}"
                    class="block w-full px-11 py-3 bg-[#F5F5F5] rounded-lg text-gray-900 text-xs font-medium transition-all border border-transparent hover:border-gray-400 focus:border-gray-400"
                    placeholder="@lang('shop::app.components.layouts.header.search-text')"
                    aria-label="@lang('shop::app.components.layouts.header.search-text')"
                    aria-required="true"
                    required
                >

                <button type="submit" class="hidden" aria-label="Submit"></button>

                @if (core()->getConfigData('general.content.shop.image_search'))
                    @include('shop::search.images.index')
                @endif
            </form>
        </div>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.after') !!}

        <!-- Right Navigation Links -->
        <div class="flex gap-x-8 mt-1.5 max-lg:gap-x-8 max-[1100px]:gap-x-6">

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.compare.before') !!}

            <!-- Compare -->
            @if(core()->getConfigData('general.content.shop.compare_option'))
                <a
                    href="{{ route('shop.compare.index') }}"
                    aria-label="@lang('shop::app.components.layouts.header.compare')"
                >
                    <span
                        class="icon-compare inline-block text-2xl cursor-pointer"
                        role="presentation"
                    ></span>
                </a>
            @endif

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.compare.after') !!}

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.mini_cart.before') !!}

            <!-- Mini cart -->
            @include('shop::checkout.cart.mini-cart')

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.mini_cart.after') !!}

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile.before') !!}

            <!-- user profile -->
            <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
                <x-slot:toggle>
                    <span
                        class="icon-users inline-block text-2xl cursor-pointer"
                        role="button"
                        aria-label="@lang('shop::app.components.layouts.header.profile')"
                        tabindex="0"
                    ></span>
                </x-slot>

                <!-- Guest Dropdown -->
                @guest('customer')
                    <x-slot:content>
                        <div class="grid gap-2.5">
                            <p class="text-xl font-dmserif">
                                @lang('shop::app.components.layouts.header.welcome-guest')
                            </p>

                            <p class="text-sm">
                                @lang('shop::app.components.layouts.header.dropdown-text')
                            </p>
                        </div>

                        <p class="w-full mt-3 py-2px border border-[#E9E9E9]"></p>

                        <div class="flex gap-4 mt-6">
                            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.sign_in_button.before') !!}

                            <a
                                href="{{ route('shop.customer.session.create') }}"
                                class="primary-button block w-max px-7 mx-auto m-0 ltr:ml-0 rtl:mr-0 rounded-2xl text-base text-center"
                            >
                                @lang('shop::app.components.layouts.header.sign-in')
                            </a>

                            <a
                                href="{{ route('shop.customers.register.index') }}"
                                class="secondary-button block w-max m-0 ltr:ml-0 rtl:mr-0 mx-auto px-7 border-2 rounded-2xl text-base text-center"
                            >
                                @lang('shop::app.components.layouts.header.sign-up')
                            </a>

                            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.sign_up__button.after') !!}
                        </div>
                    </x-slot>
                @endguest

                <!-- Customers Dropdown -->
                @auth('customer')
                    <x-slot:content class="!p-0">
                        <div class="grid gap-2.5 p-5 pb-0">
                            <p class="text-xl font-dmserif">
                                @lang('shop::app.components.layouts.header.welcome')’
                                {{ auth()->guard('customer')->user()->first_name }}
                            </p>

                            <p class="text-sm">
                                @lang('shop::app.components.layouts.header.dropdown-text')
                            </p>
                        </div>

                        <p class="w-full mt-3 py-2px border border-[#E9E9E9]"></p>

                        <div class="grid gap-1 mt-2.5 pb-2.5">
                            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile_dropdown.links.before') !!}

                            <a
                                class="px-5 py-2 text-base hover:bg-gray-100 cursor-pointer"
                                href="{{ route('shop.customers.account.profile.index') }}"
                            >
                                @lang('shop::app.components.layouts.header.profile')
                            </a>

                            <a
                                class="px-5 py-2 text-base hover:bg-gray-100 cursor-pointer"
                                href="{{ route('shop.customers.account.orders.index') }}"
                            >
                                @lang('shop::app.components.layouts.header.orders')
                            </a>

                            @if (core()->getConfigData('general.content.shop.wishlist_option'))
                                <a
                                    class="px-5 py-2 text-base hover:bg-gray-100 cursor-pointer"
                                    href="{{ route('shop.customers.account.wishlist.index') }}"
                                >
                                    @lang('shop::app.components.layouts.header.wishlist')
                                </a>
                            @endif

                            <!--Customers logout-->
                            @auth('customer')
                                <x-shop::form
                                    method="DELETE"
                                    action="{{ route('shop.customer.session.destroy') }}"
                                    id="customerLogout"
                                />

                                <a
                                    class="px-5 py-2 text-base hover:bg-gray-100 cursor-pointer"
                                    href="{{ route('shop.customer.session.destroy') }}"
                                    onclick="event.preventDefault(); document.getElementById('customerLogout').submit();"
                                >
                                    @lang('shop::app.components.layouts.header.logout')
                                </a>
                            @endauth

                            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile_dropdown.links.after') !!}
                        </div>
                    </x-slot>
                @endauth
            </x-shop::dropdown>

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile.after') !!}
        </div>
    </div>
</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-desktop-category-template">
        <div
            class="flex gap-5 items-center"
            v-if="isLoading"
        >
            <span
                class="shimmer w-20 h-6 rounded"
                role="presentation"
            ></span>
            <span
                class="shimmer w-20 h-6 rounded"
                role="presentation"
            ></span>
            <span
                class="shimmer w-20 h-6 rounded"
                role="presentation"
            ></span>
        </div>

        <div
            class="flex items-center"
            v-else
        >
            <div
                class="flex items-center relative h-[77px] group border-b-[4px] border-transparent hover:border-b-[4px] hover:border-navyBlue"
                v-for="category in categories"
            >
                <span>
                    <a
                        :href="category.url"
                        class="inline-block px-5 uppercase"
                        v-text="category.name"
                    >
                    </a>
                </span>

                <div
                    class="w-max absolute top-[78px] max-h-[580px] max-w-[1260px] p-9 z-[1] overflow-auto overflow-x-auto bg-white shadow-[0_6px_6px_1px_rgba(0,0,0,.3)] border border-b-0 border-l-0 border-r-0 border-t border-[#F3F3F3] pointer-events-none opacity-0 transition duration-300 ease-out translate-y-1 group-hover:pointer-events-auto group-hover:opacity-100 group-hover:translate-y-0 group-hover:ease-in group-hover:duration-200 ltr:-left-9 rtl:-right-9"
                    v-if="category.children.length"
                >
                    <div class="flex aigns gap-x-[70px] justify-between">
                        <div
                            class="grid grid-cols-[1fr] gap-5 content-start w-full flex-auto min-w-max max-w-[150px]"
                            v-for="pairCategoryChildren in pairCategoryChildren(category)"
                        >
                            <template v-for="secondLevelCategory in pairCategoryChildren">
                                <p class="text-navyBlue font-medium">
                                    <a
                                        :href="secondLevelCategory.url"
                                        v-text="secondLevelCategory.name"
                                    >
                                    </a>
                                </p>

                                <ul
                                    class="grid grid-cols-[1fr] gap-3"
                                    v-if="secondLevelCategory.children.length"
                                >
                                    <li
                                        class="text-sm font-medium text-[#6E6E6E]"
                                        v-for="thirdLevelCategory in secondLevelCategory.children"
                                    >
                                        <a
                                            :href="thirdLevelCategory.url"
                                            v-text="thirdLevelCategory.name"
                                        >
                                        </a>
                                    </li>
                                </ul>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-desktop-category', {
            template: '#v-desktop-category-template',

            data() {
                return  {
                    isLoading: true,

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
                            this.isLoading = false;

                            this.categories = response.data.data;
                        }).catch(error => {
                            console.log(error);
                        });
                },

                pairCategoryChildren(category) {
                    return category.children.reduce((result, value, index, array) => {
                        if (index % 2 === 0) {
                            result.push(array.slice(index, index + 2));
                        }

                        return result;
                    }, []);
                }
            },
        });
    </script>
@endPushOnce

{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.after') !!}

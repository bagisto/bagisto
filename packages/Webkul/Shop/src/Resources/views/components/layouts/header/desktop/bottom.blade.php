{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.before') !!}

<div class="flex min-h-[78px] w-full justify-between border border-b border-l-0 border-r-0 border-t-0 px-[60px] max-1180:px-8">
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
            <div class="flex items-center gap-5">
                <span
                    class="shimmer h-6 w-20 rounded"
                    role="presentation"
                ></span>

                <span
                    class="shimmer h-6 w-20 rounded"
                    role="presentation"
                ></span>

                <span
                    class="shimmer h-6 w-20 rounded"
                    role="presentation"
                ></span>
            </div>
        </v-desktop-category>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.category.after') !!}
    </div>

    <!-- Right Nagivation Section -->
    <div class="flex items-center gap-x-9 max-[1100px]:gap-x-6 max-lg:gap-x-8">

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.before') !!}

        <!-- Search Bar Container -->
        <div class="relative w-full">
            <form
                action="{{ route('shop.search.index') }}"
                class="flex max-w-[445px] items-center"
                role="search"
            >
                <label
                    for="organic-search"
                    class="sr-only"
                >
                    @lang('shop::app.components.layouts.header.search')
                </label>

                <div class="icon-search pointer-events-none absolute top-2.5 flex items-center text-xl ltr:left-3 rtl:right-3"></div>

                <input
                    type="text"
                    name="query"
                    value="{{ request('query') }}"
                    class="block w-full rounded-lg border border-transparent bg-zinc-100 px-11 py-3 text-xs font-medium text-gray-900 transition-all hover:border-gray-400 focus:border-gray-400"
                    minlength="{{ core()->getConfigData('catalog.products.search.min_query_length') }}"
                    maxlength="{{ core()->getConfigData('catalog.products.search.max_query_length') }}"
                    placeholder="@lang('shop::app.components.layouts.header.search-text')"
                    aria-label="@lang('shop::app.components.layouts.header.search-text')"
                    aria-required="true"
                    pattern="[^\\]+"
                    required
                >

                <button
                    type="submit"
                    class="hidden"
                    aria-label="@lang('shop::app.components.layouts.header.submit')"
                >
                </button>

                @if (core()->getConfigData('catalog.products.settings.image_search'))
                    @include('shop::search.images.index')
                @endif
            </form>
        </div>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.after') !!}

        <!-- Right Navigation Links -->
        <div class="mt-1.5 flex gap-x-8 max-[1100px]:gap-x-6 max-lg:gap-x-8">

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.compare.before') !!}

            <!-- Compare -->
            @if(core()->getConfigData('catalog.products.settings.compare_option'))
                <a
                    href="{{ route('shop.compare.index') }}"
                    aria-label="@lang('shop::app.components.layouts.header.compare')"
                >
                    <span
                        class="icon-compare inline-block cursor-pointer text-2xl"
                        role="presentation"
                    ></span>
                </a>
            @endif

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.compare.after') !!}

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.mini_cart.before') !!}

            <!-- Mini cart -->
            @if(core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                @include('shop::checkout.cart.mini-cart')
            @endif

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.mini_cart.after') !!}

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile.before') !!}

            <!-- user profile -->
            <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
                <x-slot:toggle>
                    <span
                        class="icon-users inline-block cursor-pointer text-2xl"
                        role="button"
                        aria-label="@lang('shop::app.components.layouts.header.profile')"
                        tabindex="0"
                    ></span>
                </x-slot>

                <!-- Guest Dropdown -->
                @guest('customer')
                    <x-slot:content>
                        <div class="grid gap-2.5">
                            <p class="font-dmserif text-xl">
                                @lang('shop::app.components.layouts.header.welcome-guest')
                            </p>

                            <p class="text-sm">
                                @lang('shop::app.components.layouts.header.dropdown-text')
                            </p>
                        </div>

                        <p class="mt-3 w-full border border-zinc-200"></p>

                        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.customers_action.before') !!}
                        
                        <div class="mt-6 flex gap-4">
                            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.sign_in_button.before') !!}

                            <a
                                href="{{ route('shop.customer.session.create') }}"
                                class="primary-button m-0 mx-auto block w-max rounded-2xl px-7 text-center text-base max-md:rounded-lg ltr:ml-0 rtl:mr-0"
                            >
                                @lang('shop::app.components.layouts.header.sign-in')
                            </a>

                            <a
                                href="{{ route('shop.customers.register.index') }}"
                                class="secondary-button m-0 mx-auto block w-max rounded-2xl border-2 px-7 text-center text-base max-md:rounded-lg max-md:py-3 ltr:ml-0 rtl:mr-0"
                            >
                                @lang('shop::app.components.layouts.header.sign-up')
                            </a>
                            
                            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.sign_up_button.after') !!}
                        </div>

                        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.customers_action.after') !!}
                    </x-slot>
                @endguest

                <!-- Customers Dropdown -->
                @auth('customer')
                    <x-slot:content class="!p-0">
                        <div class="grid gap-2.5 p-5 pb-0">
                            <p class="font-dmserif text-xl">
                                @lang('shop::app.components.layouts.header.welcome')’
                                {{ auth()->guard('customer')->user()->first_name }}
                            </p>

                            <p class="text-sm">
                                @lang('shop::app.components.layouts.header.dropdown-text')
                            </p>
                        </div>

                        <p class="mt-3 w-full border border-zinc-200"></p>

                        <div class="mt-2.5 grid gap-1 pb-2.5">
                            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile_dropdown.links.before') !!}

                            <a
                                class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                                href="{{ route('shop.customers.account.profile.index') }}"
                            >
                                @lang('shop::app.components.layouts.header.profile')
                            </a>

                            <a
                                class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
                                href="{{ route('shop.customers.account.orders.index') }}"
                            >
                                @lang('shop::app.components.layouts.header.orders')
                            </a>

                            @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                                <a
                                    class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
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
                                    class="cursor-pointer px-5 py-2 text-base hover:bg-gray-100"
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
    <script
        type="text/x-template"
        id="v-desktop-category-template"
    >
        <!-- Loading State -->
        <div
            class="flex items-center gap-5"
            v-if="isLoading"
        >
            <span
                class="shimmer h-6 w-20 rounded"
                role="presentation"
            ></span>

            <span
                class="shimmer h-6 w-20 rounded"
                role="presentation"
            ></span>

            <span
                class="shimmer h-6 w-20 rounded"
                role="presentation"
            ></span>
        </div>

        <!-- Categories Navigation -->
        <div
            class="flex items-center"
            v-else
        >
            <!-- "All" button for opening the category drawer -->
            <div 
                class="flex h-[77px] cursor-pointer items-center border-b-4 border-transparent hover:border-b-4 hover:border-navyBlue"
                @click="toggleAllCategoriesDrawer"
            >
                <span class="flex items-center px-5 uppercase">
                    <span class="icon-hamburger mr-2 text-2xl"></span> All
                </span>
            </div>
                
            <!-- Show only first 4 categories in main navigation -->
            <div
                class="group relative flex h-[77px] items-center border-b-4 border-transparent hover:border-b-4 hover:border-navyBlue"
                v-for="category in categories.slice(0, 4)"
            >
                <span>
                    <a
                        :href="category.url"
                        class="inline-block px-5 uppercase"
                    >
                        @{{ category.name }}
                    </a>
                </span>

                <!-- Dropdown for each category -->
                <div
                    class="pointer-events-none absolute top-[78px] z-[1] max-h-[580px] w-max max-w-[1260px] translate-y-1 overflow-auto overflow-x-auto border border-b-0 border-l-0 border-r-0 border-t border-[#F3F3F3] bg-white p-9 opacity-0 shadow-[0_6px_6px_1px_rgba(0,0,0,.3)] transition duration-300 ease-out group-hover:pointer-events-auto group-hover:translate-y-0 group-hover:opacity-100 group-hover:duration-200 group-hover:ease-in ltr:-left-9 rtl:-right-9"
                    v-if="category.children && category.children.length"
                >
                    <div class="flex justify-between gap-x-[70px]">
                        <div
                            class="grid w-full min-w-max max-w-[150px] flex-auto grid-cols-[1fr] content-start gap-5"
                            v-for="pairCategoryChildren in pairCategoryChildren(category)"
                        >
                            <template v-for="secondLevelCategory in pairCategoryChildren">
                                <p class="font-medium text-navyBlue">
                                    <a :href="secondLevelCategory.url">
                                        @{{ secondLevelCategory.name }}
                                    </a>
                                </p>

                                <ul
                                    class="grid grid-cols-[1fr] gap-3"
                                    v-if="secondLevelCategory.children && secondLevelCategory.children.length"
                                >
                                    <li
                                        class="text-sm font-medium text-zinc-500"
                                        v-for="thirdLevelCategory in secondLevelCategory.children"
                                    >
                                        <a :href="thirdLevelCategory.url">
                                            @{{ thirdLevelCategory.name }}
                                        </a>
                                    </li>
                                </ul>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Backdrop Overlay with Fade Transition -->
        <transition name="fade">
            <div 
                class="fixed inset-0 z-50 bg-black bg-opacity-50" 
                v-if="showAllCategoriesDrawer"
                @click="closeAllCategoriesDrawer"
            ></div>
        </transition>

        <!-- Single drawer with multiple views - Amazon style slide from left -->
        <transition name="slide-left">
            <div 
                class="fixed bottom-0 left-0 top-0 z-50 w-96 overflow-hidden bg-white shadow-lg"
                v-if="showAllCategoriesDrawer"
            >
                <!-- Main drawer wrapper with horizontal sliding panels -->
                <div class="drawer-inner-transition flex h-full" :style="{ transform: `translateX(${showThirdLevelDrawer ? '-100%' : '0'})` }">
                    <!-- First level panel -->
                    <div class="min-w-full flex-shrink-0">
                        <!-- Drawer Header -->
                        <div class="flex items-center justify-between border-b p-4">
                            <h2 class="text-xl font-bold">All Categories</h2>
                            <button 
                                @click="closeAllCategoriesDrawer" 
                                class="icon-cancel p-2 text-2xl focus:outline-none"
                                aria-label="Close menu"
                            >                            
                            </button>
                        </div>

                        <!-- Drawer Content - First Level Categories -->
                        <div class="h-full overflow-y-auto p-4">
                            <div 
                                v-for="category in categories" 
                                :key="category.id" 
                                class="mb-4"
                            >
                                <div class="flex cursor-pointer items-center justify-between rounded px-4 py-2 transition-colors duration-200 hover:bg-gray-100">
                                    <a :href="category.url" class="text-base font-medium text-black">
                                        @{{ category.name }}
                                    </a>
                                </div>

                                <!-- Second Level Categories -->
                                <div 
                                    v-if="category.children && category.children.length" 
                                    class="mt-2"
                                >
                                    <div 
                                        v-for="secondLevelCategory in category.children" 
                                        :key="secondLevelCategory.id" 
                                        class="mb-2"
                                    >
                                        <div 
                                            class="flex cursor-pointer items-center justify-between rounded px-4 py-2 transition-colors duration-200 hover:bg-gray-100"
                                            @click="toggleSecondLevelCategory(secondLevelCategory, category, $event)"
                                        >
                                            <a :href="secondLevelCategory.url" class="text-sm font-normal">
                                                @{{ secondLevelCategory.name }}
                                            </a>
                                            
                                            <span 
                                                v-if="secondLevelCategory.children && secondLevelCategory.children.length" 
                                                class="icon-arrow-right transform transition-transform duration-300"
                                                :class="{'rotate-90': !isAmazonStyleSecondLevel && expandedCategories.includes(secondLevelCategory.id)}"
                                            ></span>
                                        </div>

                                        <!-- Third Level Categories (Original Expandable Style) -->
                                        <div 
                                            v-if="!isAmazonStyleSecondLevel && secondLevelCategory.children && secondLevelCategory.children.length && expandedCategories.includes(secondLevelCategory.id)" 
                                            class="ml-4 mt-2"
                                        >
                                            <div 
                                                v-for="thirdLevelCategory in secondLevelCategory.children" 
                                                :key="thirdLevelCategory.id" 
                                                class="rounded px-4 py-2 transition-colors duration-200 hover:bg-gray-100"
                                            >
                                                <a :href="thirdLevelCategory.url" class="text-sm text-gray-600">
                                                    @{{ thirdLevelCategory.name }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Second level panel (third level categories) -->
                    <div class="min-w-full flex-shrink-0">
                        <!-- Drawer Header with Back Button -->
                        <div class="flex items-center justify-between border-b p-4">
                            <div class="flex items-center">
                                <button 
                                    @click="closeThirdLevelDrawer" 
                                    class="mr-3 flex items-center justify-center focus:outline-none"
                                    aria-label="Go back"
                                >
                                    <span class="icon-arrow-left text-lg"></span>
                                </button>
                                <div>
                                    <div class="text-xs text-gray-500">@{{ currentParentCategory?.name }}</div>
                                    <h2 class="text-xl font-bold">@{{ currentSecondLevelCategory?.name }}</h2>
                                </div>
                            </div>
                            <button 
                                @click="closeAllCategoriesDrawer" 
                                class="icon-cancel p-2 text-2xl focus:outline-none"
                                aria-label="Close menu"
                            >
                            </button>
                        </div>

                        <!-- Third Level Content -->
                        <div class="h-full overflow-y-auto p-4">
                            <div
                                v-for="thirdLevelCategory in currentSecondLevelCategory?.children" 
                                :key="thirdLevelCategory.id" 
                                class="mb-2"
                            >
                                <a 
                                    :href="thirdLevelCategory.url" 
                                    class="block rounded px-4 py-3 text-sm transition-colors duration-200 hover:bg-gray-100"
                                >
                                    @{{ thirdLevelCategory.name }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </script>

    <script type="module">
        app.component('v-desktop-category', {
            template: '#v-desktop-category-template',

            data() {
                return {
                    isLoading: true,
                    categories: [],
                    showAllCategoriesDrawer: false,
                    expandedCategories: [],
                    showThirdLevelDrawer: false,
                    currentSecondLevelCategory: null,
                    currentParentCategory: null,
                    isAmazonStyleSecondLevel: true,
                    drawerTransition: 'fade'
                }
            },

            mounted() {
                this.get();
                
                // Add transition styles to document head
                this.addTransitionStyles();
                
                // Close drawer when clicking escape key
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        if (this.showThirdLevelDrawer) {
                            this.closeThirdLevelDrawer();
                        } else if (this.showAllCategoriesDrawer) {
                            this.closeAllCategoriesDrawer();
                        }
                    }
                });
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

                addTransitionStyles() {
                    // Create style element for transitions
                    const style = document.createElement('style');
                    style.textContent = `
                        /* Fade transition for overlay */
                        .fade-enter-active, .fade-leave-active {
                            transition: opacity 0.3s ease;
                        }
                        .fade-enter-from, .fade-leave-to {
                            opacity: 0;
                        }
                        
                        /* Slide from left transition for main drawer */
                        .slide-left-enter-active, .slide-left-leave-active {
                            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                        }
                        .slide-left-enter-from, .slide-left-leave-to {
                            transform: translateX(-100%);
                        }
                        
                        /* Ensure smooth inner transitions between drawer views */
                        .drawer-inner-transition {
                            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                        }
                    `;
                    document.head.appendChild(style);
                },

                pairCategoryChildren(category) {
                    if (! category.children) return [];
                    
                    return category.children.reduce((result, value, index, array) => {
                        if (index % 2 === 0) {
                            result.push(array.slice(index, index + 2));
                        }

                        return result;
                    }, []);
                },
                
                toggleAllCategoriesDrawer() {
                    this.showAllCategoriesDrawer = !this.showAllCategoriesDrawer;
                    
                    // Prevent body scroll when drawer is open
                    if (this.showAllCategoriesDrawer) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = '';
                        // Reset third level drawer when main drawer is closed
                        this.showThirdLevelDrawer = false;
                    }
                },
                
                closeAllCategoriesDrawer() {
                    // First transition back to main panel if we're in a subcategory
                    if (this.showThirdLevelDrawer) {
                        this.showThirdLevelDrawer = false;
                        // Use setTimeout to allow inner transition to complete before closing the drawer
                        setTimeout(() => {
                            this.showAllCategoriesDrawer = false;
                            document.body.style.overflow = '';
                        }, 300);
                    } else {
                        this.showAllCategoriesDrawer = false;
                        document.body.style.overflow = '';
                    }
                },
                
                toggleSecondLevelCategory(secondLevelCategory, parentCategory, event) {
                    // If category has children and we're using Amazon style
                    if (secondLevelCategory.children && secondLevelCategory.children.length && this.isAmazonStyleSecondLevel) {
                        // Store current categories for the drawer
                        this.currentSecondLevelCategory = secondLevelCategory;
                        this.currentParentCategory = parentCategory;
                        
                        // Smooth transition to the next panel
                        this.showThirdLevelDrawer = true;
                        
                        // Prevent default link behavior
                        if (event) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        return;
                    }
                    
                    // Original expand/collapse behavior for non-Amazon style
                    if (!this.isAmazonStyleSecondLevel && secondLevelCategory.children && secondLevelCategory.children.length) {
                        if (this.expandedCategories.includes(secondLevelCategory.id)) {
                            this.expandedCategories = this.expandedCategories.filter(id => id !== secondLevelCategory.id);
                        } else {
                            this.expandedCategories.push(secondLevelCategory.id);
                        }
                        
                        // Prevent default link behavior
                        if (event) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                    }
                },
                
                closeThirdLevelDrawer() {
                    // Slide back to main menu with animation
                    this.showThirdLevelDrawer = false;
                }
            },
        });
    </script>
@endPushOnce
{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.after') !!}

@php
    $admin = auth()->guard('admin')->user();
@endphp

<header class="flex justify-between items-center px-4 py-2.5 bg-white dark:bg-gray-900  border-b dark:border-gray-800 sticky top-0 z-[10001]">
    <div class="flex gap-1.5 items-center">
        <!-- Hamburger Menu -->
        <i
            class="hidden icon-menu text-2xl p-1.5 rounded-md cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 max-lg:block"
            @click="$refs.sidebarMenuDrawer.open()"
        >
        </i>

        <!-- Logo -->
        <a href="{{ route('admin.dashboard.index') }}">
            @if ($logo = core()->getConfigData('general.design.admin_logo.logo_image'))
                <img
                    class="h-10"
                    src="{{ Storage::url($logo) }}"
                    alt="{{ config('app.name') }}"
                />
            @else
                <img
                    src="{{ request()->cookie('dark_mode') ? bagisto_asset('images/dark-logo.svg') : bagisto_asset('images/logo.svg') }}"
                    id="logo-image"
                    alt="{{ config('app.name') }}"
                />
            @endif
        </a>

        <!-- Mega Search Bar Vue Component -->
        <v-mega-search>
            <div class="flex items-center relative w-[525px] max-w-[525px] ltr:ml-2.5 rtl:mr-2.5 max-lg:w-[400px]">
                <i class="icon-search absolute flex items-center ltr:left-3 rtl:right-3 text-2xl top-1.5"></i>

                <input 
                    type="text" 
                    class="w-full px-10 py-1.5 block bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-lg leading-6 text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400"
                    placeholder="@lang('admin::app.components.layouts.header.mega-search.title')" 
                >
            </div>
        </v-mega-search>
    </div>

    <div class="flex gap-2.5 items-center">
        <!-- Dark mode Switcher -->
        <v-dark>
            <div class="flex">
                <span
                    class="{{ request()->cookie('dark_mode') ? 'icon-light' : 'icon-dark' }} p-1.5 rounded-md text-2xl cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                ></span>
            </div>
        </v-dark>

        <a 
            href="{{ route('shop.home.index') }}" 
            target="_blank"
            class="flex"
        >
            <span 
                class="icon-store p-1.5 rounded-md text-2xl cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                title="@lang('admin::app.components.layouts.header.visit-shop')"
            >
            </span>
        </a>

       <!-- Notification Component -->
        <v-notifications {{ $attributes }}>
            <span class="flex relative">
                <span 
                    class="icon-notification p-1.5 rounded-md text-2xl cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-950" 
                    title="@lang('admin::app.components.layouts.header.notifications')"
                >
                </span>
            </span>
        </v-notifications>

        <!-- Admin profile -->
        <x-admin::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
            <x-slot:toggle>
                @if ($admin->image)
                    <button class="flex w-9 h-9 overflow-hidden rounded-full cursor-pointer hover:opacity-80 focus:opacity-80">
                        <img
                            src="{{ $admin->image_url }}"
                            class="w-full"
                        />
                    </button>
                @else
                    <button class="flex justify-center items-center w-9 h-9 bg-blue-400 rounded-full text-sm text-white font-semibold cursor-pointer leading-6 transition-all hover:bg-blue-500 focus:bg-blue-500">
                        {{ substr($admin->name, 0, 1) }}
                    </button>
                @endif
            </x-slot>

            <!-- Admin Dropdown -->
            <x-slot:content class="!p-0">
                <div class="flex gap-1.5 items-center px-5 py-2.5 border border-b-gray-300 dark:border-gray-800">
                    <img
                        src="{{ url('cache/logo/bagisto.png') }}"
                        width="24"
                        height="24"
                    />

                    <!-- Version -->
                    <p class="text-gray-400">
                        @lang('admin::app.components.layouts.header.app-version', ['version' => 'v' . core()->version()])
                    </p>
                </div>

                <div class="grid gap-1 pb-2.5">
                    <a
                        class="px-5 py-2 text-base  text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-950 cursor-pointer"
                        href="{{ route('admin.account.edit') }}"
                    >
                        @lang('admin::app.components.layouts.header.my-account')
                    </a>

                    <!--Admin logout-->
                    <x-admin::form
                        method="DELETE"
                        action="{{ route('admin.session.destroy') }}"
                        id="adminLogout"
                    >
                    </x-admin::form>

                    <a
                        class="px-5 py-2 text-base  text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-950 cursor-pointer"
                        href="{{ route('admin.session.destroy') }}"
                        onclick="event.preventDefault(); document.getElementById('adminLogout').submit();"
                    >
                        @lang('admin::app.components.layouts.header.logout')
                    </a>
                </div>
            </x-slot>
        </x-admin::dropdown>
    </div>
</header>

<!-- Menu Sidebar Drawer -->
<x-admin::drawer
    position="left"
    width="270px"
    ref="sidebarMenuDrawer"
>
    <!-- Drawer Header -->
    <x-slot:header>
        <div class="flex justify-between items-center">
            @if ($logo = core()->getConfigData('general.design.admin_logo.logo_image'))
                <img
                    class="h-10"
                    src="{{ Storage::url($logo) }}"
                    alt="{{ config('app.name') }}"
                />
            @else
                <img
                    src="{{ request()->cookie('dark_mode') ? bagisto_asset('images/dark-logo.svg') : bagisto_asset('images/logo.svg') }}"
                    id="logo-image"
                    alt="{{ config('app.name') }}"
                />
            @endif
        </div>
    </x-slot>

    <!-- Drawer Content -->
    <x-slot:content class="p-4">
        <div class="h-[calc(100vh-100px)] overflow-auto journal-scroll">
            <nav class="grid gap-2 w-full">
                <!-- Navigation Menu -->
                @foreach ($menu->items as $menuItem)
                    <div class="relative group/item">
                        <a
                            href="{{ $menuItem['url'] }}"
                            class="flex gap-2.5 p-1.5 items-center cursor-pointer {{ $menu->getActive($menuItem) == 'active' ? 'bg-blue-600 rounded-lg' : ' hover:bg-gray-100 dark:hover:bg-gray-950 ' }} peer"
                        >
                            <span class="{{ $menuItem['icon'] }} text-2xl {{ $menu->getActive($menuItem) ? 'text-white' : ''}}"></span>
                            
                            <p class="text-gray-600 dark:text-gray-300 font-semibold whitespace-nowrap {{ $menu->getActive($menuItem) ? 'text-white' : ''}}">
                                @lang($menuItem['name'])
                            </p>
                        </a>

                        @if (count($menuItem['children']))
                            <div class="{{ $menu->getActive($menuItem) ? ' !grid bg-gray-100 dark:bg-gray-950' : '' }} hidden min-w-[180px] ltr:pl-10 rtl:pr-10 pb-2 rounded-b-lg z-[100]">
                                @foreach ($menuItem['children'] as $subMenuItem)
                                    <a
                                        href="{{ $subMenuItem['url'] }}"
                                        class="text-sm text-{{ $menu->getActive($subMenuItem) ? 'blue':'gray' }}-600 dark:text-{{ $menu->getActive($subMenuItem) ? 'blue':'gray' }}-300 whitespace-nowrap py-1 dark:hover:bg-gray-950"
                                    >
                                        @lang($subMenuItem['name'])
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </nav>
        </div>
    </x-slot>
</x-admin::drawer>

@pushOnce('scripts')
    <script type="text/x-template" id="v-mega-search-template">
        <div class="flex items-center relative w-[525px] max-w-[525px] ltr:ml-2.5 rtl:mr-2.5 max-lg:w-[400px]">
            <i class="icon-search text-2xl flex items-center absolute ltr:left-3 rtl:right-3 top-1.5"></i>

            <input 
                type="text"
                class="bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-lg block w-full px-10 py-1.5 leading-6 text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 peer"
                :class="{'border-gray-400': isDropdownOpen}"
                placeholder="@lang('admin::app.components.layouts.header.mega-search.title')"
                v-model.lazy="searchTerm"
                @click="searchTerm.length >= 2 ? isDropdownOpen = true : {}"
                v-debounce="500"
            >

            <div
                class="absolute top-10 w-full bg-white dark:bg-gray-900 shadow-[0px_0px_0px_0px_rgba(0,0,0,0.10),0px_1px_3px_0px_rgba(0,0,0,0.10),0px_5px_5px_0px_rgba(0,0,0,0.09),0px_12px_7px_0px_rgba(0,0,0,0.05),0px_22px_9px_0px_rgba(0,0,0,0.01),0px_34px_9px_0px_rgba(0,0,0,0.00)] border dark:border-gray-800 rounded-lg z-10"
                v-if="isDropdownOpen"
            >
                <!-- Search Tabs -->
                <div class="flex border-b dark:border-gray-800 text-sm text-gray-600 dark:text-gray-300">
                    <div
                        class="p-4 hover:bg-gray-100 dark:hover:bg-gray-950 cursor-pointer"
                        :class="{ 'border-b-2 border-blue-600': activeTab == tab.key }"
                        v-for="tab in tabs"
                        @click="activeTab = tab.key; search();"
                    >
                        @{{ tab.title }}
                    </div>
                </div>

                <!-- Searched Results -->
                <template v-if="activeTab == 'products'">
                    <template v-if="isLoading">
                        <x-admin::shimmer.header.mega-search.products />
                    </template>

                    <template v-else>
                        <div class="grid max-h-[400px] overflow-y-auto">
                            <a
                                :href="'{{ route('admin.catalog.products.edit', ':id') }}'.replace(':id', product.id)"
                                class="flex gap-2.5 justify-between p-4 border-b border-slate-300 dark:border-gray-800 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 last:border-b-0"
                                v-for="product in searchedResults.products.data"
                            >
                                <!-- Left Information -->
                                <div class="flex gap-2.5">
                                    <!-- Image -->
                                    <div
                                        class="w-full h-[60px] max-w-[60px] max-h-[60px] relative rounded overflow-hidden"
                                        :class="{'border border-dashed border-gray-300 dark:border-gray-800 rounded dark:invert dark:mix-blend-exclusion overflow-hidden': ! product.images.length}"
                                    >
                                        <template v-if="! product.images.length">
                                            <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}">
                                        
                                            <p class="w-full absolute bottom-1.5 text-[6px] text-gray-400 text-center font-semibold">
                                                @lang('admin::app.catalog.products.edit.types.grouped.image-placeholder')
                                            </p>
                                        </template>

                                        <template v-else>
                                            <img :src="product.images[0].url">
                                        </template>
                                    </div>

                                    <!-- Details -->
                                    <div class="grid gap-1.5 place-content-start">
                                        <p
                                            class="text-base  text-gray-600 dark:text-gray-300 font-semibold"
                                            v-text="product.name"
                                        >
                                        </p>

                                        <p class="text-gray-500">
                                            @{{ "@lang('admin::app.components.layouts.header.mega-search.sku')".replace(':sku', product.sku) }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Right Information -->
                                <div class="grid gap-1 place-content-center text-right">
                                    <p
                                        class="text-gray-600 dark:text-gray-300 font-semibold"
                                        v-text="product.formatted_price"
                                    >
                                    </p>
                                </div>
                            </a>
                        </div>

                        <div class="flex p-3 border-t dark:border-gray-800">
                            <a
                                :href="'{{ route('admin.catalog.products.index') }}?search=:query'.replace(':query', searchTerm)"
                                class="text-xs text-blue-600 font-semibold cursor-pointer transition-all hover:underline"
                                v-if="searchedResults.products.data.length"
                            >
                                @{{ "@lang('admin::app.components.layouts.header.mega-search.explore-all-matching-products')".replace(':query', searchTerm).replace(':count', searchedResults.products.total) }}
                            </a>

                            <a
                                href="{{ route('admin.catalog.products.index') }}"
                                class="text-xs text-blue-600 font-semibold cursor-pointer transition-all hover:underline"
                                v-else
                            >
                                @lang('admin::app.components.layouts.header.mega-search.explore-all-products')
                            </a>
                        </div>
                    </template>
                </template>

                <template v-if="activeTab == 'orders'">
                    <template v-if="isLoading">
                        <x-admin::shimmer.header.mega-search.orders />
                    </template>

                    <template v-else>
                        <div class="grid max-h-[400px] overflow-y-auto">
                            <a
                                :href="'{{ route('admin.sales.orders.view', ':id') }}'.replace(':id', order.id)"
                                class="grid gap-1.5 place-content-start p-4 border-b border-slate-300 dark:border-gray-800 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 last:border-b-0"
                                v-for="order in searchedResults.orders.data"
                            >
                                <p class="text-base text-gray-600 dark:text-gray-300 font-semibold">
                                    #@{{ order.increment_id }}
                                </p>

                                <p class="text-gray-500 dark:text-gray-300">
                                    @{{ order.formatted_created_at + ', ' + order.status_label + ', ' + order.customer_full_name }}
                                </p>
                            </a>
                        </div>

                        <div class="flex p-3 border-t dark:border-gray-800">
                            <a
                                :href="'{{ route('admin.sales.orders.index') }}?search=:query'.replace(':query', searchTerm)"
                                class="text-xs text-blue-600 font-semibold cursor-pointer transition-all hover:underline"
                                v-if="searchedResults.orders.data.length"
                            >
                                @{{ "@lang('admin::app.components.layouts.header.mega-search.explore-all-matching-orders')".replace(':query', searchTerm).replace(':count', searchedResults.orders.total) }}
                            </a>

                            <a
                                href="{{ route('admin.sales.orders.index') }}"
                                class="text-xs text-blue-600 font-semibold cursor-pointer transition-all hover:underline"
                                v-else
                            >
                                @lang('admin::app.components.layouts.header.mega-search.explore-all-orders')
                            </a>
                        </div>
                    </template>
                </template>

                <template v-if="activeTab == 'categories'">
                    <template v-if="isLoading">
                        <x-admin::shimmer.header.mega-search.categories />
                    </template>

                    <template v-else>
                        <div class="grid max-h-[400px] overflow-y-auto">
                            <a
                                :href="'{{ route('admin.catalog.categories.edit', ':id') }}'.replace(':id', category.id)"
                                class="p-4 border-b dark:border-gray-800 text-sm text-gray-600 dark:text-gray-300 font-semibold cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 last:border-b-0"
                                v-for="category in searchedResults.categories.data"
                            >
                                @{{ category.name }}
                            </a>
                        </div>

                        <div class="flex p-3 border-t dark:border-gray-800">
                            <a
                                :href="'{{ route('admin.catalog.categories.index') }}?search=:query'.replace(':query', searchTerm)"
                                class="text-xs text-blue-600 font-semibold cursor-pointer transition-all hover:underline"
                                v-if="searchedResults.categories.data.length"
                            >
                                @{{ "@lang('admin::app.components.layouts.header.mega-search.explore-all-matching-categories')".replace(':query', searchTerm).replace(':count', searchedResults.categories.total) }}
                            </a>

                            <a
                                href="{{ route('admin.catalog.categories.index') }}"
                                class="text-xs text-blue-600 font-semibold cursor-pointer transition-all hover:underline"
                                v-else
                            >
                                @lang('admin::app.components.layouts.header.mega-search.explore-all-categories')
                            </a>
                        </div>
                    </template>
                </template>

                <template v-if="activeTab == 'customers'">
                    <template v-if="isLoading">
                        <x-admin::shimmer.header.mega-search.customers />
                    </template>

                    <template v-else>
                        <div class="grid max-h-[400px] overflow-y-auto">
                            <a
                                :href="'{{ route('admin.customers.customers.view', ':id') }}'.replace(':id', customer.id)"
                                class="grid gap-1.5 place-content-start p-4 border-b border-slate-300 dark:border-gray-800 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 last:border-b-0"
                                v-for="customer in searchedResults.customers.data"
                            >
                                <p class="text-base text-gray-600 dark:text-gray-300 font-semibold">
                                    @{{ customer.first_name + ' ' + customer.last_name }}
                                </p>

                                <p class="text-gray-500">
                                    @{{ customer.email }}
                                </p>
                            </a>
                        </div>

                        <div class="flex p-3 border-t dark:border-gray-800">
                            <a
                                :href="'{{ route('admin.customers.customers.index') }}?search=:query'.replace(':query', searchTerm)"
                                class="text-xs text-blue-600 font-semibold cursor-pointer transition-all hover:underline"
                                v-if="searchedResults.customers.data.length"
                            >
                                @{{ "@lang('admin::app.components.layouts.header.mega-search.explore-all-matching-customers')".replace(':query', searchTerm).replace(':count', searchedResults.customers.total) }}
                            </a>

                            <a
                                href="{{ route('admin.customers.customers.index') }}"
                                class="text-xs text-blue-600 font-semibold cursor-pointer transition-all hover:underline"
                                v-else
                            >
                                @lang('admin::app.components.layouts.header.mega-search.explore-all-customers')
                            </a>
                        </div>
                    </template>
                </template>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-mega-search', {
            template: '#v-mega-search-template',

            data() {
                return {
                    activeTab: 'products',

                    isDropdownOpen: false,

                    tabs: {
                        products: {
                            key: 'products',
                            title: "@lang('admin::app.components.layouts.header.mega-search.products')",
                            is_active: true,
                            endpoint: "{{ route('admin.catalog.products.search') }}"
                        },
                        
                        orders: {
                            key: 'orders',
                            title: "@lang('admin::app.components.layouts.header.mega-search.orders')",
                            endpoint: "{{ route('admin.sales.orders.search') }}"
                        },
                        
                        categories: {
                            key: 'categories',
                            title: "@lang('admin::app.components.layouts.header.mega-search.categories')",
                            endpoint: "{{ route('admin.catalog.categories.search') }}"
                        },
                        
                        customers: {
                            key: 'customers',
                            title: "@lang('admin::app.components.layouts.header.mega-search.customers')",
                            endpoint: "{{ route('admin.customers.customers.search') }}"
                        }
                    },

                    isLoading: false,

                    searchTerm: '',

                    searchedResults: {
                        products: [],
                        orders: [],
                        categories: [],
                        customers: []
                    },
                }
            },

            watch: {
                searchTerm: function(newVal, oldVal) {
                    this.search()
                }
            },

            created() {
                window.addEventListener('click', this.handleFocusOut);
            },

            beforeDestroy() {
                window.removeEventListener('click', this.handleFocusOut);
            },

            methods: {
                search() {
                    if (this.searchTerm.length <= 1) {
                        this.searchedResults[this.activeTab] = [];

                        this.isDropdownOpen = false;

                        return;
                    }

                    this.isDropdownOpen = true;

                    let self = this;

                    this.isLoading = true;
                    
                    this.$axios.get(this.tabs[this.activeTab].endpoint, {
                            params: {query: this.searchTerm}
                        })
                        .then(function(response) {
                            self.searchedResults[self.activeTab] = response.data;

                            self.isLoading = false;
                        })
                        .catch(function (error) {
                        })
                },

                handleFocusOut(e) {
                    if (! this.$el.contains(e.target)) {
                        this.isDropdownOpen = false;
                    }
                },
            }
        });
    </script>

    <script type="text/x-template" id="v-notifications-template">
        <x-admin::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
            <!-- Notification Toggle -->
            <x-slot:toggle>
                <span class="flex relative">
                    <span
                        class="icon-notification p-1.5 rounded-md text-2xl text-red cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-950" 
                        title="@lang('admin::app.components.layouts.header.notifications')"
                    >
                    </span>
                
                    <span
                        class="flex justify-center items-center min-w-5 h-5 absolute -top-2 p-1.5 ltr:left-5 rtl:right-5 bg-blue-600 rounded-full text-white text-[10px] font-semibold leading-[9px] cursor-pointer"
                        v-text="totalUnRead"
                        v-if="totalUnRead"
                    >
                    </span>
                </span>
            </x-slot>

            <!-- Notification Content -->
            <x-slot:content class="!p-0 min-w-[250px] max-w-[250px]">
                <!-- Header -->
                <div class="text-base  p-3 text-gray-600 dark:text-gray-300 font-semibold border-b dark:border-gray-800">
                    @lang('admin::app.notifications.title', ['read' => 0])
                </div>

                <!-- Content -->
                <div class="grid">
                    <a
                        class="flex gap-1.5 items-start p-3 hover:bg-gray-50 dark:hover:bg-gray-950 border-b dark:border-gray-800 last:border-b-0"
                        v-for="notification in notifications"
                        :href="'{{ route('admin.notification.viewed_notification', ':orderId') }}'.replace(':orderId', notification.order_id)"
                    >
                        <!-- Notification Icon -->
                        <span
                            v-if="notification.order.status in notificationStatusIcon"
                            class="h-fit"
                            :class="notificationStatusIcon[notification.order.status]"
                        >
                        </span>

                        <div class="grid">
                            <!-- Order Id & Status -->
                            <p class="text-gray-800 dark:text-white">
                                #@{{ notification.order.id }}
                                @{{ orderTypeMessages[notification.order.status] }}
                            </p>

                            <!-- Created Date In humand Readable Format -->
                            <p class="text-xs text-gray-600 dark:text-gray-300">
                                @{{ notification.order.datetime }}
                            </p>
                        </div>
                    </a>
                </div>

                <!-- Footer -->
                <div class="flex gap-1.5 justify-between h-[47px] py-4 px-6 border-t dark:border-gray-800">
                    <a
                        href="{{ route('admin.notification.index') }}"
                        class="text-xs text-blue-600 font-semibold cursor-pointer transition-all hover:underline"
                    >
                        @lang('admin::app.notifications.view-all')
                    </a>

                    <a
                        class="text-xs text-blue-600 font-semibold cursor-pointer transition-all hover:underline"
                        v-if="notifications?.length"
                        @click="readAll()"
                    >
                        @lang('admin::app.notifications.read-all')
                    </a>
                </div>
            </x-slot>
        </x-admin::dropdown>
    </script>

    <script type="module">
        app.component('v-notifications', {
            template: '#v-notifications-template',

                props: [
                    'getReadAllUrl',
                    'readAllTitle',
                ],

                data() {
                    return {
                        notifications: [],

                        ordertype: {
                            pending: {
                                icon: 'icon-information',
                                message: "@lang('admin::app.notifications.order-status-messages.pending-payment')"
                            },

                            processing: {
                                icon: 'icon-processing',
                                message: "@lang('admin::app.notifications.order-status-messages.processing')",
                            },

                            canceled: {
                                icon: 'icon-cancel-1',
                                message: "@lang('admin::app.notifications.order-status-messages.canceled')"
                            },

                            completed: {
                                icon: 'icon-done',
                                message: "@lang('admin::app.notifications.order-status-messages.completed')"
                            },

                            closed: {
                                icon: 'icon-cancel-1',
                                message: "@lang('admin::app.notifications.order-status-messages.closed')"
                            },

                            pending_payment: {
                                icon: "icon-information",
                                message: "@lang('admin::app.notifications.order-status-messages.pending-payment')"
                            },
                        },

                        totalUnRead: 0,

                        orderTypeMessages: {
                        {{ \Webkul\Sales\Models\Order::STATUS_PENDING }}: "@lang('admin::app.notifications.order-status-messages.pending')",
                        {{ \Webkul\Sales\Models\Order::STATUS_CANCELED }}: "@lang('admin::app.notifications.order-status-messages.canceled')",
                        {{ \Webkul\Sales\Models\Order::STATUS_CLOSED }}: "@lang('admin::app.notifications.order-status-messages.closed')",
                        {{ \Webkul\Sales\Models\Order::STATUS_COMPLETED }}: "@lang('admin::app.notifications.order-status-messages.completed')",
                        {{ \Webkul\Sales\Models\Order::STATUS_PROCESSING }}: "@lang('admin::app.notifications.order-status-messages.processing')",
                        {{ \Webkul\Sales\Models\Order::STATUS_PENDING_PAYMENT }}: "@lang('admin::app.notifications.order-status-messages.pending-payment')",
                        }
                    }
                },

                computed: {
                    notificationStatusIcon() {
                        return {
                            pending: 'icon-information text-2xl text-amber-600 bg-amber-100 rounded-full',
                            closed: 'icon-repeat text-2xl text-red-600 bg-red-100 rounded-full',
                            completed: 'icon-done text-2xl text-blue-600 bg-blue-100 rounded-full',
                            canceled: 'icon-cancel-1 text-2xl text-red-600 bg-red-100 rounded-full',
                            processing: 'icon-sort-right text-2xl text-green-600 bg-green-100 rounded-full',
                        };
                    },
                },

                mounted() {
                    this.getNotification();
                },

                methods: {
                    getNotification() {
                        this.$axios.get('{{ route('admin.notification.get_notification') }}', {
                                params: {
                                    limit: 5,
                                    read: 0
                                }
                            })
                            .then((response) => {
                                this.notifications = response.data.search_results.data;

                                this.totalUnRead =   response.data.total_unread;
                            })
                            .catch(error => console.log(error))
                    },

                    readAll() {
                        this.$axios.post('{{ route('admin.notification.read_all') }}')
                            .then((response) => {
                                this.notifications = response.data.search_results.data;

                                this.totalUnRead = response.data.total_unread;

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.success_message });
                        })
                        .catch((error) => {});
                },
            },
        });
    </script>

    <script type="text/x-template" id="v-dark-template">
        <div class="flex">
            <span
                class="p-1.5 rounded-md text-2xl cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                :class="[isDarkMode ? 'icon-light' : 'icon-dark']"
                @click="toggle"
            ></span>
        </div>
    </script>

    <script type="module">
        app.component('v-dark', {
            template: '#v-dark-template',

            data() {
                return {
                    isDarkMode: {{ request()->cookie('dark_mode') ?? 0 }},

                    logo: "{{ bagisto_asset('images/logo.svg') }}",

                    dark_logo: "{{ bagisto_asset('images/dark-logo.svg') }}",
                };
            },

            methods: {
                toggle() {
                    this.isDarkMode = parseInt(this.isDarkModeCookie()) ? 0 : 1;

                    var expiryDate = new Date();

                    expiryDate.setMonth(expiryDate.getMonth() + 1);

                    document.cookie = 'dark_mode=' + this.isDarkMode + '; path=/; expires=' + expiryDate.toGMTString();

                    document.documentElement.classList.toggle('dark', this.isDarkMode === 1);

                    if (this.isDarkMode) {
                        this.$emitter.emit('change-theme', 'dark');

                        document.getElementById('logo-image').src = this.dark_logo;
                    } else {
                        this.$emitter.emit('change-theme', 'light');

                        document.getElementById('logo-image').src = this.logo;
                    }
                },

                isDarkModeCookie() {
                    const cookies = document.cookie.split(';');

                    for (const cookie of cookies) {
                        const [name, value] = cookie.trim().split('=');

                        if (name === 'dark_mode') {
                            return value;
                        }
                    }

                    return 0;
                },
            },
        });
    </script>
@endpushOnce
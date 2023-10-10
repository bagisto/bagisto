@php
    $admin = auth()->guard('admin')->user();
@endphp

<header class="flex justify-between items-center px-[16px] py-[10px] bg-white dark:bg-gray-900  border-b-[1px] dark:border-gray-800 sticky top-0 z-[10001]">
    <div class="flex gap-[6px] items-center">
        {{-- Hamburger Menu --}}
        <i
            class="hidden icon-menu text-[24px] p-[6px] max-lg:block cursor-pointer"
            @click="$refs.sidebarMenuDrawer.open()"
        >
        </i>

        {{-- Logo --}}
        <a
            href="{{ route('admin.dashboard.index') }}" 
            class="place-self-start -mt-[4px]"            
        >
            @if (core()->getConfigData('general.design.admin_logo.logo_image', core()->getCurrentChannelCode()))
                <img src="{{ Storage::url(core()->getConfigData('general.design.admin_logo.logo_image', core()->getCurrentChannelCode())) }}" alt="{{ config('app.name') }}" style="height: 40px; width: 110px;"/>
            @else
                @if (! request()->cookie('dark_mode'))
                    <img src="{{ bagisto_asset('images/logo.svg') }}" id="logo-image">
                @else
                    <img src="{{ bagisto_asset('images/dark-logo.svg') }}" id="logo-image">
                @endif
            @endif
        </a>

        {{-- Mega Search Bar Vue Component --}}
        <v-mega-search>
            <div class="flex items-center relative w-[525px] max-w-[525px] ltr:ml-[10px] rtl:mr-[10px]">
                <i class="icon-search text-[22px] flex items-center absolute ltr:left-[12px] rtl:right-[12px] top-[6px]"></i>

                <input 
                    type="text" 
                    class="bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-lg block w-full px-[40px] py-[5px] leading-6 text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400"
                    placeholder="@lang('admin::app.components.layouts.header.mega-search.title')" 
                >
            </div>
        </v-mega-search>
    </div>

    <div class="flex gap-[10px] items-center">
        {{-- Dark mode Switcher --}}
        <v-dark>
            <div class="flex">
                <span
                    class="{{ request()->cookie('dark_mode') ? 'icon-light' : 'icon-dark' }} p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                ></span>
            </div>
        </v-dark>

        <a 
            href="{{ route('shop.home.index') }}" 
            target="_blank"
            class="flex"
        >
            <span 
                class="icon-store p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-950 "
                title="@lang('admin::app.components.layouts.header.visit-shop')"
            >
            </span>
        </a>

       {{-- Notification Component --}}
        <v-notifications {{ $attributes }}>
            <span class="flex relative">
                <span 
                    class="icon-notification p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-950" 
                    title="@lang('admin::app.components.layouts.header.notifications')"
                >
                </span>
            </span>
        </v-notifications>

        {{-- Admin profile --}}
        <x-admin::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
            <x-slot:toggle>
                @if ($admin->image)
                    <button class="flex w-[36px] h-[36px] overflow-hidden rounded-full cursor-pointer hover:opacity-80 focus:opacity-80">
                        <img
                            src="{{ $admin->image_url }}"
                            class="w-full"
                        />
                    </button>
                @else
                    <button class="flex justify-center items-center w-[36px] h-[36px] bg-blue-400 rounded-full text-[14px] text-white font-semibold cursor-pointer leading-6 transition-all hover:bg-blue-500 focus:bg-blue-500">
                        {{ substr($admin->name, 0, 1) }}
                    </button>
                @endif
            </x-slot:toggle>

            {{-- Admin Dropdown --}}
            <x-slot:content class="!p-[0px]">
                <div class="grid gap-[10px] px-[20px] py-[10px] border border-b-gray-300 dark:border-gray-800">
                    {{-- Version --}}
                    <p class="text-gray-400">
                        @lang('admin::app.components.layouts.header.app-version', ['version' => 'v' . core()->version()])
                    </p>
                </div>

                <div class="grid gap-[4px] pb-[10px]">
                    <a
                        class="px-5 py-2 text-[16px] text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-950 cursor-pointer"
                        href="{{ route('admin.account.edit') }}"
                    >
                        @lang('admin::app.components.layouts.header.my-account')
                    </a>

                    {{--Admin logout--}}
                    <x-admin::form
                        method="DELETE"
                        action="{{ route('admin.session.destroy') }}"
                        id="adminLogout"
                    >
                    </x-admin::form>

                    <a
                        class="px-5 py-2 text-[16px] text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-950 cursor-pointer"
                        href="{{ route('admin.session.destroy') }}"
                        onclick="event.preventDefault(); document.getElementById('adminLogout').submit();"
                    >
                        @lang('admin::app.components.layouts.header.logout')
                    </a>
                </div>
            </x-slot:content>
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
            @if (core()->getConfigData('general.design.admin_logo.logo_image', core()->getCurrentChannelCode()))
                <img src="{{ Storage::url(core()->getConfigData('general.design.admin_logo.logo_image', core()->getCurrentChannelCode())) }}" alt="{{ config('app.name') }}" style="height: 40px; width: 110px;"/>
            @else
                <img src="{{ bagisto_asset('images/logo.svg') }}">
            @endif
        </div>
    </x-slot:header>

    <!-- Drawer Content -->
    <x-slot:content class="p-[16px]">
        <div class="h-[calc(100vh-100px)] overflow-auto journal-scroll">
            <nav class="grid gap-[7px] w-full">
                {{-- Navigation Menu --}}
                @foreach ($menu->items as $menuItem)
                    <div class="relative group/item">
                        <a
                            href="{{ $menuItem['url'] }}"
                            class="flex gap-[10px] p-[6px] items-center cursor-pointer {{ $menu->getActive($menuItem) == 'active' ? 'bg-blue-600 rounded-[8px]' : ' hover:bg-gray-100 dark:hover:bg-gray-950 ' }} peer"
                        >
                            <span class="{{ $menuItem['icon'] }} text-[24px] {{ $menu->getActive($menuItem) ? 'text-white' : ''}}"></span>
                            
                            <p class="text-gray-600 dark:text-gray-300 font-semibold whitespace-nowrap {{ $menu->getActive($menuItem) ? 'text-white' : ''}}">
                                @lang($menuItem['name'])
                            </p>
                        </a>

                        @if (count($menuItem['children']))
                            <div class="{{ $menu->getActive($menuItem) ? ' !grid bg-gray-100' : '' }} hidden min-w-[180px] ltr:pl-[40px] rtl:pr-[40px] pb-[7px] rounded-b-[8px] z-[100]">
                                @foreach ($menuItem['children'] as $subMenuItem)
                                    <a
                                        href="{{ $subMenuItem['url'] }}"
                                        class="text-[14px] text-{{ $menu->getActive($subMenuItem) ? 'blue':'gray' }}-600 whitespace-nowrap py-[4px]"
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
    </x-slot:content>
</x-admin::drawer>

@pushOnce('scripts')
    <script type="text/x-template" id="v-mega-search-template">
        <div class="flex items-center relative w-[525px] max-w-[525px] ltr:ml-[10px] rtl:mr-[10px]">
            <i class="icon-search text-[22px] flex items-center absolute ltr:left-[12px] rtl:right-[12px] top-[6px]"></i>

            <input 
                type="text"
                class="bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-lg block w-full px-[40px] py-[5px] leading-6 text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 peer"
                :class="{'border-gray-400': isDropdownOpen}"
                placeholder="@lang('admin::app.components.layouts.header.mega-search.title')"
                v-model.lazy="searchTerm"
                @click="searchTerm.length >= 2 ? isDropdownOpen = true : {}"
                v-debounce="500"
            >

            <div
                class="absolute top-[40px] w-full bg-white dark:bg-gray-900 shadow-[0px_0px_0px_0px_rgba(0,0,0,0.10),0px_1px_3px_0px_rgba(0,0,0,0.10),0px_5px_5px_0px_rgba(0,0,0,0.09),0px_12px_7px_0px_rgba(0,0,0,0.05),0px_22px_9px_0px_rgba(0,0,0,0.01),0px_34px_9px_0px_rgba(0,0,0,0.00)] border dark:border-gray-800 rounded-[8px] z-10"
                v-if="isDropdownOpen"
            >
                <!-- Search Tabs -->
                <div class="flex border-b-[1px] dark:border-gray-800 text-[14px] text-gray-600 dark:text-gray-300">
                    <div
                        class="p-[16px] hover:bg-gray-100 dark:hover:bg-gray-950 cursor-pointer"
                        :class="{ 'border-b-[2px] border-blue-600': activeTab == tab.key }"
                        v-for="tab in tabs"
                        @click="activeTab = tab.key; search();"
                    >
                        @{{ tab.title }}
                    </div>
                </div>

                <!-- Searched Results -->
                <template v-if="activeTab == 'products'">
                    <template v-if="isLoading">
                        <x-admin::shimmer.header.mega-search.products/>
                    </template>

                    <template v-else>
                        <div class="grid max-h-[400px] overflow-y-auto">
                            <a
                                :href="'{{ route('admin.catalog.products.edit', ':id') }}'.replace(':id', product.id)"
                                class="flex gap-[10px] justify-between p-[16px] border-b-[1px] border-slate-300 dark:border-gray-800 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 last:border-b-0"
                                v-for="product in searchedResults.products.data"
                            >
                                <!-- Left Information -->
                                <div class="flex gap-[10px]">
                                    <!-- Image -->
                                    <div
                                        class="w-full h-[60px] max-w-[60px] max-h-[60px] relative rounded-[4px] overflow-hidden"
                                        :class="{'border border-dashed border-gray-300 dark:border-gray-800 rounded-[4px] dark:invert dark:mix-blend-exclusion overflow-hidden': ! product.images.length}"
                                    >
                                        <template v-if="! product.images.length">
                                            <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}">
                                        
                                            <p class="w-full absolute bottom-[5px] text-[6px] text-gray-400 text-center font-semibold">
                                                @lang('admin::app.catalog.products.edit.types.grouped.image-placeholder')
                                            </p>
                                        </template>

                                        <template v-else>
                                            <img :src="product.images[0].url">
                                        </template>
                                    </div>

                                    <!-- Details -->
                                    <div class="grid gap-[6px] place-content-start">
                                        <p class="text-[16x] text-gray-600 dark:text-gray-300 font-semibold">
                                            @{{ product.name }}
                                        </p>

                                        <p class="text-gray-500">
                                            @{{ "@lang('admin::app.components.layouts.header.mega-search.sku')".replace(':sku', product.sku) }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Right Information -->
                                <div class="grid gap-[4px] place-content-center text-right">
                                    <p class="text-gray-600 dark:text-gray-300 font-semibold">
                                        @{{ product.formatted_price }}
                                    </p>
                                </div>
                            </a>
                        </div>

                        <div class="flex p-[12px] border-t-[1px] dark:border-gray-800">
                            <a
                                :href="'{{ route('admin.catalog.products.index') }}?search=:query'.replace(':query', searchTerm)"
                                class="text-[12px] text-blue-600 font-semibold cursor-pointer transition-all hover:underline"
                                v-if="searchedResults.products.data.length"
                            >
                                @{{ "@lang('admin::app.components.layouts.header.mega-search.explore-all-matching-products')".replace(':query', searchTerm).replace(':count', searchedResults.products.total) }}
                            </a>

                            <a
                                href="{{ route('admin.catalog.products.index') }}"
                                class="text-[12px] text-blue-600 font-semibold cursor-pointer transition-all hover:underline"
                                v-else
                            >
                                @lang('admin::app.components.layouts.header.mega-search.explore-all-products')
                            </a>
                        </div>
                    </template>
                </template>

                <template v-if="activeTab == 'orders'">
                    <template v-if="isLoading">
                        <x-admin::shimmer.header.mega-search.orders/>
                    </template>

                    <template v-else>
                        <div class="grid max-h-[400px] overflow-y-auto">
                            <a
                                :href="'{{ route('admin.sales.orders.view', ':id') }}'.replace(':id', order.id)"
                                class="grid gap-[6px] place-content-start p-[16px] border-b-[1px] border-slate-300 dark:border-gray-800 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 last:border-b-0"
                                v-for="order in searchedResults.orders.data"
                            >
                                <p class="text-[16x] text-gray-600 dark:text-gray-300 font-semibold">
                                    #@{{ order.increment_id }}
                                </p>

                                <p class="text-gray-500 dark:text-gray-300">
                                    @{{ order.formatted_created_at + ', ' + order.status_label + ', ' + order.customer_full_name }}
                                </p>
                            </a>
                        </div>

                        <div class="flex p-[12px] border-t-[1px] dark:border-gray-800  ">
                            <a
                                :href="'{{ route('admin.sales.orders.index') }}?search=:query'.replace(':query', searchTerm)"
                                class=" text-[12px] text-blue-600 font-semibold cursor-pointer transition-all hover:underline"
                                v-if="searchedResults.orders.data.length"
                            >
                                @{{ "@lang('admin::app.components.layouts.header.mega-search.explore-all-matching-orders')".replace(':query', searchTerm).replace(':count', searchedResults.orders.total) }}
                            </a>

                            <a
                                href="{{ route('admin.sales.orders.index') }}"
                                class=" text-[12px] text-blue-600 font-semibold cursor-pointer transition-all hover:underline"
                                v-else
                            >
                                @lang('admin::app.components.layouts.header.mega-search.explore-all-orders')
                            </a>
                        </div>
                    </template>
                </template>

                <template v-if="activeTab == 'categories'">
                    <template v-if="isLoading">
                        <x-admin::shimmer.header.mega-search.categories/>
                    </template>

                    <template v-else>
                        <div class="grid max-h-[400px] overflow-y-auto">
                            <a
                                :href="'{{ route('admin.catalog.categories.edit', ':id') }}'.replace(':id', category.id)"
                                class="p-[16px] border-b-[1px] dark:border-gray-800 text-[14px] text-gray-600 dark:text-gray-300 font-semibold cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 last:border-b-0"
                                v-for="category in searchedResults.categories.data"
                            >
                                @{{ category.name }}
                            </a>
                        </div>

                        <div class="flex p-[12px] border-t-[1px] dark:border-gray-800">
                            <a
                                :href="'{{ route('admin.catalog.categories.index') }}?search=:query'.replace(':query', searchTerm)"
                                class=" text-[12px] text-blue-600 font-semibold cursor-pointer transition-all hover:underline"
                                v-if="searchedResults.categories.data.length"
                            >
                                @{{ "@lang('admin::app.components.layouts.header.mega-search.explore-all-matching-categories')".replace(':query', searchTerm).replace(':count', searchedResults.categories.total) }}
                            </a>

                            <a
                                href="{{ route('admin.catalog.categories.index') }}"
                                class=" text-[12px] text-blue-600 font-semibold cursor-pointer transition-all hover:underline"
                                v-else
                            >
                                @lang('admin::app.components.layouts.header.mega-search.explore-all-categories')
                            </a>
                        </div>
                    </template>
                </template>

                <template v-if="activeTab == 'customers'">
                    <template v-if="isLoading">
                        <x-admin::shimmer.header.mega-search.customers/>
                    </template>

                    <template v-else>
                        <div class="grid max-h-[400px] overflow-y-auto">
                            <a
                                :href="'{{ route('admin.customers.customers.view', ':id') }}'.replace(':id', customer.id)"
                                class="grid gap-[6px] place-content-start p-[16px] border-b-[1px] border-slate-300 dark:border-gray-800 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 last:border-b-0"
                                v-for="customer in searchedResults.customers.data"
                            >
                                <p class="text-[16x] text-gray-600 dark:text-gray-300 font-semibold">
                                    @{{ customer.first_name + ' ' + customer.last_name }}
                                </p>

                                <p class="text-gray-500">
                                    @{{ customer.email }}
                                </p>
                            </a>
                        </div>

                        <div class="flex p-[12px] border-t-[1px] dark:border-gray-800">
                            <a
                                :href="'{{ route('admin.customers.customers.index') }}?search=:query'.replace(':query', searchTerm)"
                                class=" text-[12px] text-blue-600 font-semibold cursor-pointer transition-all hover:underline"
                                v-if="searchedResults.customers.data.length"
                            >
                                @{{ "@lang('admin::app.components.layouts.header.mega-search.explore-all-matching-customers')".replace(':query', searchTerm).replace(':count', searchedResults.customers.total) }}
                            </a>

                            <a
                                href="{{ route('admin.customers.customers.index') }}"
                                class=" text-[12px] text-blue-600 font-semibold cursor-pointer transition-all hover:underline"
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

    <script 
        type="text/x-template"
        id="v-notifications-template"
    >
        <x-admin::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
            <!-- Notification Toggle -->
            <x-slot:toggle>
                <span class="flex relative">
                    <span
                        class="icon-notification p-[6px] rounded-[6px] text-[24px] text-red cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-950" 
                        title="@lang('admin::app.components.layouts.header.notifications')"
                    >
                    </span>
                
                    <span
                        class="flex justify-center items-center min-w-[20px] h-[20px] absolute top-[-8px] p-[5px] ltr:left-[18px] rtl:right-[18px] bg-blue-600 rounded-[44px] text-white text-[10px] font-semibold leading-[9px] cursor-pointer"
                        v-text="totalUnRead"
                        v-if="totalUnRead"
                    >
                    </span>
                </span>
            </x-slot:toggle>

            <!-- Notification Content -->
            <x-slot:content class="!p-0 min-w-[250px] max-w-[250px]">
                <!-- Header -->
                <div class="text-[16px] p-[12px] text-gray-600 dark:text-gray-300 font-semibold border-b-[1px] dark:border-gray-800">
                    @lang('admin::app.notifications.title', ['read' => 0])
                </div>

                <!-- Content -->
                <div class="grid">
                    <a
                        class="flex gap-[5px] items-start p-[12px] hover:bg-gray-50 dark:hover:bg-gray-950 border-b-[1px] dark:border-gray-800 last:border-b-0"
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

                            <!-- Craeted Date In humand Readable Format -->
                            <p class="text-[12px] text-gray-600 dark:text-gray-300">
                                @{{ notification.order.datetime }}
                            </p>
                        </div>
                    </a>
                </div>

                <!-- Footer -->
                <div class="flex gap-[10px] justify-between p-[12px] border-t-[1px] dark:border-gray-800">
                    <a
                        href="{{ route('admin.notification.index') }}"
                        class="text-[12px] text-blue-600 font-semibold cursor-pointer transition-all hover:underline"
                    >
                        @lang('admin::app.notifications.view-all')
                    </a>

                    <a
                        class="text-[12px] text-blue-600 font-semibold cursor-pointer transition-all hover:underline"
                        v-if="notifications?.length"
                        @click="readAll()"
                    >
                        @lang('admin::app.notifications.read-all')
                    </a>
                </div>
            </x-slot:content>
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
                                message: 'Order Pending',
                            },
                            processing: {
                                icon: 'icon-processing',
                                message: 'Order Processing'
                            },
                            canceled: {
                                icon: 'icon-cancel-1',
                                message: 'Order Canceled'
                            },
                            completed: {
                                icon: 'icon-done',
                                message: 'Order Completed'
                            },
                            closed: {
                                icon: 'icon-cancel-1',
                                message: 'Order Closed'
                            },
                            pending_payment: {
                                icon: 'icon-information',
                                message: 'Payment Pending'
                            },
                        },

                        totalUnRead: 0,

                        orderTypeMessages: {
                            'pending': "@lang('admin::app.notifications.order-status-messages.pending')",
                            'canceled': "@lang('admin::app.notifications.order-status-messages.canceled')",
                            'closed': "@lang('admin::app.notifications.order-status-messages.closed')",
                            'completed': "@lang('admin::app.notifications.order-status-messages.completed')",
                            'processing': "@lang('admin::app.notifications.order-status-messages.processing')",
                            'pending_payment': "@lang('admin::app.notifications.order-status-messages.pending-payment')",
                        }
                    }
                },

                computed: {
                    notificationStatusIcon() {
                        return {
                            pending: 'icon-information text-[24px] text-amber-600 bg-amber-100 rounded-full',
                            closed: 'icon-repeat text-[24px] text-red-600 bg-red-100 rounded-full',
                            completed: 'icon-done text-[24px] text-blue-600 bg-blue-100 rounded-full',
                            canceled: 'icon-cancel-1 text-[24px] text-red-600 bg-red-100 rounded-full',
                            processing: 'icon-sort-right text-[24px] text-green-600 bg-green-100 rounded-full',
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
                class="p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
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
                        document.getElementById('logo-image').src= this.dark_logo;
                    } else {
                        document.getElementById('logo-image').src=this.logo;
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
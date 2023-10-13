<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.reporting.sales.index.title')
    </x-slot:title>

    {{-- Page Header --}}
    <div class="flex gap-[16px] justify-between items-center mb-[20px] max-sm:flex-wrap">
        <div class="grid gap-[6px]">
            <p class="pt-[6px] text-[20px] text-gray-800 dark:text-white font-bold leading-[24px]">
                @lang('admin::app.reporting.sales.index.title')
            </p>
        </div>
    </div>

    {{-- Sales Stats Vue Component --}}
    <v-sales-stats>
        <x-admin::shimmer.reporting.sales/>
    </v-sales-stats>

    @pushOnce('scripts')
        <script type="module" src="{{ bagisto_asset('js/chart.js') }}"></script>

        <script type="text/x-template" id="v-sales-stats-template">
            <div class="flex flex-col gap-[15px] flex-1 max-xl:flex-auto">
                <!-- Sales Section -->
                <div class="relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-[16px]">
                        <p class="text-[16px] text-gray-600 dark:text-white font-semibold">
                            @lang('admin::app.reporting.sales.index.total-sales')
                        </p>

                        <a
                            href="#"
                            class="text-[14px] text-blue-600 cursor-pointer transition-all hover:underline"
                        >
                            @lang('admin::app.reporting.sales.index.view-details')
                        </a>
                    </div>

                    <template v-if="isLoading.getTotalSalesStats">
                        <x-admin::shimmer.reporting.graph/>
                    </template>

                    <template v-else>
                        <!-- Content -->
                        <div class="grid gap-[16px]">
                            <div class="flex gap-[16px] place-content-start">
                                <p class="text-[30px] text-gray-600 dark:text-gray-300 font-bold leading-9">
                                    @{{ reports.getTotalSalesStats.statistics.sales.formatted_total }}
                                </p>
                                
                                <div class="flex gap-[2px] items-center">
                                    <p
                                        class="text-[16px] text-emerald-500"
                                        :class="[reports.getTotalSalesStats.statistics.sales.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                                    >
                                        @{{ reports.getTotalSalesStats.statistics.sales.progress.toFixed(2) }}%
                                    </p>

                                    <span
                                        class="text-[16px] text-emerald-500"
                                        :class="[reports.getTotalSalesStats.statistics.sales.progress < 0 ? 'icon-down-stat text-red-500' : 'icon-up-stat text-emerald-500']"
                                    ></span>
                                </div>
                            </div>

                            <p class="text-[16px] text-gray-600 font-semibold">
                                @lang('admin::app.reporting.sales.index.sales-over-time')
                            </p>

                            <!-- Line Chart -->
                            <canvas
                                id="getTotalSalesStats"
                                class="flex items-end w-full aspect-[3.17/1]"
                            ></canvas>

                            <!-- Date Range Section -->
                            <div class="flex gap-[20px] justify-center">
                                <div class="flex gap-[4px] items-center">
                                    <span class="w-[14px] h-[14px] rounded-[3px] bg-emerald-400"></span>

                                    <p class="text-[12px]">
                                        @{{ reports.getTotalSalesStats.date_range.previous }}
                                    </p>
                                </div>

                                <div class="flex gap-[4px] items-center">
                                    <span class="w-[14px] h-[14px] rounded-[3px] bg-sky-400"></span>

                                    <p class="text-[12px]">
                                        @{{ reports.getTotalSalesStats.date_range.current }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Purchase Funnel and Abandoned Carts Sections Container -->
                <div class="flex justify-between gap-[15px] flex-1 max-xl:flex-auto">
                    <!-- Purchase Funnel Section -->
                    <div class="flex-1 relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                        <!-- Header -->
                        <p class="text-[16px] text-gray-600 dark:text-white font-semibold mb-[16px]">
                            @lang('admin::app.reporting.sales.index.purchase-funnel')
                        </p>

                        <template v-if="isLoading.getPurchaseFunnelStats">
                            <x-admin::shimmer.reporting.sales.purchase-funnel/>
                        </template>

                        <template v-else>
                            <!-- Content -->
                            <div class="grid grid-cols-4 gap-[24px]">
                                <!-- Total Visits -->
                                <div class="grid gap-[16px]">
                                    <div class="grid gap-[2px]">
                                        <p class="text-[16px] text-gray-800 font-semibold">
                                            @{{ reports.getPurchaseFunnelStats.statistics.visitors.total }}
                                        </p>

                                        <p class="text-[12px] text-gray-600 font-semibold">
                                            @lang('admin::app.reporting.sales.index.total-visits')
                                        </p>
                                    </div>

                                    <div class="w-full relative bg-slate-100 aspect-[0.5/1]">
                                        <div
                                            class="w-full absolute bottom-0 bg-emerald-400"
                                            :style="{ 'height': reports.getPurchaseFunnelStats.statistics.visitors.progress + '%' }"
                                        ></div>
                                    </div>

                                    <p class="text-gray-600">
                                        @lang('admin::app.reporting.sales.index.total-visits-info')
                                    </p>
                                </div>

                                <!-- Total Product Visits -->
                                <div class="grid gap-[16px]">
                                    <div class="grid gap-[2px]">
                                        <p class="text-[16px] text-gray-800 font-semibold">
                                            @{{ reports.getPurchaseFunnelStats.statistics.product_visitors.total }}
                                        </p>

                                        <p class="text-[12px] text-gray-600 font-semibold">
                                            @lang('admin::app.reporting.sales.index.product-views')
                                        </p>
                                    </div>

                                    <div class="w-full relative bg-slate-100 aspect-[0.5/1]">
                                        <div
                                            class="w-full absolute bottom-0 bg-emerald-400"
                                            :style="{ 'height': reports.getPurchaseFunnelStats.statistics.product_visitors.progress + '%' }"
                                        ></div>
                                    </div>

                                    <p
                                        class="text-gray-600"
                                        v-html="'@lang('admin::app.reporting.sales.index.product-views-info')'.replace(':progress', '<span class=\'text-emerald-400 font-semibold\'>' + reports.getPurchaseFunnelStats.statistics.product_visitors.progress + '%</span>')"
                                    ></p>
                                </div>

                                <!-- Total Added To Cart -->
                                <div class="grid gap-[16px]">
                                    <div class="grid gap-[2px]">
                                        <p class="text-[16px] text-gray-800 font-semibold">
                                            @{{ reports.getPurchaseFunnelStats.statistics.carts.total }}
                                        </p>

                                        <p class="text-[12px] text-gray-600 font-semibold">
                                            @lang('admin::app.reporting.sales.index.added-to-cart')
                                        </p>
                                    </div>

                                    <div class="w-full relative bg-slate-100 aspect-[0.5/1]">
                                        <div
                                            class="w-full absolute bottom-0 bg-emerald-400"
                                            :style="{ 'height': reports.getPurchaseFunnelStats.statistics.carts.progress + '%' }"
                                        ></div>
                                    </div>

                                    <p
                                        class="text-gray-600"
                                        v-html="'@lang('admin::app.reporting.sales.index.added-to-cart-info')'.replace(':progress', '<span class=\'text-emerald-400 font-semibold\'>' + reports.getPurchaseFunnelStats.statistics.carts.progress + '%</span>')"
                                    ></p>
                                </div>

                                <!-- Total Purchased -->
                                <div class="grid gap-[16px]">
                                    <div class="grid gap-[2px]">
                                        <p class="text-[16px] text-gray-800 font-semibold">
                                            @{{ reports.getPurchaseFunnelStats.statistics.orders.total }}
                                        </p>

                                        <p class="text-[12px] text-gray-600 font-semibold">
                                            @lang('admin::app.reporting.sales.index.purchased')
                                        </p>
                                    </div>

                                    <div class="w-full relative bg-slate-100 aspect-[0.5/1]">
                                        <div
                                            class="w-full absolute bottom-0 bg-emerald-400"
                                            :style="{ 'height': reports.getPurchaseFunnelStats.statistics.orders.progress + '%' }"
                                        ></div>
                                    </div>

                                    <p
                                        class="text-gray-600"
                                        v-html="'@lang('admin::app.reporting.sales.index.purchased-info')'.replace(':progress', '<span class=\'text-emerald-400 font-semibold\'>' + reports.getPurchaseFunnelStats.statistics.orders.progress + '%</span>')"
                                    ></p>
                                </div>
                            </div>

                            <!-- Date Range Section -->
                            <div class="flex gap-[20px] justify-end mt-[24px]">
                                <div class="flex gap-[4px] items-center">
                                    <span class="w-[14px] h-[14px] rounded-[3px] bg-emerald-400"></span>

                                    <p class="text-[12px]">
                                        @{{ reports.getPurchaseFunnelStats.date_range.current }}
                                    </p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Abandoned Carts Section -->
                    <div class="flex-1 relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                        <div class="flex items-center justify-between mb-[16px]">
                            <p class="text-[16px] text-gray-600 dark:text-white font-semibold">
                                @lang('admin::app.reporting.sales.index.abandoned-carts')
                            </p>

                            <a
                                href="#"
                                class="text-[14px] text-blue-600 cursor-pointer transition-all hover:underline"
                            >
                                @lang('admin::app.reporting.sales.index.view-details')
                            </a>
                        </div>

                        <template v-if="isLoading.getAbandonedCartsStats">
                            <x-admin::shimmer.reporting.sales.abandoned-carts/>
                        </template>

                        <template v-else>
                            <!-- Content -->
                            <div class="grid gap-[16px]">
                                <!-- Stats -->
                                <div class="flex gap-[16px] justify-between">
                                    <!-- Abandoned Revenue -->
                                    <div class="grid gap-[4px]">
                                        <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                            @{{ reports.getAbandonedCartsStats.statistics.sales.formatted_total }}
                                        </p>

                                        <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                            @lang('admin::app.reporting.sales.index.abandoned-revenue')
                                        </p>
                                        
                                        <div class="flex gap-[2px] items-center">
                                            <span
                                                class="text-[16px] text-emerald-500"
                                                :class="[reports.getAbandonedCartsStats.statistics.sales.progress < 0 ? 'icon-down-stat text-red-500' : 'icon-up-stat text-emerald-500']"
                                            ></span>

                                            <p
                                                class="text-[16px] text-emerald-500"
                                                :class="[reports.getAbandonedCartsStats.statistics.sales.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                                            >
                                                @{{ reports.getAbandonedCartsStats.statistics.sales.progress.toFixed(2) }}%
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Abandoned Cart -->
                                    <div class="grid gap-[4px]">
                                        <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                            @{{ reports.getAbandonedCartsStats.statistics.carts.current }}
                                        </p>

                                        <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                            @lang('admin::app.reporting.sales.index.abandoned-carts')
                                        </p>
                                        
                                        <div class="flex gap-[2px] items-center">
                                            <span
                                                class="text-[16px] text-emerald-500"
                                                :class="[reports.getAbandonedCartsStats.statistics.carts.progress < 0 ? 'icon-down-stat text-red-500' : 'icon-up-stat text-emerald-500']"
                                            ></span>

                                            <p
                                                class="text-[16px] text-emerald-500"
                                                :class="[reports.getAbandonedCartsStats.statistics.carts.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                                            >
                                                @{{ reports.getAbandonedCartsStats.statistics.carts.progress.toFixed(2) }}%
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Abandoned Rate -->
                                    <div class="grid gap-[4px]">
                                        <div class="flex gap-[2px]">
                                            <p
                                                class="text-[16px] text-emerald-500"
                                                :class="[reports.getAbandonedCartsStats.statistics.rate.progress >= 0 ?  'text-red-500' : 'text-emerald-500']"
                                            >
                                                @{{ reports.getAbandonedCartsStats.statistics.rate.current.toFixed(2) }}%
                                            </p>

                                            <span
                                                class="text-[16px] text-emerald-500"
                                                :class="[reports.getAbandonedCartsStats.statistics.carts.progress >= 0 ? 'icon-down-stat text-red-500' : 'icon-up-stat text-emerald-500']"
                                            ></span>
                                        </div>

                                        <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                            @lang('admin::app.reporting.sales.index.abandoned-rate')
                                        </p>
                                        
                                        <div class="flex gap-[2px] items-center">
                                            <p
                                                class="text-[16px] text-emerald-500"
                                                :class="[reports.getAbandonedCartsStats.statistics.rate.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                                            >
                                                @{{ reports.getAbandonedCartsStats.statistics.rate.progress.toFixed(2) }}%
                                            </p>

                                            <span
                                                class="text-[16px] text-emerald-500"
                                                :class="[reports.getAbandonedCartsStats.statistics.carts.progress < 0 ? 'icon-down-stat text-red-500' : 'icon-up-stat text-emerald-500']"
                                            ></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Header -->
                                <p class="py-[10px] text-[16px] text-gray-600 dark:text-white font-semibold mt-[16px]">
                                    @lang('admin::app.reporting.sales.index.abandoned-products')
                                </p>

                                <!-- Abandoned Products -->
                                <div class="grid gap-[27px]">
                                    <div
                                        class="grid"
                                        v-for="product in reports.getAbandonedCartsStats.statistics.products"
                                    >
                                        <p>@{{ product.name }}</p>

                                        <div class="flex gap-[20px] items-center">
                                            <div class="w-full h-[8px] relative bg-slate-100">
                                                <div
                                                    class="h-[8px] absolute left-0 bg-blue-500"
                                                    :style="{ 'width': product.progress + '%' }"
                                                ></div>
                                            </div>

                                            <p class="text-[14px] text-gray-600 font-semibold">
                                                @{{ product.count }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Date Range -->
                                <div class="flex gap-[20px] justify-end">
                                    <div class="flex gap-[4px] items-center">
                                        <span class="w-[14px] h-[14px] rounded-[3px] bg-blue-500"></span>

                                        <p class="text-[12px]">
                                            @{{ reports.getAbandonedCartsStats.date_range.current }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Total Orders and Average Order Value Sections Container -->
                <div class="flex justify-between gap-[15px] flex-1 max-xl:flex-auto">
                    <!-- Total Orders Section -->
                    <div class="flex-1 relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-[16px]">
                            <p class="text-[16px] text-gray-600 dark:text-white font-semibold">
                                @lang('admin::app.reporting.sales.index.total-orders')
                            </p>

                            <a
                                href="#"
                                class="text-[14px] text-blue-600 cursor-pointer transition-all hover:underline"
                            >
                                @lang('admin::app.reporting.sales.index.view-details')
                            </a>
                        </div>

                        <template v-if="isLoading.getTotalOrdersStats">
                            <x-admin::shimmer.reporting.graph/> 
                        </template>

                        <template v-else>
                            <!-- Content -->
                            <div class="grid gap-[16px]">
                                <div class="flex gap-[16px] justify-between">
                                    <p class="text-[30px] text-gray-600 dark:text-gray-300 font-bold leading-9">
                                        @{{ reports.getTotalOrdersStats.statistics.orders.current }}
                                    </p>
                                    
                                    <div class="flex gap-[2px] items-center">
                                        <span
                                            class="text-[16px] text-emerald-500"
                                            :class="[reports.getTotalOrdersStats.statistics.orders.progress < 0 ? 'icon-down-stat text-red-500' : 'icon-up-stat text-emerald-500']"
                                        ></span>

                                        <p
                                            class="text-[16px] text-emerald-500"
                                            :class="[reports.getTotalOrdersStats.statistics.orders.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                                        >
                                            @{{ reports.getTotalOrdersStats.statistics.orders.progress.toFixed(2) }}%
                                        </p>
                                    </div>
                                </div>

                                <p class="text-[16px] text-gray-600 font-semibold">
                                    @lang('admin::app.reporting.sales.index.orders-over-time')
                                </p>

                                <!-- Line Chart -->
                                <canvas
                                    id="getTotalOrdersStats"
                                    class="flex items-end w-full aspect-[3.23/1]"
                                ></canvas>

                                <!-- Date Range -->
                                <div class="flex gap-[20px] justify-center">
                                    <div class="flex gap-[4px] items-center">
                                        <span class="w-[14px] h-[14px] rounded-[3px] bg-emerald-400"></span>

                                        <p class="text-[12px]">
                                            @{{ reports.getTotalOrdersStats.date_range.previous }}
                                        </p>
                                    </div>

                                    <div class="flex gap-[4px] items-center">
                                        <span class="w-[14px] h-[14px] rounded-[3px] bg-sky-400"></span>

                                        <p class="text-[12px]">
                                            @{{ reports.getTotalOrdersStats.date_range.current }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Average Order Value Section -->
                    <div class="flex-1 relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-[16px]">
                            <p class="text-[16px] text-gray-600 dark:text-white font-semibold">
                                @lang('admin::app.reporting.sales.index.average-order-value')
                            </p>

                            <a
                                href="#"
                                class="text-[14px] text-blue-600 cursor-pointer transition-all hover:underline"
                            >
                                @lang('admin::app.reporting.sales.index.view-details')
                            </a>
                        </div>

                        <template v-if="isLoading.getAverageSalesStats">
                            <x-admin::shimmer.reporting.graph/>
                        </template>

                        <template v-else>
                            <!-- Content -->
                            <div class="grid gap-[16px]">
                                <div class="flex gap-[16px] justify-between">
                                    <p class="text-[30px] text-gray-600 dark:text-gray-300 font-bold leading-9">
                                        @{{ reports.getAverageSalesStats.statistics.sales.formatted_total }}
                                    </p>
                                    
                                    <div class="flex gap-[2px] items-center">
                                        <span
                                            class="text-[16px] text-emerald-500"
                                            :class="[reports.getAverageSalesStats.statistics.sales.progress < 0 ? 'icon-down-stat text-red-500' : 'icon-up-stat text-emerald-500']"
                                        ></span>

                                        <p
                                            class="text-[16px] text-emerald-500"
                                            :class="[reports.getAverageSalesStats.statistics.sales.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                                        >
                                            @{{ reports.getAverageSalesStats.statistics.sales.progress.toFixed(2) }}%
                                        </p>
                                    </div>
                                </div>

                                <p class="text-[16px] text-gray-600 font-semibold">
                                    @lang('admin::app.reporting.sales.index.average-order-value-over-time')
                                </p>

                                <!-- Line Chart -->
                                <canvas
                                    id="getAverageSalesStats"
                                    class="flex items-end w-full aspect-[3.23/1]"
                                ></canvas>

                                <!-- Date Range -->
                                <div class="flex gap-[20px] justify-center">
                                    <div class="flex gap-[4px] items-center">
                                        <span class="w-[14px] h-[14px] rounded-[3px] bg-emerald-400"></span>

                                        <p class="text-[12px]">
                                            @{{ reports.getAverageSalesStats.date_range.previous }}
                                        </p>
                                    </div>

                                    <div class="flex gap-[4px] items-center">
                                        <span class="w-[14px] h-[14px] rounded-[3px] bg-sky-400"></span>

                                        <p class="text-[12px]">
                                            @{{ reports.getAverageSalesStats.date_range.current }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Tax Collected and Shipping Collected Sections Container -->
                <div class="flex justify-between gap-[15px] flex-1 max-xl:flex-auto">
                    <!-- Tax Collected Section -->
                    <div class="flex-1 relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-[16px]">
                            <p class="text-[16px] text-gray-600 dark:text-white font-semibold">
                                @lang('admin::app.reporting.sales.index.tax-collected')
                            </p>

                            <a
                                href="#"
                                class="text-[14px] text-blue-600 cursor-pointer transition-all hover:underline"
                            >
                                @lang('admin::app.reporting.sales.index.view-details')
                            </a>
                        </div>

                        <template v-if="isLoading.getTaxCollectedStats">
                            <div class="grid gap-[16px]">
                                <x-admin::shimmer.reporting.graph/>

                                <div class="shimmer w-[150px] h-[17px] mb-[16px]"></div>

                                <x-admin::shimmer.reporting.progress-bar/>
                            </div>
                        </template>

                        <template v-else>
                            <!-- Content -->
                            <div class="grid gap-[16px]">
                                <div class="flex gap-[16px] justify-between">
                                    <p class="text-[30px] text-gray-600 dark:text-gray-300 font-bold leading-9">
                                        @{{ reports.getTaxCollectedStats.statistics.tax_collected.formatted_total }}
                                    </p>
                                    
                                    <div class="flex gap-[2px] items-center">
                                        <span
                                            class="text-[16px] text-emerald-500"
                                            :class="[reports.getTaxCollectedStats.statistics.tax_collected.progress < 0 ? 'icon-down-stat text-red-500' : 'icon-up-stat text-emerald-500']"
                                        ></span>

                                        <p
                                            class="text-[16px] text-emerald-500"
                                            :class="[reports.getTaxCollectedStats.statistics.tax_collected.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                                        >
                                            @{{ reports.getTaxCollectedStats.statistics.tax_collected.progress.toFixed(2) }}%
                                        </p>
                                    </div>
                                </div>

                                <p class="text-[16px] text-gray-600 font-semibold">
                                    @lang('admin::app.reporting.sales.index.tax-collected-over-time')
                                </p>

                                <!-- Line Chart -->
                                <canvas
                                    id="getTaxCollectedStats"
                                    class="flex items-end w-full aspect-[3.23/1]"
                                ></canvas>

                                <!-- Date Range -->
                                <div class="flex gap-[20px] justify-center">
                                    <div class="flex gap-[4px] items-center">
                                        <span class="w-[14px] h-[14px] rounded-[3px] bg-emerald-400"></span>

                                        <p class="text-[12px]">
                                            @{{ reports.getTaxCollectedStats.date_range.previous }}
                                        </p>
                                    </div>

                                    <div class="flex gap-[4px] items-center">
                                        <span class="w-[14px] h-[14px] rounded-[3px] bg-sky-400"></span>

                                        <p class="text-[12px]">
                                            @{{ reports.getTaxCollectedStats.date_range.current }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Tax Categories -->
                                <template v-if="reports.getTaxCollectedStats.statistics.top_categories.length">
                                    <!-- Header -->
                                    <p class="py-[10px] text-[16px] text-gray-600 dark:text-white font-semibold">
                                        @lang('admin::app.reporting.sales.index.top-tax-categories')
                                    </p>

                                    <!-- Categories -->
                                    <div class="grid gap-[27px]">
                                        <div
                                            class="grid"
                                            v-for="category in reports.getTaxCollectedStats.statistics.top_categories"
                                        >
                                            <p>@{{ category.name }}</p>

                                            <div class="flex gap-[20px] items-center">
                                                <div class="w-full h-[8px] relative bg-slate-100">
                                                    <div
                                                        class="h-[8px] absolute left-0 bg-blue-500"
                                                        :style="{ 'width': category.progress + '%' }"
                                                    ></div>
                                                </div>

                                                <p class="text-[14px] text-gray-600 font-semibold">
                                                    @{{ category.formatted_total }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- Date Range -->
                                <div class="flex gap-[20px] justify-end">
                                    <div class="flex gap-[4px] items-center">
                                        <span class="w-[14px] h-[14px] rounded-[3px] bg-sky-400"></span>

                                        <p class="text-[12px]">
                                            @{{ reports.getTaxCollectedStats.date_range.current }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Shipping Collected Section -->
                    <div class="flex-1 relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-[16px]">
                            <p class="text-[16px] text-gray-600 dark:text-white font-semibold">
                                @lang('admin::app.reporting.sales.index.shipping-collected')
                            </p>

                            <a
                                href="#"
                                class="text-[14px] text-blue-600 cursor-pointer transition-all hover:underline"
                            >
                                @lang('admin::app.reporting.sales.index.view-details')
                            </a>
                        </div>

                        <template v-if="isLoading.getShippingCollectedStats">
                            <div class="grid gap-[16px]">
                                <x-admin::shimmer.reporting.graph/>

                                <div class="shimmer w-[150px] h-[17px] mb-[16px]"></div>

                                <x-admin::shimmer.reporting.progress-bar/>
                            </div>
                        </template>

                        <template v-else>
                            <!-- Content -->
                            <div class="grid gap-[16px]">
                                <div class="flex gap-[16px] justify-between">
                                    <p class="text-[30px] text-gray-600 dark:text-gray-300 font-bold leading-9">
                                        @{{ reports.getShippingCollectedStats.statistics.shipping_collected.formatted_total }}
                                    </p>
                                    
                                    <div class="flex gap-[2px] items-center">
                                        <span
                                            class="text-[16px] text-emerald-500"
                                            :class="[reports.getShippingCollectedStats.statistics.shipping_collected.progress < 0 ? 'icon-down-stat text-red-500' : 'icon-up-stat text-emerald-500']"
                                        ></span>

                                        <p
                                            class="text-[16px] text-emerald-500"
                                            :class="[reports.getShippingCollectedStats.statistics.shipping_collected.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                                        >
                                            @{{ reports.getShippingCollectedStats.statistics.shipping_collected.progress.toFixed(2) }}%
                                        </p>
                                    </div>
                                </div>

                                <p class="text-[16px] text-gray-600 font-semibold">
                                    @lang('admin::app.reporting.sales.index.shipping-collected-over-time')
                                </p>

                                <!-- Line Chart -->
                                <canvas
                                    id="getShippingCollectedStats"
                                    class="flex items-end w-full aspect-[3.23/1]"
                                ></canvas>

                                <!-- Chart Date Range -->
                                <div class="flex gap-[20px] justify-center">
                                    <div class="flex gap-[4px] items-center">
                                        <span class="w-[14px] h-[14px] rounded-[3px] bg-emerald-400"></span>

                                        <p class="text-[12px]">
                                            @{{ reports.getShippingCollectedStats.date_range.previous }}
                                        </p>
                                    </div>

                                    <div class="flex gap-[4px] items-center">
                                        <span class="w-[14px] h-[14px] rounded-[3px] bg-sky-400"></span>

                                        <p class="text-[12px]">
                                            @{{ reports.getShippingCollectedStats.date_range.current }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Top Shipping Methods -->
                                <template v-if="reports.getShippingCollectedStats.statistics.top_methods.length">
                                    <!-- Header -->
                                    <p class="py-[10px] text-[16px] text-gray-600 dark:text-white font-semibold">
                                        @lang('admin::app.reporting.sales.index.top-shipping-methods')
                                    </p>

                                    <!-- Methods -->
                                    <div class="grid gap-[27px]">
                                        <div
                                            class="grid"
                                            v-for="method in reports.getShippingCollectedStats.statistics.top_methods"
                                        >
                                            <p>@{{ method.title }}</p>

                                            <div class="flex gap-[20px] items-center">
                                                <div class="w-full h-[8px] relative bg-slate-100">
                                                    <div
                                                        class="h-[8px] absolute left-0 bg-emerald-500"
                                                        :style="{ 'width': method.progress + '%' }"
                                                    ></div>
                                                </div>

                                                <p class="text-[14px] text-gray-600 font-semibold">
                                                    @{{ method.formatted_total }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- Date Range -->
                                <div class="flex gap-[20px] justify-end">
                                    <div class="flex gap-[4px] items-center">
                                        <span class="w-[14px] h-[14px] rounded-[3px] bg-emerald-400"></span>

                                        <p class="text-[12px]">
                                            @{{ reports.getShippingCollectedStats.date_range.current }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Refunds and Top Payment Methods Sections Container -->
                <div class="flex justify-between gap-[15px] flex-1 max-xl:flex-auto">
                    <!-- Refunds Section -->
                    <div class="flex-1 relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-[16px]">
                            <p class="text-[16px] text-gray-600 dark:text-white font-semibold">
                                @lang('admin::app.reporting.sales.index.refunds')
                            </p>

                            <a
                                href="#"
                                class="text-[14px] text-blue-600 cursor-pointer transition-all hover:underline"
                            >
                                @lang('admin::app.reporting.sales.index.view-details')
                            </a>
                        </div>

                        <template v-if="isLoading.getRefundsStats">
                            <x-admin::shimmer.reporting.graph/>
                        </template>

                        <template v-else>
                            <!-- Content -->
                            <div class="grid gap-[16px]">
                                <div class="flex gap-[16px] justify-between">
                                    <p class="text-[30px] text-gray-600 dark:text-gray-300 font-bold leading-9">
                                        @{{ reports.getRefundsStats.statistics.refunds.formatted_total }}
                                    </p>
                                    
                                    <div class="flex gap-[2px] items-center">
                                        <span
                                            class="text-[16px] text-emerald-500"
                                            :class="[reports.getRefundsStats.statistics.refunds.progress < 0 ? 'icon-down-stat text-red-500' : 'icon-up-stat text-emerald-500']"
                                        ></span>

                                        <p
                                            class="text-[16px] text-emerald-500"
                                            :class="[reports.getRefundsStats.statistics.refunds.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                                        >
                                            @{{ reports.getRefundsStats.statistics.refunds.progress.toFixed(2) }}%
                                        </p>
                                    </div>
                                </div>

                                <p class="text-[16px] text-gray-600 font-semibold">
                                    @lang('admin::app.reporting.sales.index.refunds-over-time')
                                </p>

                                <!-- Line Chart -->
                                <canvas
                                    id="getRefundsStats"
                                    class="flex items-end w-full aspect-[3.23/1]"
                                ></canvas>

                                <!-- Date Range -->
                                <div class="flex gap-[20px] justify-center">
                                    <div class="flex gap-[4px] items-center">
                                        <span class="w-[14px] h-[14px] rounded-[3px] bg-emerald-400"></span>

                                        <p class="text-[12px]">
                                            @{{ reports.getRefundsStats.date_range.previous }}
                                        </p>
                                    </div>

                                    <div class="flex gap-[4px] items-center">
                                        <span class="w-[14px] h-[14px] rounded-[3px] bg-sky-400"></span>

                                        <p class="text-[12px]">
                                            @{{ reports.getRefundsStats.date_range.current }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Top Payment Methods Section -->
                    <div class="flex-1 relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-[16px]">
                            <p class="text-[16px] text-gray-600 dark:text-white font-semibold">
                                @lang('admin::app.reporting.sales.index.top-payment-methods')
                            </p>

                            <a
                                href="#"
                                class="text-[14px] text-blue-600 cursor-pointer transition-all hover:underline"
                            >
                                @lang('admin::app.reporting.sales.index.view-details')
                            </a>
                        </div>

                        <template v-if="isLoading.getTopPaymentMethods">
                            <x-admin::shimmer.reporting.progress-bar/>
                        </template>

                        <template v-else>
                            <!-- Content -->
                            <div class="grid gap-[16px]">
                                <!-- Top Payment Methods -->
                                <template v-if="reports.getTopPaymentMethods.statistics">
                                    <!-- Payment Methods -->
                                    <div class="grid gap-[27px]">
                                        <div
                                            class="grid"
                                            v-for="method in reports.getTopPaymentMethods.statistics"
                                        >
                                            <p>@{{ method.title }}</p>

                                            <div class="flex gap-[20px] items-center">
                                                <div class="w-full h-[8px] relative bg-slate-100">
                                                    <div
                                                        class="h-[8px] absolute left-0 bg-emerald-500"
                                                        :style="{ 'width': method.progress + '%' }"
                                                    ></div>
                                                </div>

                                                <p class="text-[14px] text-gray-600 font-semibold">
                                                    @{{ method.formatted_total }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- Date Range -->
                                <div class="flex gap-[20px] justify-end">
                                    <div class="flex gap-[4px] items-center">
                                        <span class="w-[14px] h-[14px] rounded-[3px] bg-sky-400"></span>

                                        <p class="text-[12px]">
                                            @{{ reports.getTopPaymentMethods.date_range.current }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-sales-stats', {
                template: '#v-sales-stats-template',

                data() {
                    return {
                        reports: {
                            getTotalSalesStats: [],
                            getAverageSalesStats: [],
                            getTotalOrdersStats: [],
                            getPurchaseFunnelStats: [],
                            getAbandonedCartsStats: [],
                            getRefundsStats: [],
                            getTaxCollectedStats: [],
                            getShippingCollectedStats: [],
                            getTopPaymentMethods: [],
                        },

                        isLoading: {
                            getTotalSalesStats: true,
                            getAverageSalesStats: true,
                            getTotalOrdersStats: true,
                            getPurchaseFunnelStats: true,
                            getAbandonedCartsStats: true,
                            getRefundsStats: true,
                            getTaxCollectedStats: true,
                            getShippingCollectedStats: true,
                            getTopPaymentMethods: true,
                        },

                        charts: {}
                    }
                },

                mounted() {
                    for (let key in this.isLoading) {
                        this.getStats(key);
                    }
                },

                methods: {
                    getStats(type) {
                        this.isLoading[type] = true;

                        this.$axios.get("{{ route('admin.reporting.sales.stats') }}", {
                                params: {
                                    type: type
                                }
                            })
                            .then(response => {
                                this.reports[type] = response.data;

                                if (
                                    [
                                        'getTotalSalesStats',
                                        'getAverageSalesStats',
                                        'getTotalOrdersStats',
                                        'getRefundsStats',
                                        'getTaxCollectedStats',
                                        'getShippingCollectedStats',
                                    ].includes(type)
                                ) {
                                    setTimeout(() => {
                                        this.prepareChart(type, response.data.statistics.over_time);
                                    }, 0);
                                }

                                this.isLoading[type] = false;
                            })
                            .catch(error => {});
                    },

                    prepareChart(type, stats) {
                        if (this.charts[type]) {
                           this.charts[type].destroy();
                        }

                        this.charts[type] = new Chart(document.getElementById(type), {
                            type: 'line',
                            
                            data: {
                                labels: stats['current']['label'],

                                datasets: [{
                                    data: stats['current']['total'],
                                    lineTension: 0.2,
                                    pointStyle: false,
                                    borderWidth: 2,
                                    borderColor: '#0E9CFF',
                                    backgroundColor: 'rgba(14, 156, 255, 0.3)',
                                    fill: true,
                                }, {
                                    data: stats['previous']['total'],
                                    lineTension: 0.2,
                                    pointStyle: false,
                                    borderWidth: 2,
                                    borderColor: '#34D399',
                                    backgroundColor: 'rgba(52, 211, 153, 0.3)',
                                    fill: true,
                                }],
                            },
                    
                            options: {
                                aspectRatio: 3.17,
                                
                                plugins: {
                                    legend: {
                                        display: false
                                    },

                                    {{-- tooltip: {
                                        enabled: false,
                                    } --}}
                                },
                                
                                scales: {
                                    x: {
                                        beginAtZero: true,

                                        border: {
                                            dash: [8, 4],
                                        }
                                    },

                                    y: {
                                        beginAtZero: true,
                                        border: {
                                            dash: [8, 4],
                                        }
                                    }
                                }
                            }
                        });
                    },

                    resetLoading() {
                        for (let key in this.isLoading) {
                            this.isLoading[key] = false;
                        }
                    }
                }
            });
        </script>
    @endPushOnce
</x-admin::layouts>

<!-- Todays Details Vue Component -->
<v-dashboard-todays-details>
    <!-- Shimmer -->
    <x-admin::shimmer.dashboard.todays-details />
</v-dashboard-todays-details>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-todays-details-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.dashboard.todays-details />
        </template>

        <!-- Total Sales Section -->
        <template v-else>
            <div class="rounded box-shadow">
                <div class="flex gap-4 flex-wrap p-4 bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                    <!-- Today's Sales -->
                    <div class="flex gap-2.5 flex-1">
                        <img
                            class="w-full h-[60px] max-w-[60px] max-h-[60px] dark:invert dark:mix-blend-exclusion"
                            src="{{ bagisto_asset('images/total-sales.svg')}}"
                            title="@lang('admin::app.dashboard.index.today-sales')"
                        >

                        <!-- Sales Stats -->
                        <div class="grid gap-1 place-content-start">
                            <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
                                @{{ report.statistics.total_sales.formatted_total }}
                            </p>

                            <p class="text-xs text-gray-600 dark:text-gray-300 font-semibold">
                                @lang('admin::app.dashboard.index.today-sales')
                            </p>

                            <!-- Percentage Of Sales -->
                            <div class="flex gap-0.5 items-center">
                                <span
                                    class="text-base  text-emerald-500"
                                    :class="[report.statistics.total_sales.progress < 0 ? 'icon-down-stat text-red-500 dark:!text-red-500' : 'icon-up-stat text-emerald-500 dark:!text-emerald-500']"
                                ></span>

                                <p
                                    class="text-xs text-emerald-500 font-semibold"
                                    :class="[report.statistics.total_sales.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                                >
                                    @{{ Math.abs(report.statistics.total_sales.progress.toFixed(2)) }}%
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Orders -->
                    <div class="flex gap-2.5 flex-1">
                        <img
                            class="w-full h-[60px] max-w-[60px] max-h-[60px] dark:invert dark:mix-blend-exclusion"
                            src="{{ bagisto_asset('images/total-orders.svg')}}"
                            title="@lang('admin::app.dashboard.index.today-orders')"
                        >

                        <!-- Orders Stats -->
                        <div class="grid gap-1 place-content-start">
                            <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
                                @{{ report.statistics.total_orders.current }}
                            </p>

                            <p class="text-xs text-gray-600 dark:text-gray-300 font-semibold">
                                @lang('admin::app.dashboard.index.today-orders')
                            </p>

                            <!-- Orders Percentage -->
                            <div class="flex gap-0.5 items-center">
                                <span
                                    class="text-base  text-emerald-500"
                                    :class="[report.statistics.total_orders.progress < 0 ? 'icon-down-stat text-red-500 dark:!text-red-500' : 'icon-up-stat text-emerald-500 dark:!text-emerald-500']"
                                ></span>

                                <p
                                    class="text-xs text-emerald-500 font-semibold"
                                    :class="[report.statistics.total_orders.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                                >
                                    @{{ Math.abs(report.statistics.total_orders.progress.toFixed(2)) }}%
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Customers -->
                    <div class="flex gap-2.5 flex-1">
                        <img
                            class="w-full h-[60px] max-w-[60px] max-h-[60px] dark:invert dark:mix-blend-exclusion"
                            src="{{ bagisto_asset('images/customers.svg')}}"
                            title="@lang('admin::app.dashboard.index.today-customers')"
                        >

                        <!-- Customers Stats -->
                        <div class="grid gap-1 place-content-start">
                            <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
                                @{{ report.statistics.total_customers.current }}
                            </p>

                            <p class="text-xs text-gray-600 dark:text-gray-300 font-semibold">
                                @lang('admin::app.dashboard.index.today-customers')
                            </p>

                            <!-- Customers Percentage -->
                            <div class="flex gap-0.5 items-center">
                                <span
                                    class="text-base  text-emerald-500"
                                    :class="[report.statistics.total_customers.progress < 0 ? 'icon-down-stat text-red-500 dark:!text-red-500' : 'icon-up-stat text-emerald-500 dark:!text-emerald-500']"
                                ></span>

                                <p
                                    class="text-xs text-emerald-500 font-semibold"
                                    :class="[report.statistics.total_customers.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                                >
                                    @{{ Math.abs(report.statistics.total_customers.progress.toFixed(2)) }}%
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today Orders Details -->
                <div 
                    v-for="order in report.statistics.orders"
                    class="p-4 bg-white dark:bg-gray-900 border-b dark:border-gray-800 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                >
                    <div class="flex gap-4 flex-wrap">
                        <!-- Total Sales -->
                        <div class="flex gap-2.5 flex-1 min-w-[180px]">
                            <div class="flex flex-col gap-1.5">
                                <!-- Order Id -->
                                <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
                                    @{{ "@lang('admin::app.dashboard.index.order-id', ['id' => ':replace'])".replace(':replace', order.increment_id) }}
                                </p>
    
                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ order.created_at}}
                                </p>
    
                                <!-- Order Status -->
                                <p :class="'label-' + order.status">
                                    @{{ order.status_label }}
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-2.5 flex-1 min-w-[180px]">
                            <div class="flex flex-col gap-1.5">
                                <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
                                    @{{ order.formatted_base_grand_total }}
                                </p>
        
                                <!-- Payment Mode -->
                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ order.payment_method }}
                                </p>
        
                                <!-- Channel Name -->
                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ order.channel_name }}
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-2.5 flex-1 min-w-[200px]">
                            <div class="flex flex-col gap-1.5">
                            <!-- Customer Detailes -->
                                <p class="text-base text-gray-800 dark:text-white">
                                    @{{ order.customer_name }}
                                </p>
        
                                <p class="text-gray-600 dark:text-gray-300 break-words max-w-[180px]">
                                    @{{ order.customer_email }}
                                </p>
        
                                <!-- Order Address -->
                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ order.billing_address }}
                                </p>
                            </div>
                        </div>
 
                        <div class="flex gap-2.5 flex-1 min-w-[180px] justify-between items-center">
                            <div class="flex flex-col gap-1.5">
                                <!-- Ordered Product Images -->
                                <div
                                    class="flex gap-1.5 flex-wrap"
                                    v-html="order.image"
                                >
                                </div>
                            </div>

                             <!-- View More Icon -->
                             <a :href="'{{ route('admin.sales.orders.view', ':replace') }}'.replace(':replace', order.id)">
                                <span class="icon-sort-right text-2xl ltr:ml-1 rtl:mr-1 p-1.5 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-md"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-todays-details', {
            template: '#v-dashboard-todays-details-template',

            data() {
                return {
                    report: [],

                    isLoading: true,
                }
            },

            mounted() {
                this.getStats({});
            },

            methods: {
                getStats(filtets) {
                    this.isLoading = true;

                    var filtets = Object.assign({}, filtets);

                    filtets.type = 'today';

                    this.$axios.get("{{ route('admin.dashboard.stats') }}", {
                            params: filtets
                        })
                        .then(response => {
                            this.report = response.data;

                            this.isLoading = false;
                        })
                        .catch(error => {});
                }
            }
        });
    </script>
@endPushOnce
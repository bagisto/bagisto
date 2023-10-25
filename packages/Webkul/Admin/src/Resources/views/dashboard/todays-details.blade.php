<!-- Todays Details Vue Component -->
<v-dashboard-todays-details>
    <!-- Shimmer -->
    <x-admin::shimmer.dashboard.todays-details/>
</v-dashboard-todays-details>

@pushOnce('scripts')
    <script type="text/x-template" id="v-dashboard-todays-details-template">
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.dashboard.todays-details/>
        </template>

        <!-- Total Sales Section -->
        <template v-else>
            <div class="rounded-[4px] box-shadow">
                <div class="flex gap-[16px] flex-wrap p-[16px] bg-white dark:bg-gray-900 border-b-[1px] dark:border-gray-800">
                    <!-- Today's Sales -->
                    <div class="flex gap-[10px] flex-1">
                        <img
                            class="w-full h-[60px] max-w-[60px] max-h-[60px] dark:invert dark:mix-blend-exclusion"
                            src="{{ bagisto_asset('images/total-sales.svg')}}"
                            title="@lang('admin::app.dashboard.index.today-sales')"
                        >

                        <!-- Sales Stats -->
                        <div class="grid gap-[4px] place-content-start">
                            <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                @{{ report.statistics.total_sales.formatted_total }}
                            </p>

                            <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                @lang('admin::app.dashboard.index.today-sales')
                            </p>

                            <!-- Percentage Of Sales -->
                            <div class="flex gap-[2px] items-center">
                                <span
                                    class="text-[16px] text-emerald-500"
                                    :class="[report.statistics.total_sales.progress < 0 ? 'icon-down-stat text-red-500 dark:!text-red-500' : 'icon-up-stat text-emerald-500 dark:!text-emerald-500']"
                                ></span>

                                <p
                                    class="text-[12px] text-emerald-500 font-semibold"
                                    :class="[report.statistics.total_sales.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                                >
                                    @{{ report.statistics.total_sales.progress.toFixed(2) }}%
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Orders -->
                    <div class="flex gap-[10px] flex-1">
                        <img
                            class="w-full h-[60px] max-w-[60px] max-h-[60px] dark:invert dark:mix-blend-exclusion"
                            src="{{ bagisto_asset('images/total-orders.svg')}}"
                            title="@lang('admin::app.dashboard.index.today-orders')"
                        >

                        <!-- Orders Stats -->
                        <div class="grid gap-[4px] place-content-start">
                            <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                @{{ report.statistics.total_orders.current }}
                            </p>

                            <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                @lang('admin::app.dashboard.index.today-orders')
                            </p>

                            <!-- Orders Percentage -->
                            <div class="flex gap-[2px] items-center">
                                <span
                                    class="text-[16px] text-emerald-500"
                                    :class="[report.statistics.total_orders.progress < 0 ? 'icon-down-stat text-red-500 dark:!text-red-500' : 'icon-up-stat text-emerald-500 dark:!text-emerald-500']"
                                ></span>

                                <p
                                    class="text-[12px] text-emerald-500 font-semibold"
                                    :class="[report.statistics.total_orders.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                                >
                                    @{{ report.statistics.total_orders.progress.toFixed(2) }}%
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Customers -->
                    <div class="flex gap-[10px] flex-1">
                        <img
                            class="w-full h-[60px] max-w-[60px] max-h-[60px] dark:invert dark:mix-blend-exclusion"
                            src="{{ bagisto_asset('images/customers.svg')}}"
                            title="@lang('admin::app.dashboard.index.today-customers')"
                        >

                        <!-- Customers Stats -->
                        <div class="grid gap-[4px] place-content-start">
                            <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                @{{ report.statistics.total_customers.current }}
                            </p>

                            <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                @lang('admin::app.dashboard.index.today-customers')
                            </p>

                            <!-- Customers Percentage -->
                            <div class="flex gap-[2px] items-center">
                                <span
                                    class="text-[16px] text-emerald-500"
                                    :class="[report.statistics.total_customers.progress < 0 ? 'icon-down-stat text-red-500 dark:!text-red-500' : 'icon-up-stat text-emerald-500 dark:!text-emerald-500']"
                                ></span>

                                <p
                                    class="text-[12px] text-emerald-500 font-semibold"
                                    :class="[report.statistics.total_customers.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                                >
                                    @{{ report.statistics.total_customers.progress.toFixed(2) }}%
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today Orders Details -->
                <div
                    class="row grid grid-cols-4 gap-y-[24px] p-[16px] bg-white dark:bg-gray-900 border-b-[1px] dark:border-gray-800 transition-all hover:bg-gray-50 dark:hover:bg-gray-950 max-1580:grid-cols-3 max-sm:grid-cols-1"
                    v-for="order in report.statistics.orders"
                >
                    <!-- Order ID, Status, Created -->
                    <div class="flex gap-[10px]">
                        <div class="flex flex-col gap-[6px]">
                            <!-- Order Id -->
                            <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
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

                    <!-- Payment And Channel Detailes -->
                    <div class="flex flex-col gap-[6px]">
                        <!-- Grand Total -->
                        <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
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

                    <div class="flex flex-col gap-[6px]">
                        <!-- Customer Detailes -->
                        <p class="text-[16px] text-gray-800 dark:text-white">
                            @{{ order.customer_name }}
                        </p>

                        <p class="text-gray-600 dark:text-gray-300">
                            @{{ order.customer_email }}
                        </p>

                        <!-- Order Address -->
                        <p class="text-gray-600 dark:text-gray-300">
                            @{{ order.billing_address }}
                        </p>
                    </div>

                    <!-- Ordered Product Images -->
                    <div class="max-1580:col-span-full">
                        <div class="flex gap-[6px] items-center justify-between">
                            <div
                                class="flex gap-[6px] items-center flex-wrap"
                                v-html="order.image"
                            >
                            </div>

                            <!-- View More Icon -->
                            <a :href="'{{ route('admin.sales.orders.view', ':replace') }}'.replace(':replace', order.id)">
                                <span class="icon-sort-right text-[24px] ltr:ml-[4px] rtl:mr-[4px] p-[6px] cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-[6px]"></span>
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
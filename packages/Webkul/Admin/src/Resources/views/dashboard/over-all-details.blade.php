<!-- Over Details Vue Component -->
<v-dashboard-overall-details>
    <!-- Shimmer -->
    <x-admin::shimmer.dashboard.over-all-details />
</v-dashboard-overall-details>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-overall-details-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.dashboard.over-all-details />
        </template>

        <!-- Total Sales Section -->
        <template v-else>
            <div class="p-4 rounded bg-white dark:bg-gray-900 box-shadow">
                <div class="flex gap-4 flex-wrap">
                    <!-- Total Sales -->
                    <div class="flex gap-2.5 flex-1 min-w-[200px]">
                        <div class="w-full h-[60px] max-w-[60px] max-h-[60px] dark:invert dark:mix-blend-exclusion">
                            <img
                                src="{{ bagisto_asset('images/total-sales.svg')}}"
                                title="@lang('admin::app.dashboard.index.total-sales')"
                            >
                        </div>

                        <!-- Sales Stats -->
                        <div class="grid gap-1 place-content-start">
                            <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
                                @{{ report.statistics.total_sales.formatted_total }}
                            </p>

                            <p class="text-xs text-gray-600 dark:text-gray-300 font-semibold">
                                @lang('admin::app.dashboard.index.total-sales')
                            </p>

                            <!-- Sales Percentage -->
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

                    <!-- Total Orders -->
                    <div class="flex gap-2.5 flex-1 min-w-[200px]">
                        <div class="w-full h-[60px] max-w-[60px] max-h-[60px] dark:invert dark:mix-blend-exclusion">
                            <img
                                src="{{ bagisto_asset('images/total-orders.svg')}}"
                                title="@lang('admin::app.dashboard.index.total-orders')"
                            >
                        </div>

                        <!-- Orders Stats -->
                        <div class="grid gap-1 place-content-start">
                            <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
                                @{{ report.statistics.total_orders.current }}
                            </p>

                            <p class="text-xs text-gray-600 dark:text-gray-300 font-semibold">
                                @lang('admin::app.dashboard.index.total-orders')
                            </p>

                            <!-- Order Percentage -->
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

                    <!-- Total Customers -->
                    <div class="flex gap-2.5 flex-1 min-w-[200px]">
                        <div class="w-full h-[60px] max-w-[60px] max-h-[60px] dark:invert dark:mix-blend-exclusion">
                            <img
                                src="{{ bagisto_asset('images/customers.svg')}}"
                                title="@lang('admin::app.dashboard.index.total-customers')"
                            >
                        </div>

                        <!-- Customers Stats -->
                        <div class="grid gap-1 place-content-start">
                            <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
                                @{{ report.statistics.total_customers.current }}
                            </p>

                            <p class="text-xs text-gray-600 dark:text-gray-300 font-semibold">
                                @lang('admin::app.dashboard.index.total-customers')
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

                    <!-- Average sales -->
                    <div class="flex gap-2.5 flex-1 min-w-[200px]">
                        <div class="w-full h-[60px] max-w-[60px] max-h-[60px] dark:invert dark:mix-blend-exclusion">
                            <img
                                src="{{ bagisto_asset('images/average-orders.svg')}}"
                                title="@lang('admin::app.dashboard.index.average-sale')"
                            >
                        </div>

                        <!-- Sales Stats -->
                        <div class="grid gap-1 place-content-start">
                            <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
                                @{{ report.statistics.avg_sales.formatted_total }}
                            </p>

                            <p class="text-xs text-gray-600 dark:text-gray-300 font-semibold">
                                @lang('admin::app.dashboard.index.average-sale')
                            </p>

                            <!-- Sales Percentage -->
                            <div class="flex gap-0.5 items-center">
                                <span
                                    class="text-base  text-emerald-500"
                                    :class="[report.statistics.avg_sales.progress < 0 ? 'icon-down-stat text-red-500 dark:!text-red-500' : 'icon-up-stat text-emerald-500 dark:!text-emerald-500']"
                                ></span>

                                <p
                                    class="text-xs font-semibold"
                                    :class="[report.statistics.avg_sales.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                                >
                                    @{{ Math.abs(report.statistics.avg_sales.progress).toFixed(2) }}%
                                </p>

                            </div>
                        </div>
                    </div>

                    <!-- Unpaid Invoices -->
                    <div class="flex gap-2.5 flex-1 min-w-[200px]">
                        <div class="w-full h-[60px] max-w-[60px] max-h-[60px] dark:invert dark:mix-blend-exclusion">
                            <img
                                src="{{ bagisto_asset('images/unpaid-invoices.svg')}}"
                                title="@lang('admin::app.dashboard.index.total-unpaid-invoices')"
                            >
                        </div>

                        <div class="grid gap-1 place-content-start">
                            <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
                                @{{ report.statistics.total_unpaid_invoices.formatted_total }}
                            </p>

                            <p class="text-xs text-gray-600 dark:text-gray-300 font-semibold">
                                @lang('admin::app.dashboard.index.total-unpaid-invoices')
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-overall-details', {
            template: '#v-dashboard-overall-details-template',

            data() {
                return {
                    report: [],

                    isLoading: true,
                }
            },

            mounted() {
                this.getStats({});

                this.$emitter.on('reporting-filter-updated', this.getStats);
            },

            methods: {
                getStats(filtets) {
                    this.isLoading = true;

                    var filtets = Object.assign({}, filtets);

                    filtets.type = 'over-all';

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
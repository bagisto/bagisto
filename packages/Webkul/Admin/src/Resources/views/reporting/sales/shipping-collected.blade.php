<!-- Shipping Collected Vue Component -->
<v-reporting-sales-shipping-collected>
    <!-- Shimmer -->
    <x-admin::shimmer.reporting.sales.shipping-collected />
</v-reporting-sales-shipping-collected>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-reporting-sales-shipping-collected-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.reporting.sales.shipping-collected />
        </template>

        <!-- Shipping Collected Section -->
        <template v-else>
            <div class="flex-1 relative p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <p class="text-base text-gray-600 dark:text-white font-semibold">
                        @lang('admin::app.reporting.sales.index.shipping-collected')
                    </p>

                    <a
                        href="{{ route('admin.reporting.sales.view', ['type' => 'shipping-collected']) }}"
                        class="text-sm text-blue-600 cursor-pointer transition-all hover:underline"
                    >
                        @lang('admin::app.reporting.sales.index.view-details')
                    </a>
                </div>

                <!-- Content -->
                <div class="grid gap-4">
                    <div class="flex gap-4 justify-between">
                        <p class="text-3xl text-gray-600 dark:text-gray-300 font-bold leading-9">
                            @{{ report.statistics.shipping_collected.formatted_total }}
                        </p>
                        
                        <div class="flex gap-0.5 items-center">
                            <span
                                class="text-base  text-emerald-500"
                                :class="[report.statistics.shipping_collected.progress < 0 ? 'icon-down-stat text-red-500 dark:!text-red-500' : 'icon-up-stat text-emerald-500 dark:!text-emerald-500']"
                            ></span>

                            <p
                                class="text-base  text-emerald-500"
                                :class="[report.statistics.shipping_collected.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                            >
                                @{{ Math.abs(report.statistics.shipping_collected.progress.toFixed(2)) }}%
                            </p>
                        </div>
                    </div>

                    <p class="text-base text-gray-600 dark:text-gray-300 font-semibold">
                        @lang('admin::app.reporting.sales.index.shipping-collected-over-time')
                    </p>

                    <!-- Line Chart -->
                    <x-admin::charts.line
                        ::labels="chartLabels"
                        ::datasets="chartDatasets"
                    />

                    <!-- Chart Date Range -->
                    <div class="flex gap-5 justify-center">
                        <div class="flex gap-1 items-center">
                            <span class="w-3.5 h-3.5 rounded-md bg-emerald-400"></span>

                            <p class="text-xs dark:text-gray-300">
                                @{{ report.date_range.previous }}
                            </p>
                        </div>

                        <div class="flex gap-1 items-center">
                            <span class="w-3.5 h-3.5 rounded-md bg-sky-400"></span>

                            <p class="text-xs dark:text-gray-300">
                                @{{ report.date_range.current }}
                            </p>
                        </div>
                    </div>

                    <!-- Top Shipping Methods -->
                    <p class="py-2.5 text-base  text-gray-600 dark:text-white font-semibold">
                        @lang('admin::app.reporting.sales.index.top-shipping-methods')
                    </p>

                    <!-- Methods -->
                    <template v-if="report.statistics.top_methods.length">
                        <div class="grid gap-7">
                            <div
                                class="grid"
                                v-for="method in report.statistics.top_methods"
                            >
                                <p class="dark:text-white">@{{ method.title }}</p>

                                <div class="flex gap-5 items-center">
                                    <div class="w-full h-2 relative bg-slate-100">
                                        <div
                                            class="h-2 absolute left-0 bg-emerald-500"
                                            :style="{ 'width': method.progress + '%' }"
                                        ></div>
                                    </div>

                                    <p class="text-sm text-gray-600 dark:text-gray-300 font-semibold">
                                        @{{ method.formatted_total }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Empty State -->
                    <template v-else>
                        @include('admin::reporting.empty')
                    </template>

                    <!-- Date Range -->
                    <div class="flex gap-5 justify-end">
                        <div class="flex gap-1 items-center">
                            <span class="w-3.5 h-3.5 rounded-md bg-emerald-400"></span>

                            <p class="text-xs dark:text-gray-300">
                                @{{ report.date_range.current }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-reporting-sales-shipping-collected', {
            template: '#v-reporting-sales-shipping-collected-template',

            data() {
                return {
                    report: [],

                    isLoading: true,
                }
            },

            computed: {
                chartLabels() {
                    return this.report.statistics.over_time.current.map(({ label }) => label);
                },

                chartDatasets() {
                    return [{
                        data: this.report.statistics.over_time.current.map(({ total }) => total),
                        lineTension: 0.2,
                        pointStyle: false,
                        borderWidth: 2,
                        borderColor: '#0E9CFF',
                        backgroundColor: 'rgba(14, 156, 255, 0.3)',
                        fill: true,
                    }, {
                        data: this.report.statistics.over_time.previous.map(({ total }) => total),
                        lineTension: 0.2,
                        pointStyle: false,
                        borderWidth: 2,
                        borderColor: '#34D399',
                        backgroundColor: 'rgba(52, 211, 153, 0.3)',
                        fill: true,
                    }];
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

                    filtets.type = 'shipping-collected';

                    this.$axios.get("{{ route('admin.reporting.sales.stats') }}", {
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
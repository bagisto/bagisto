<!-- Total Sales Vue Component -->
<v-dashboard-total-sales>
    <!-- Shimmer -->
    <x-admin::shimmer.dashboard.total-sales />
</v-dashboard-total-sales>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-total-sales-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.dashboard.total-sales />
        </template>

        <!-- Total Sales Section -->
        <template v-else>
            <div class="grid gap-4 px-4 py-2 border-b dark:border-gray-800">
                <div class="flex gap-2 justify-between">
                    <div class="flex flex-col gap-1 justify-between">
                        <p class="text-xs text-gray-600 dark:text-gray-300 font-semibold">
                            @lang('admin::app.dashboard.index.total-sales')
                        </p>

                        <!-- Total Order Revenue -->
                        <p class="text-lg text-gray-800 leading-none dark:text-white font-bold">
                            @{{ report.statistics.total_sales.formatted_total }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-1 justify-between">
                        <!-- Orders Time Duration -->
                        <p class="text-xs text-gray-400 font-semibold text-right dark:text-white">
                            @{{ report.date_range }}
                        </p>

                        <!-- Total Orders -->
                        <p class="text-xs text-gray-400 font-semibold text-right dark:text-white">
                            @{{ "@lang('admin::app.dashboard.index.order')".replace(':total_orders', report.statistics.total_orders.current ?? 0) }}
                        </p>
                    </div>
                </div>

                <!-- Bar Chart -->
                <x-admin::charts.bar
                    ::labels="chartLabels"
                    ::datasets="chartDatasets"
                    ::aspect-ratio="1.41"
                />
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-total-sales', {
            template: '#v-dashboard-total-sales-template',

            data() {
                return {
                    report: [],

                    isLoading: true,
                }
            },

            computed: {
                chartLabels() {
                    return this.report.statistics.over_time.map(({ label }) => label);
                },

                chartDatasets() {
                    return [{
                        data: this.report.statistics.over_time.map(({ total }) => total),
                        barThickness: 6,
                        backgroundColor: '#598de6',
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

                    filtets.type = 'total-sales';

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
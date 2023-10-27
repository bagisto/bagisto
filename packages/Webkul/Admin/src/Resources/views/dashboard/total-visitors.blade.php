<!-- Total Visitors Vue Component -->
<v-dashboard-total-visitors>
    <!-- Shimmer -->
    <x-admin::shimmer.dashboard.total-sales/>
</v-dashboard-total-visitors>

@pushOnce('scripts')
    <script type="text/x-template" id="v-dashboard-total-visitors-template">
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.dashboard.total-sales/>
        </template>

        <!-- Total Sales Section -->
        <template v-else>
            <div class="grid gap-[16px] px-[16px] py-[8px] border-b dark:border-gray-800">
                <div class="flex gap-[8px] justify-between">
                    <div class="flex flex-col gap-[4px] justify-between">
                        <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                            @lang('admin::app.dashboard.index.visitors')
                        </p>

                        <!-- Total Order Revenue -->
                        <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                            @{{ report.statistics.total.current }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-[4px] justify-between">
                        <!-- Orders Time Duration -->
                        <p class="text-[12px] text-gray-400 font-semibold text-right dark:text-white">
                            @{{ report.date_range }}
                        </p>

                        <!-- Total Orders -->
                        <p class="text-[12px] text-gray-400 font-semibold text-right dark:text-white">
                            @{{ "@lang('admin::app.dashboard.index.unique-visitors')".replace(':count', report.statistics.unique.current ?? 0) }}
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
        app.component('v-dashboard-total-visitors', {
            template: '#v-dashboard-total-visitors-template',

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
                        backgroundColor: '#f87171',
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

                    filtets.type = 'total-visitors';

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
{{-- Total Customer Vue Component --}}
<v-reporting-customers-total-traffic>
    {{-- Shimmer --}}
    <x-admin::shimmer.reporting.customers.total-traffic/> 
</v-reporting-customers-total-traffic>

@pushOnce('scripts')
    <script type="text/x-template" id="v-reporting-customers-total-traffic-template">
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.reporting.customers.total-traffic/> 
        </template>

        <!-- Total Customer Section -->
        <template v-else>
            <div class="flex-1 relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                <!-- Header -->
                <div class="flex items-center justify-between mb-[16px]">
                    <p class="text-[16px] text-gray-600 dark:text-white font-semibold">
                        @lang('admin::app.reporting.customers.index.customers-traffic')
                    </p>
                </div>
                
                <!-- Content -->
                <div class="grid gap-[16px]">
                    <div class="flex gap-[16px] justify-between">
                        <!-- Total Visitors -->
                        <div class="grid gap-[4px]">
                            <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                @{{ report.statistics.total.current }}
                            </p>

                            <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                @lang('admin::app.reporting.customers.index.total-visitors')
                            </p>
                            
                            <div class="flex gap-[2px] items-center">
                                <span
                                    class="text-[16px] text-emerald-500"
                                    :class="[report.statistics.total.progress < 0 ? 'icon-down-stat text-red-500 dark:!text-red-500' : 'icon-up-stat text-emerald-500 dark:!text-emerald-500']"
                                ></span>

                                <p
                                    class="text-[16px] text-emerald-500"
                                    :class="[report.statistics.total.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                                >
                                    @{{ report.statistics.total.progress.toFixed(2) }}%
                                </p>
                            </div>
                        </div>

                        <!-- Unique Visitors -->
                        <div class="grid gap-[4px]">
                            <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                @{{ report.statistics.unique.current }}
                            </p>

                            <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                @lang('admin::app.reporting.customers.index.unique-visitors')
                            </p>
                            
                            <div class="flex gap-[2px] items-center">
                                <span
                                    class="text-[16px] text-emerald-500"
                                    :class="[report.statistics.unique.progress < 0 ? 'icon-down-stat text-red-500 dark:!text-red-500' : 'icon-up-stat text-emerald-500 dark:!text-emerald-500']"
                                ></span>

                                <p
                                    class="text-[16px] text-emerald-500"
                                    :class="[report.statistics.unique.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                                >
                                    @{{ report.statistics.unique.progress.toFixed(2) }}%
                                </p>
                            </div>
                        </div>
                    </div>

                    <p class="text-[16px] text-gray-600 dark:text-gray-300 font-semibold">
                        @lang('admin::app.reporting.customers.index.traffic-over-week')
                    </p>

                    <!-- Bar Chart -->
                    <x-admin::charts.bar
                        ::labels="chartLabels"
                        ::datasets="chartDatasets"
                    />

                    <!-- Date Range -->
                    <div class="flex gap-[20px] justify-center">
                        <div class="flex gap-[4px] items-center">
                            <span class="w-[14px] h-[14px] rounded-[3px] bg-emerald-400"></span>

                            <p class="text-[12px] dark:text-gray-300">
                                @{{ report.date_range.previous }}
                            </p>
                        </div>

                        <div class="flex gap-[4px] items-center">
                            <span class="w-[14px] h-[14px] rounded-[3px] bg-sky-400"></span>

                            <p class="text-[12px] dark:text-gray-300">
                                @{{ report.date_range.current }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-reporting-customers-total-traffic', {
            template: '#v-reporting-customers-total-traffic-template',

            data() {
                return {
                    report: [],

                    isLoading: true,
                }
            },

            computed: {
                chartLabels() {
                    return this.report.statistics.over_time.previous['label'];
                },

                chartDatasets() {
                    return [{
                        data: this.report.statistics.over_time.previous['total'],
                        pointStyle: false,
                        backgroundColor: '#34D399',
                        fill: true,
                    }, {
                        data: this.report.statistics.over_time.current['total'],
                        pointStyle: false,
                        backgroundColor: '#0E9CFF',
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

                    filtets.type = 'customers-traffic';

                    this.$axios.get("{{ route('admin.reporting.customers.stats') }}", {
                            params: filtets
                        })
                        .then(response => {
                            this.report = response.data;

                            this.isLoading = false;
                        })
                        .catch(error => {});
                },
            }
        });
    </script>
@endPushOnce
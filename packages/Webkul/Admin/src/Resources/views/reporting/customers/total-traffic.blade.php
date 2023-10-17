{{-- Total Customer Vue Component --}}
<v-reporting-customers-total-traffic>
    <x-admin::shimmer.reporting.graph/> 
</v-reporting-customers-total-traffic>

@pushOnce('scripts')
    <script type="text/x-template" id="v-reporting-customers-total-traffic-template">
        <!-- Total Customer Section -->
        <div class="flex-1 relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
            <!-- Header -->
            <div class="flex items-center justify-between mb-[16px]">
                <p class="text-[16px] text-gray-600 dark:text-white font-semibold">
                    @lang('admin::app.reporting.customers.index.customers-traffic')
                </p>

                <a
                    href="#"
                    class="text-[14px] text-blue-600 cursor-pointer transition-all hover:underline"
                >
                    @lang('admin::app.reporting.customers.index.view-details')
                </a>
            </div>

            <template v-if="isLoading">
                <x-admin::shimmer.reporting.graph/> 
            </template>

            <template v-else>
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
                                    :class="[report.statistics.total.progress < 0 ? 'icon-down-stat text-red-500' : 'icon-up-stat text-emerald-500']"
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
                                    :class="[report.statistics.unique.progress < 0 ? 'icon-down-stat text-red-500' : 'icon-up-stat text-emerald-500']"
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

                    <!-- Line Chart -->
                    <canvas
                        id="getCustomersTrafficStats"
                        class="flex items-end w-full aspect-[3.23/1]"
                    ></canvas>

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
            </template>
        </div>
    </script>

    <script type="module">
        app.component('v-reporting-customers-total-traffic', {
            template: '#v-reporting-customers-total-traffic-template',

            data() {
                return {
                    report: [],

                    isLoading: true,

                    chart: undefined,
                }
            },

            mounted() {
                this.getStats();
            },

            methods: {
                getStats() {
                    this.isLoading = true;

                    this.$axios.get("{{ route('admin.reporting.sales.stats') }}", {
                            params: {
                                type: 'getCustomersTrafficStats'
                            }
                        })
                        .then(response => {
                            this.report = response.data;

                            setTimeout(() => {
                                this.prepareChart(response.data.statistics.over_time);
                            }, 0);

                            this.isLoading = false;
                        })
                        .catch(error => {});
                },

                prepareChart(stats) {
                    if (this.chart) {
                        this.chart.destroy();
                    }

                    this.chart = new Chart(document.getElementById('getCustomersTrafficStats'), {
                        type: 'bar',
                        
                        data: {
                            labels: stats['current']['label'],

                            datasets: [{
                                data: stats['previous']['total'],
                                pointStyle: false,
                                backgroundColor: '#34D399',
                                fill: true,
                            }, {
                                data: stats['current']['total'],
                                pointStyle: false,
                                backgroundColor: '#0E9CFF',
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
            }
        });
    </script>
@endPushOnce
<!-- Tax Collected Vue Component -->
<v-reporting-sales-tax-collected>
    <!-- Shimmer -->
    <x-admin::shimmer.reporting.sales.tax-collected />
</v-reporting-sales-tax-collected>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-reporting-sales-tax-collected-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.reporting.sales.tax-collected />
        </template>

        <!-- Tax Collected Section -->
        <template v-else>
            <div class="box-shadow relative flex-1 rounded bg-white p-4 dark:bg-gray-900">
                <!-- Header -->
                <div class="mb-4 flex items-center justify-between">
                    <p class="text-base font-semibold text-gray-600 dark:text-white">
                        @lang('admin::app.reporting.sales.index.tax-collected')
                    </p>

                    <a
                        href="{{ route('admin.reporting.sales.view', ['type' => 'tax-collected']) }}"
                        class="cursor-pointer text-sm text-blue-600 transition-all hover:underline"
                    >
                        @lang('admin::app.reporting.sales.index.view-details')
                    </a>
                </div>
                
                <!-- Content -->
                <div class="grid gap-4">
                    <div class="flex justify-between gap-4">
                        <p class="text-3xl font-bold leading-9 text-gray-600 dark:text-gray-300">
                            @{{ report.statistics.tax_collected.formatted_total }}
                        </p>
                        
                        <div class="flex items-center gap-0.5">
                            <span
                                class="text-base text-emerald-500"
                                :class="[report.statistics.tax_collected.progress < 0 ? 'icon-down-stat text-red-500 dark:!text-red-500' : 'icon-up-stat text-emerald-500 dark:!text-emerald-500']"
                            ></span>

                            <p
                                class="text-base text-emerald-500"
                                :class="[report.statistics.tax_collected.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                            >
                                @{{ Math.abs(report.statistics.tax_collected.progress.toFixed(2)) }}%
                            </p>
                        </div>
                    </div>

                    <p class="text-base font-semibold text-gray-600 dark:text-gray-300">
                        @lang('admin::app.reporting.sales.index.tax-collected-over-time')
                    </p>

                    <!-- Line Chart -->
                    <x-admin::charts.line
                        ::labels="chartLabels"
                        ::datasets="chartDatasets"
                    />

                    <!-- Date Range -->
                    <div class="flex justify-center gap-5">
                        <div class="flex items-center gap-1">
                            <span class="h-3.5 w-3.5 rounded-md bg-emerald-400"></span>

                            <p class="text-xs dark:text-gray-300">
                                @{{ report.date_range.previous }}
                            </p>
                        </div>

                        <div class="flex items-center gap-1">
                            <span class="h-3.5 w-3.5 rounded-md bg-sky-400"></span>

                            <p class="text-xs dark:text-gray-300">
                                @{{ report.date_range.current }}
                            </p>
                        </div>
                    </div>

                    <!-- Tax Categories -->
                    <p class="py-2.5 text-base font-semibold text-gray-600 dark:text-white">
                        @lang('admin::app.reporting.sales.index.top-tax-categories')
                    </p>

                    <!-- Categories -->
                    <template v-if="report.statistics.top_categories.length">
                        <div class="grid gap-7">
                            <div
                                class="grid"
                                v-for="category in report.statistics.top_categories"
                            >
                                <p class="dark:text-white">
                                    @{{ category.name }}
                                </p>

                                <div class="flex items-center gap-5">
                                    <div class="relative h-2 w-full bg-slate-100">
                                        <div
                                            class="absolute left-0 h-2 bg-blue-500"
                                            :style="{ 'width': category.progress + '%' }"
                                        ></div>
                                    </div>

                                    <p class="text-sm font-semibold text-gray-600 dark:text-gray-300">
                                        @{{ category.formatted_total }}
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
                    <div class="flex justify-end gap-5">
                        <div class="flex items-center gap-1">
                            <span class="h-3.5 w-3.5 rounded-md bg-sky-400"></span>

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
        app.component('v-reporting-sales-tax-collected', {
            template: '#v-reporting-sales-tax-collected-template',

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

                    filtets.type = 'tax-collected';

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
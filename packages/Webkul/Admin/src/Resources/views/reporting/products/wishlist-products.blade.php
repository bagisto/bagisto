<!-- Product Added to Wishlist Vue Component -->
<v-reporting-products-wishlist-products>
    <!-- Shimmer -->
    <x-admin::shimmer.reporting.products.wishlist-products />
</v-reporting-products-wishlist-products>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-reporting-products-wishlist-products-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.reporting.products.wishlist-products />
        </template>

        <!-- Product Added to Wishlist Section -->
        <template v-else>
            <div class="flex-1 relative p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <p class="text-base text-gray-600 dark:text-white font-semibold">
                        @lang('admin::app.reporting.products.index.total-products-added-to-wishlist')
                    </p>

                    <a
                        href="{{ route('admin.reporting.products.view', ['type' => 'total-products-added-to-wishlist']) }}"
                        class="text-sm text-blue-600 cursor-pointer transition-all hover:underline"
                    >
                        @lang('admin::app.reporting.products.index.view-details')
                    </a>
                </div>
                
                <!-- Content -->
                <div class="grid gap-4">
                    <div class="flex gap-4">
                        <p class="text-3xl text-gray-600 dark:text-gray-300 font-bold leading-9">
                            @{{ report.statistics.wishlist.current }}
                        </p>
                        
                        <div class="flex gap-0.5 items-center">
                            <span
                                class="text-base  text-emerald-500"
                                :class="[report.statistics.wishlist.progress < 0 ? 'icon-down-stat text-red-500 dark:!text-red-500' : 'icon-up-stat text-emerald-500 dark:!text-emerald-500']"
                            ></span>

                            <p
                                class="text-base  text-emerald-500"
                                :class="[report.statistics.wishlist.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                            >
                                @{{ report.statistics.wishlist.progress.toFixed(2) }}%
                            </p>
                        </div>
                    </div>

                    <p class="text-base text-gray-600 dark:text-gray-300 font-semibold">
                        @lang('admin::app.reporting.products.index.products-added-over-time')
                    </p>

                    <!-- Line Chart -->
                    <x-admin::charts.line
                        ::labels="chartLabels"
                        ::datasets="chartDatasets"
                    />

                    <!-- Date Range -->
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
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-reporting-products-wishlist-products', {
            template: '#v-reporting-products-wishlist-products-template',

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

                    filtets.type = 'total-products-added-to-wishlist';

                    this.$axios.get("{{ route('admin.reporting.products.stats') }}", {
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
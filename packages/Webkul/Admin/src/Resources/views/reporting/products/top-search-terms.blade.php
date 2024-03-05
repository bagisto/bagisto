<!-- Products with Most Reviews Vue Component -->
<v-reporting-products-top-search-terms>
    <!-- Shimmer -->
    <x-admin::shimmer.reporting.products.top-search-terms />
</v-reporting-products-top-search-terms>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-reporting-products-top-search-terms-template"
    >
        <template v-if="isLoading">
            <!-- Shimmer -->
            <x-admin::shimmer.reporting.products.top-search-terms />
        </template>

        <template v-else>
            <div class="flex-1 relative p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <p class="text-base text-gray-600 dark:text-white font-semibold">
                        @lang('admin::app.reporting.products.index.top-search-terms')
                    </p>
                </div>

                <!-- Content -->
                <div class="grid gap-4">
                    <template v-if="report.statistics.length">
                        <div class="table-responsive grid w-full rounded bg-white dark:bg-gray-900 overflow-hidden">
                            <!-- Table Header -->
                            <div
                                class="row grid grid-cols-4 grid-rows-1 gap-2.5 items-center px-4 py-2.5 border-b dark:border-gray-800 text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900 font-semibold"
                                style="grid-template-columns: repeat(3, minmax(0, 1fr));"
                            >
                                <div class="flex gap-2.5">
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.reporting.products.index.search-term')
                                    </p>
                                </div>

                                <div class="flex gap-2.5">
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.reporting.products.index.results')
                                    </p>
                                </div>

                                <div class="flex gap-2.5">
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.reporting.products.index.uses')
                                    </p>
                                </div>
                            </div>

                            <!-- Table Body -->
                            <div
                                class="row grid gap-2.5 items-center px-4 py-4 border-b dark:border-gray-800 text-gray-600 dark:text-gray-300 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                                style="grid-template-columns: repeat(3, minmax(0, 1fr));"
                                v-for="record in report.statistics"
                            >
                                <p>
                                    @{{ record.term }}
                                </p>

                                <p>
                                    @{{ record.results }}
                                </p>

                                <p>
                                    @{{ record.uses }}
                                </p>
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
        app.component('v-reporting-products-top-search-terms', {
            template: '#v-reporting-products-top-search-terms-template',

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

                    filtets.type = 'top-search-terms';

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
@endpushOnce
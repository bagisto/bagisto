<!-- Products with Most Reviews Vue Component -->
<v-reporting-products-with-most-reviews>
    <!-- Shimmer -->
    <x-admin::shimmer.reporting.products.most-reviews />
</v-reporting-products-with-most-reviews>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-reporting-products-with-most-reviews-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.reporting.products.most-reviews />
        </template>

        <!-- Products with Most Reviews Section -->
        <template v-else>
            <div class="flex-1 relative p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <p class="text-base text-gray-600 dark:text-white font-semibold">
                        @lang('admin::app.reporting.products.index.products-with-most-reviews')
                    </p>

                    <a
                        href="{{ route('admin.reporting.products.view', ['type' => 'products-with-most-reviews']) }}"
                        class="text-sm text-blue-600 cursor-pointer transition-all hover:underline"
                    >
                        @lang('admin::app.reporting.products.index.view-details')
                    </a>
                </div>

                <!-- Content -->
                <div class="grid gap-4">
                    <!-- Products with Most Reviews -->
                    <template v-if="report.statistics.length">
                        <!-- Products -->
                        <div class="grid gap-7">
                            <div
                                class="grid"
                                v-for="product in report.statistics"
                            >
                                <p class="dark:text-white">@{{ product.product_name }}</p>

                                <div class="flex gap-5 items-center">
                                    <div class="w-full h-2 relative bg-slate-100">
                                        <div
                                            class="h-2 absolute left-0 bg-emerald-500"
                                            :style="{ 'width': product.progress + '%' }"
                                        ></div>
                                    </div>

                                    <p class="text-sm text-gray-600 dark:text-gray-300 font-semibold">
                                        @{{ product.reviews }}
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
        app.component('v-reporting-products-with-most-reviews', {
            template: '#v-reporting-products-with-most-reviews-template',

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

                    filtets.type = 'products-with-most-reviews';

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
<!-- Stock Threshold Products Vue Component -->
<v-dashboard-stock-threshold-products>
    <!-- Shimmer -->
    <x-admin::shimmer.dashboard.stock-threshold-products />
</v-dashboard-stock-threshold-products>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-stock-threshold-products-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.dashboard.stock-threshold-products />
        </template>

        <!-- Total Sales Section -->
        <template v-else>
            <!-- Stock Threshold Products Details -->
            <div
                class="box-shadow rounded"
                v-if="report.statistics.length"
            >
                <!-- Single Product -->
                <div
                    class="relative"
                    v-for="product in report.statistics"
                >
                    <div class="row grid grid-cols-2 gap-y-6 border-b bg-white p-4 transition-all hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-950 max-sm:grid-cols-[1fr_auto]">
                        <div class="flex gap-2.5">
                            <template v-if="product.image">
                                <div class="">
                                    <img
                                        class="max-h-[65px] min-h-[65px] min-w-[65px] max-w-[65px] rounded"
                                        :src="product.image"
                                    >
                                </div>
                            </template>

                            <template v-else>
                                <div class="relative h-[65px] max-h-[65px] w-full max-w-[65px] overflow-hidden rounded border border-dashed border-gray-300 dark:border-gray-800 dark:mix-blend-exclusion dark:invert">
                                    <img src="{{ bagisto_asset('images/product-placeholders/front.svg')}}">

                                    <p class="absolute bottom-1.5 w-full text-center text-[6px] font-semibold text-gray-400">
                                        @lang('admin::app.dashboard.index.product-image')
                                    </p>
                                </div>
                            </template>

                            <div class="flex flex-col gap-1.5">
                                <!-- Product Name -->
                                <p class="text-base font-semibold text-gray-800 dark:text-white">
                                    @{{ product.name }}
                                </p>

                                <!-- Product SKU -->
                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ "@lang('admin::app.dashboard.index.sku', ['sku' => ':replace'])".replace(':replace', product.sku) }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-1.5">
                            <div class="flex flex-col gap-1.5">
                                <!-- Product Price -->
                                <p class="text-base font-semibold text-gray-800 dark:text-white">
                                    @{{ product.formatted_price }}
                                </p>

                                <!-- Total Product Stock -->
                                <p :class="[product.total_qty > {{ core()->getConfigData('catalog.inventory.stock_options.out_of_stock_threshold') }} ? 'text-emerald-500' : 'text-red-500']">
                                    @{{ "@lang('admin::app.dashboard.index.total-stock', ['total_stock' => ':replace'])".replace(':replace', product.total_qty) }}
                                </p>
                            </div>

                            <!-- View More Icon -->
                            <a :href="'{{ route('admin.catalog.products.edit', ':replace') }}'.replace(':replace', product.id)">
                                <span class="icon-sort-right rtl:icon-sort-left cursor-pointer p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty Product Design -->
            <div
                class="box-shadow rounded"
                v-else
            >
                <div class="grid justify-center justify-items-center gap-3.5 px-2.5 py-10">
                    <img src="{{ bagisto_asset('images/icon-add-product.svg') }}" class="h-20 w-20 dark:mix-blend-exclusion dark:invert">
                    
                    <div class="flex flex-col items-center">
                        <p class="text-base font-semibold text-gray-400">
                            @lang('admin::app.dashboard.index.empty-threshold')
                        </p>
    
                        <p class="text-gray-400">
                            @lang('admin::app.dashboard.index.empty-threshold-description')
                        </p>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-stock-threshold-products', {
            template: '#v-dashboard-stock-threshold-products-template',

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

                    filtets.type = 'stock-threshold-products';

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
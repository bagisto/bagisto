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
            <!-- Stock Threshold Products Detailes -->
            <div
                class="rounded box-shadow"
                v-if="report.statistics.length"
            >
                <!-- Single Product -->
                <div
                    class="relative"
                    v-for="product in report.statistics"
                >
                    <div class="row grid grid-cols-2 gap-y-6 p-4 bg-white dark:bg-gray-900 border-b dark:border-gray-800 transition-all hover:bg-gray-50 dark:hover:bg-gray-950 max-sm:grid-cols-[1fr_auto]">
                        <div class="flex gap-2.5">
                            <template v-if="product.image">
                                <div class="">
                                    <img
                                        class="min-h-[65px] min-w-[65px] max-h-[65px] max-w-[65px] rounded"
                                        :src="product.image"
                                    >
                                </div>
                            </template>

                            <template v-else>
                                <div class="w-full h-[65px] max-w-[65px] max-h-[65px] relative border border-dashed border-gray-300 dark:border-gray-800 rounded overflow-hidden dark:invert dark:mix-blend-exclusion">
                                    <img src="{{ bagisto_asset('images/product-placeholders/front.svg')}}">

                                    <p class="w-full absolute bottom-1.5 text-[6px] text-gray-400 text-center font-semibold">
                                        @lang('admin::app.dashboard.index.product-image')
                                    </p>
                                </div>
                            </template>

                            <div class="flex flex-col gap-1.5">
                                <!-- Product Name -->
                                <p class="text-base text-gray-800 dark:text-white font-semibold">
                                    @{{ product.name }}
                                </p>

                                <!-- Product SKU -->
                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ "@lang('admin::app.dashboard.index.sku', ['sku' => ':replace'])".replace(':replace', product.sku) }}
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-1.5 items-center justify-between">
                            <div class="flex flex-col gap-1.5">
                                <!-- Product Price -->
                                <p class="text-base text-gray-800 dark:text-white font-semibold">
                                    @{{ product.formatted_price }}
                                </p>

                                <!-- Total Product Stock -->
                                <p :class="[product.ttal_qty > 10 ? 'text-emerald-500' : 'text-red-500']">
                                    @{{ "@lang('admin::app.dashboard.index.total-stock', ['total_stock' => ':replace'])".replace(':replace', product.total_qty) }}
                                </p>
                            </div>

                            <!-- View More Icon -->
                            <a :href="'{{ route('admin.catalog.products.edit', ':replace') }}'.replace(':replace', product.id)">
                                <span class="icon-sort-right text-2xl ltr:ml-1 rtl:mr-1 p-1.5 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-md"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty Product Design -->
            <div
                class="rounded box-shadow"
                v-else
            >
                <div class="grid gap-3.5 justify-center justify-items-center py-10 px-2.5 ">
                    <img src="{{ bagisto_asset('images/icon-add-product.svg') }}" class="w-20 h-20 dark:invert dark:mix-blend-exclusion">
                    
                    <div class="flex flex-col items-center">
                        <p class="text-base text-gray-400 font-semibold">
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
<!-- Top Selling Products Vue Component -->
<v-dashboard-top-selling-products>
    <!-- Shimmer -->
    <x-admin::shimmer.dashboard.top-selling-products/>
</v-dashboard-top-selling-products>

@pushOnce('scripts')
    <script type="text/x-template" id="v-dashboard-top-selling-products-template">
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.dashboard.top-selling-products/>
        </template>

        <!-- Total Sales Section -->
        <template v-else>
            <div class="border-b dark:border-gray-800">
                <div class="flex items-center justify-between p-[16px]">
                    <p class="text-gray-600 dark:text-gray-300 text-[16px] font-semibold">
                        @lang('admin::app.dashboard.index.top-selling-products')
                    </p>

                    <p class="text-[12px] text-gray-400 font-semibold">
                        @{{ report.date_range }}
                    </p>
                </div>

                <!-- Top Selling Products Detailes -->
                <div
                    class="flex flex-col"
                    v-if="report.statistics.length"
                >
                    <a
                        :href="`{{route('admin.catalog.products.edit', '')}}/${item.product_id}`"
                        class="flex gap-[10px] p-[16px] border-b-[1px] dark:border-gray-800 last:border-b-0 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                        v-for="item in report.statistics"
                    >
                        <!-- Product Item -->
                        <img
                            v-if="item.product?.images.length"
                            class="w-full h-[65px] max-w-[65px] max-h-[65px] relative rounded-[4px] overflow-hidden"
                            :src="item.product?.images[0]?.url"
                        />

                        <div
                            v-else
                            class="w-full h-[65px] max-w-[65px] max-h-[65px] relative border border-dashed border-gray-300 dark:border-gray-800 rounded-[4px] overflow-hidden dark:invert dark:mix-blend-exclusion"
                        >
                            <img src="{{ bagisto_asset('images/product-placeholders/front.svg')}}">
                            
                            <p class="w-full absolute bottom-[5px] text-[6px] text-gray-400 text-center font-semibold">
                                @lang('admin::app.dashboard.index.product-image')
                            </p>
                        </div>

                        <!-- Product Detailes -->
                        <div class="flex flex-col gap-[6px] w-full">
                            <p
                                class="text-gray-600 dark:text-gray-300"
                                v-text="item.name"
                            >
                            </p>

                            <div class="flex justify-between">
                                <p
                                    class="text-gray-600 dark:text-gray-300 font-semibold"
                                    v-text="item.formatted_price"
                                >
                                </p>

                                <p
                                    class="text-[16px] text-gray-800 dark:text-white font-semibold"
                                    v-text="item.formatted_revenue"
                                >
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Empty Product Design -->
                <div
                    class="flex flex-col gap-[32px] p-[16px]"
                    v-else
                >
                    <div class="grid gap-[14px] justify-center justify-items-center py-[10px]">
                        <!-- Placeholder Image -->
                        <img
                            src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                            class="w-[80px] h-[80px] dark:invert dark:mix-blend-exclusion"
                        >

                        <!-- Add Variants Information -->
                        <div class="flex flex-col items-center">
                            <p class="text-[16px] text-gray-400 font-semibold">
                                @lang('admin::app.dashboard.index.add-product')
                            </p>

                            <p class="text-gray-400">
                                @lang('admin::app.dashboard.index.product-info')
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-top-selling-products', {
            template: '#v-dashboard-top-selling-products-template',

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

                    filtets.type = 'top-selling-products';

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
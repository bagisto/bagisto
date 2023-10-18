{{-- Products with Most Reviews Vue Component --}}
<v-reporting-products-with-most-reviews>
    <x-admin::shimmer.reporting.progress-bar/>
</v-reporting-products-with-most-reviews>

@pushOnce('scripts')
    <script type="text/x-template" id="v-reporting-products-with-most-reviews-template">
        <!-- Products with Most Reviews Section -->
        <div class="flex-1 relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
            <!-- Header -->
            <div class="flex items-center justify-between mb-[16px]">
                <p class="text-[16px] text-gray-600 dark:text-white font-semibold">
                    @lang('admin::app.reporting.products.index.products-with-most-reviews')
                </p>

                <a
                    href="{{ route('admin.reporting.products.view', ['type' => 'products-with-most-reviews']) }}"
                    class="text-[14px] text-blue-600 cursor-pointer transition-all hover:underline"
                >
                    @lang('admin::app.reporting.products.index.view-details')
                </a>
            </div>

            <template v-if="isLoading">
                <x-admin::shimmer.reporting.progress-bar/>
            </template>

            <template v-else>
                <!-- Content -->
                <div class="grid gap-[16px]">
                    <!-- Products with Most Reviews -->
                    <template v-if="report.statistics">
                        <!-- Customers -->
                        <div class="grid gap-[27px]">
                            <div
                                class="grid"
                                v-for="product in report.statistics"
                            >
                                <p class="dark:text-white">@{{ product.product_name }}</p>

                                <div class="flex gap-[20px] items-center">
                                    <div class="w-full h-[8px] relative bg-slate-100">
                                        <div
                                            class="h-[8px] absolute left-0 bg-emerald-500"
                                            :style="{ 'width': product.progress + '%' }"
                                        ></div>
                                    </div>

                                    <p class="text-[14px] text-gray-600 dark:text-gray-300 font-semibold">
                                        @{{ product.reviews }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Date Range -->
                    <div class="flex gap-[20px] justify-end">
                        <div class="flex gap-[4px] items-center">
                            <span class="w-[14px] h-[14px] rounded-[3px] bg-emerald-400"></span>

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
        app.component('v-reporting-products-with-most-reviews', {
            template: '#v-reporting-products-with-most-reviews-template',

            data() {
                return {
                    report: [],

                    isLoading: true,
                }
            },

            mounted() {
                this.getStats();
            },

            methods: {
                getStats() {
                    this.isLoading = true;

                    this.$axios.get("{{ route('admin.reporting.products.stats') }}", {
                            params: {
                                type: 'products-with-most-reviews'
                            }
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
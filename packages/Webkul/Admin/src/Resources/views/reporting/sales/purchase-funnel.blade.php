{{-- Purchase Funnel Vue Component --}}
<v-reporting-sales-purchase-funnel>
    {{-- Shimmer --}}
    <x-admin::shimmer.reporting.sales.purchase-funnel/>
</v-reporting-sales-purchase-funnel>

@pushOnce('scripts')
    <script type="text/x-template" id="v-reporting-sales-purchase-funnel-template">
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.reporting.sales.purchase-funnel/>
        </template>

        <!-- Purchase Funnel Section -->
        <template v-else>
            <div class="flex-1 relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                <!-- Header -->
                <p class="text-[16px] text-gray-600 dark:text-white font-semibold mb-[16px]">
                    @lang('admin::app.reporting.sales.index.purchase-funnel')
                </p>
                
                <!-- Content -->
                <div class="grid grid-cols-4 gap-[24px]">
                    <!-- Total Visits -->
                    <div class="flex flex-col gap-[16px]">
                        <div class="grid gap-[2px]">
                            <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                @{{ report.statistics.visitors.total }}
                            </p>

                            <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                @lang('admin::app.reporting.sales.index.total-visits')
                            </p>
                        </div>

                        <div class="w-full relative bg-slate-100 aspect-[0.5/1]">
                            <div
                                class="w-full absolute bottom-0 bg-emerald-400"
                                :style="{ 'height': report.statistics.visitors.progress + '%' }"
                            ></div>
                        </div>

                        <p class="text-gray-600 dark:text-gray-300">
                            @lang('admin::app.reporting.sales.index.total-visits-info')
                        </p>
                    </div>

                    <!-- Total Product Visits -->
                    <div class="flex flex-col gap-[16px]">
                        <div class="grid gap-[2px]">
                            <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                @{{ report.statistics.product_visitors.total }}
                            </p>

                            <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                @lang('admin::app.reporting.sales.index.product-views')
                            </p>
                        </div>

                        <div class="w-full relative bg-slate-100 aspect-[0.5/1]">
                            <div
                                class="w-full absolute bottom-0 bg-emerald-400"
                                :style="{ 'height': (report.statistics.product_visitors.progress).toFixed(2) + '%' }"
                            ></div>
                        </div>

                        <p
                            class="text-gray-600 dark:text-gray-300"
                            v-html="'@lang('admin::app.reporting.sales.index.product-views-info')'.replace(':progress', '<span class=\'text-emerald-400 font-semibold\'>' + report.statistics.product_visitors.progress + '%</span>')"
                        ></p>
                    </div>

                    <!-- Total Added To Cart -->
                    <div class="flex flex-col gap-[16px]">
                        <div class="grid gap-[2px]">
                            <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                @{{ report.statistics.carts.total }}
                            </p>

                            <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                @lang('admin::app.reporting.sales.index.added-to-cart')
                            </p>
                        </div>

                        <div class="w-full relative bg-slate-100 aspect-[0.5/1]">
                            <div
                                class="w-full absolute bottom-0 bg-emerald-400"
                                :style="{ 'height': (report.statistics.carts.progress).toFixed(2) + '%' }"
                            ></div>
                        </div>

                        <p
                            class="text-gray-600 dark:text-gray-300"
                            v-html="'@lang('admin::app.reporting.sales.index.added-to-cart-info')'.replace(':progress', '<span class=\'text-emerald-400 font-semibold\'>' + report.statistics.carts.progress + '%</span>')"
                        ></p>
                    </div>

                    <!-- Total Purchased -->
                    <div class="flex flex-col gap-[16px]">
                        <div class="grid gap-[2px]">
                            <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                @{{ report.statistics.orders.total }}
                            </p>

                            <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold">
                                @lang('admin::app.reporting.sales.index.purchased')
                            </p>
                        </div>

                        <div class="w-full relative bg-slate-100 dark:text-gray-300 aspect-[0.5/1]">
                            <div
                                class="w-full absolute bottom-0 bg-emerald-400"
                                :style="{ 'height': report.statistics.orders.progress + '%' }"
                            ></div>
                        </div>

                        <p
                            class="text-gray-600 dark:text-gray-300"
                            v-html="'@lang('admin::app.reporting.sales.index.purchased-info')'.replace(':progress', '<span class=\'text-emerald-400 font-semibold\'>' + report.statistics.orders.progress + '%</span>')"
                        ></p>
                    </div>
                </div>

                <!-- Date Range Section -->
                <div class="flex gap-[20px] justify-end mt-[24px]">
                    <div class="flex gap-[4px] items-center">
                        <span class="w-[14px] h-[14px] rounded-[3px] bg-emerald-400"></span>

                        <p class="text-[12px] dark:text-gray-300">
                            @{{ report.date_range.current }}
                        </p>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-reporting-sales-purchase-funnel', {
            template: '#v-reporting-sales-purchase-funnel-template',

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

                    filtets.type = 'purchase-funnel';

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
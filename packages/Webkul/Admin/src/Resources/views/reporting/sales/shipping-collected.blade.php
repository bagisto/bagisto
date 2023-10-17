{{-- Shipping Collected Vue Component --}}
<v-reporting-sales-shipping-collected>
    <x-admin::shimmer.reporting.graph/>

    <div class="shimmer w-[150px] h-[17px] mb-[16px]"></div>

    <x-admin::shimmer.reporting.progress-bar/>
</v-reporting-sales-shipping-collected>

@pushOnce('scripts')
    <script type="text/x-template" id="v-reporting-sales-shipping-collected-template">
        <!-- Shipping Collected Section -->
        <div class="flex-1 relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
            <!-- Header -->
            <div class="flex items-center justify-between mb-[16px]">
                <p class="text-[16px] text-gray-600 dark:text-white font-semibold">
                    @lang('admin::app.reporting.sales.index.shipping-collected')
                </p>

                <a
                    href="#"
                    class="text-[14px] text-blue-600 cursor-pointer transition-all hover:underline"
                >
                    @lang('admin::app.reporting.sales.index.view-details')
                </a>
            </div>

            <template v-if="isLoading">
                <div class="grid gap-[16px]">
                    <x-admin::shimmer.reporting.graph/>

                    <div class="shimmer w-[150px] h-[17px] mb-[16px]"></div>

                    <x-admin::shimmer.reporting.progress-bar/>
                </div>
            </template>

            <template v-else>
                <!-- Content -->
                <div class="grid gap-[16px]">
                    <div class="flex gap-[16px] justify-between">
                        <p class="text-[30px] text-gray-600 dark:text-gray-300 font-bold leading-9">
                            @{{ report.statistics.shipping_collected.formatted_total }}
                        </p>
                        
                        <div class="flex gap-[2px] items-center">
                            <span
                                class="text-[16px] text-emerald-500"
                                :class="[report.statistics.shipping_collected.progress < 0 ? 'icon-down-stat text-red-500 dark:!text-red-500' : 'icon-up-stat text-emerald-500 dark:!text-emerald-500']"
                            ></span>

                            <p
                                class="text-[16px] text-emerald-500"
                                :class="[report.statistics.shipping_collected.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                            >
                                @{{ report.statistics.shipping_collected.progress.toFixed(2) }}%
                            </p>
                        </div>
                    </div>

                    <p class="text-[16px] text-gray-600 dark:text-gray-300 font-semibold">
                        @lang('admin::app.reporting.sales.index.shipping-collected-over-time')
                    </p>

                    <!-- Line Chart -->
                    <canvas
                        id="getShippingCollectedStats"
                        class="flex items-end w-full aspect-[3.23/1]"
                    ></canvas>

                    <!-- Chart Date Range -->
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

                    <!-- Top Shipping Methods -->
                    <template v-if="report.statistics.top_methods.length">
                        <!-- Header -->
                        <p class="py-[10px] text-[16px] text-gray-600 dark:text-white font-semibold">
                            @lang('admin::app.reporting.sales.index.top-shipping-methods')
                        </p>

                        <!-- Methods -->
                        <div class="grid gap-[27px]">
                            <div
                                class="grid"
                                v-for="method in report.statistics.top_methods"
                            >
                                <p class="dark:text-white">@{{ method.title }}</p>

                                <div class="flex gap-[20px] items-center">
                                    <div class="w-full h-[8px] relative bg-slate-100">
                                        <div
                                            class="h-[8px] absolute left-0 bg-emerald-500"
                                            :style="{ 'width': method.progress + '%' }"
                                        ></div>
                                    </div>

                                    <p class="text-[14px] text-gray-600 dark:text-gray-300 font-semibold">
                                        @{{ method.formatted_total }}
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
        app.component('v-reporting-sales-shipping-collected', {
            template: '#v-reporting-sales-shipping-collected-template',

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

                    this.$axios.get("{{ route('admin.reporting.sales.stats') }}", {
                            params: {
                                type: 'getShippingCollectedStats'
                            }
                        })
                        .then(response => {
                            this.report = response.data;

                            setTimeout(() => {
                                this.$parent.prepareChart('getShippingCollectedStats', response.data.statistics.over_time);
                            }, 0);

                            this.isLoading = false;
                        })
                        .catch(error => {});
                }
            }
        });
    </script>
@endPushOnce
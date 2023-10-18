{{-- Average Order Value Vue Component --}}
<v-reporting-sales-average-order-value>
    <x-admin::shimmer.reporting.graph/> 
</v-reporting-sales-average-order-value>

@pushOnce('scripts')
    <script type="text/x-template" id="v-reporting-sales-average-order-value-template">
        <!-- Average Order Value Section -->
        <div class="flex-1 relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
            <!-- Header -->
            <div class="flex items-center justify-between mb-[16px]">
                <p class="text-[16px] text-gray-600 dark:text-white font-semibold">
                    @lang('admin::app.reporting.sales.index.average-sales')
                </p>

                <a
                    href="{{ route('admin.reporting.sales.view', ['type' => 'average-sales']) }}"
                    class="text-[14px] text-blue-600 cursor-pointer transition-all hover:underline"
                >
                    @lang('admin::app.reporting.sales.index.view-details')
                </a>
            </div>

            <template v-if="isLoading">
                <x-admin::shimmer.reporting.graph/>
            </template>

            <template v-else>
                <!-- Content -->
                <div class="grid gap-[16px]">
                    <div class="flex gap-[16px] justify-between">
                        <p class="text-[30px] text-gray-600 dark:text-gray-300 font-bold leading-9">
                            @{{ report.statistics.sales.formatted_total }}
                        </p>
                        
                        <div class="flex gap-[2px] items-center">
                            <span
                                class="text-[16px] text-emerald-500"
                                :class="[report.statistics.sales.progress < 0 ? 'icon-down-stat text-red-500 dark:!text-red-500' : 'icon-up-stat text-emerald-500 dark:!text-emerald-500']"
                            ></span>

                            <p
                                class="text-[16px] text-emerald-500"
                                :class="[report.statistics.sales.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                            >
                                @{{ report.statistics.sales.progress.toFixed(2) }}%
                            </p>
                        </div>
                    </div>

                    <p class="text-[16px] text-gray-600 dark:text-gray-300 font-semibold">
                        @lang('admin::app.reporting.sales.index.average-order-value-over-time')
                    </p>

                    <!-- Line Chart -->
                    <canvas
                        id="average-sales"
                        class="flex items-end w-full aspect-[3.23/1]"
                    ></canvas>

                    <!-- Date Range -->
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
                </div>
            </template>
        </div>
    </script>

    <script type="module">
        app.component('v-reporting-sales-average-order-value', {
            template: '#v-reporting-sales-average-order-value-template',

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
                                type: 'average-sales'
                            }
                        })
                        .then(response => {
                            this.report = response.data;

                            setTimeout(() => {
                                this.$parent.prepareChart('average-sales', response.data.statistics.over_time);
                            }, 0);

                            this.isLoading = false;
                        })
                        .catch(error => {});
                }
            }
        });
    </script>
@endPushOnce
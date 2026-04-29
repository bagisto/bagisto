<!-- Sales By Coupon Vue Component -->
<v-reporting-sales-by-coupon>
    <!-- Shimmer -->
    <x-admin::shimmer.reporting.sales.sales-by-coupon />
</v-reporting-sales-by-coupon>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-reporting-sales-by-coupon-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.reporting.sales.sales-by-coupon />
        </template>

        <!-- Sales By Coupon Section -->
        <template v-else>
            <div class="box-shadow relative flex-1 rounded bg-white p-4 dark:bg-gray-900">
                <!-- Header -->
                <div class="mb-4 flex items-center justify-between">
                    <p class="text-base font-semibold text-gray-600 dark:text-white">
                        @lang('admin::app.reporting.sales.index.sales-by-coupon')
                    </p>

                    <a
                        href="{{ route('admin.reporting.sales.view', ['type' => 'sales-by-coupon']) }}"
                        class="cursor-pointer text-sm text-blue-600 transition-all hover:underline"
                    >
                        @lang('admin::app.reporting.sales.index.view-details')
                    </a>
                </div>

                <!-- Content -->
                <div class="grid gap-4">
                    <!-- Top Coupons -->
                    <template v-if="report.statistics.length">
                        <!-- Coupons -->
                        <div class="grid gap-7">
                            <div
                                class="grid gap-1.5"
                                v-for="coupon in report.statistics"
                            >
                                <div class="flex items-center gap-2">
                                    <a
                                        v-if="coupon.link"
                                        :href="coupon.link"
                                        class="rounded bg-emerald-100 px-2 py-0.5 font-mono text-xs font-semibold text-emerald-700 transition-all hover:bg-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-300 dark:hover:bg-emerald-900/60"
                                    >
                                        @{{ coupon.coupon_code }}
                                    </a>

                                    <span
                                        v-else
                                        class="rounded bg-gray-100 px-2 py-0.5 font-mono text-xs font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-300"
                                    >
                                        @{{ coupon.coupon_code }}
                                    </span>

                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        @{{ coupon.total }} @lang('admin::app.reporting.sales.index.orders')
                                    </span>
                                </div>

                                <div class="flex items-center gap-5">
                                    <div class="relative h-2 w-full bg-slate-100">
                                        <div
                                            class="absolute left-0 h-2 bg-emerald-500"
                                            :style="{ 'width': coupon.progress + '%' }"
                                        ></div>
                                    </div>

                                    <p class="text-sm font-semibold text-gray-600 dark:text-gray-300">
                                        @{{ coupon.formatted_discount_total }}
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
                    <div class="flex justify-end gap-5">
                        <div class="flex items-center gap-1">
                            <span class="h-3.5 w-3.5 rounded-md bg-emerald-400"></span>

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
        app.component('v-reporting-sales-by-coupon', {
            template: '#v-reporting-sales-by-coupon-template',

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
                getStats(filters) {
                    this.isLoading = true;

                    var filters = Object.assign({}, filters);

                    filters.type = 'sales-by-coupon';

                    this.$axios.get("{{ route('admin.reporting.sales.stats') }}", {
                            params: filters
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

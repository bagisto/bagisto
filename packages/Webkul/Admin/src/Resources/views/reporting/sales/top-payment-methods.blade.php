<!-- Top Payment Methods Vue Component -->
<v-reporting-sales-top-payment-methods>
    <!-- Shimmer -->
    <x-admin::shimmer.reporting.sales.top-payment-methods />
</v-reporting-sales-top-payment-methods>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-reporting-sales-top-payment-methods-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.reporting.sales.top-payment-methods />
        </template>

        <!-- Top Payment Methods Section -->
        <template v-else>
            <div class="box-shadow relative flex-1 rounded bg-white p-4 dark:bg-gray-900">
                <!-- Header -->
                <div class="mb-4 flex items-center justify-between">
                    <p class="text-base font-semibold text-gray-600 dark:text-white">
                        @lang('admin::app.reporting.sales.index.top-payment-methods')
                    </p>

                    <a
                        href="{{ route('admin.reporting.sales.view', ['type' => 'top-payment-methods']) }}"
                        class="cursor-pointer text-sm text-blue-600 transition-all hover:underline"
                    >
                        @lang('admin::app.reporting.sales.index.view-details')
                    </a>
                </div>
                
                <!-- Content -->
                <div class="grid gap-4">
                    <!-- Top Payment Methods -->
                    <template v-if="report.statistics.length">
                        <!-- Payment Methods -->
                        <div class="grid gap-7">
                            <div
                                class="grid"
                                v-for="method in report.statistics"
                            >
                                <p class="dark:text-white">
                                    @{{ method.title }}
                                </p>

                                <div class="flex items-center gap-5">
                                    <div class="relative h-2 w-full bg-slate-100">
                                        <div
                                            class="absolute left-0 h-2 bg-emerald-500"
                                            :style="{ 'width': method.progress + '%' }"
                                        ></div>
                                    </div>

                                    <p class="text-sm font-semibold text-gray-600 dark:text-gray-300">
                                        @{{ method.formatted_total }}
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
        app.component('v-reporting-sales-top-payment-methods', {
            template: '#v-reporting-sales-top-payment-methods-template',

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

                    filters.type = 'top-payment-methods';

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
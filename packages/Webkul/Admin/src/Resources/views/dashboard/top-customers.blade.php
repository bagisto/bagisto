<!-- Top Selling Products Vue Component -->
<v-dashboard-top-customers>
    <!-- Shimmer -->
    <x-admin::shimmer.dashboard.top-customers />
</v-dashboard-top-customers>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-top-customers-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.dashboard.top-customers />
        </template>

        <!-- Total Sales Section -->
        <template v-else>
            <div class="border-b dark:border-gray-800">
                <div class="flex items-center justify-between p-4">
                    <p class="text-base font-semibold text-gray-600 dark:text-gray-300">
                        @lang('admin::app.dashboard.index.customer-with-most-sales')
                    </p>

                    <p class="text-xs font-semibold text-gray-400">
                        @{{ report.date_range }}
                    </p>
                </div>

                <div
                    class="flex flex-col gap-8 border-b p-4 transition-all last:border-b-0 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-950"
                    v-if="report.statistics.length"
                    v-for="customer in report.statistics"
                >
                    <a :href="customer.id ? `{{ route('admin.customers.customers.view', '') }}/${customer.id}` : '#'">
                        <div class="flex justify-between gap-1.5">
                            <div class="flex flex-col">
                                <p class="font-semibold text-gray-600 dark:text-gray-300">
                                    @{{ customer.full_name }}
                                </p>

                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ customer.email }}
                                </p>
                            </div>

                            <div class="flex flex-col">
                                <p class="font-semibold text-gray-800 dark:text-white">
                                    @{{ customer.formatted_total }}
                                </p>

                                <p class="text-gray-600 dark:text-gray-300" v-if="customer.orders">
                                    @{{ "@lang('admin::app.dashboard.index.order-count')".replace(':count', customer.orders) }}
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <div
                    class="flex flex-col gap-8 p-4"
                    v-else
                >
                    <div class="grid justify-center justify-items-center gap-3.5 py-2.5">
                        <!-- Placeholder Image -->
                        <img
                            src="{{ bagisto_asset('images/empty-placeholders/customers.svg') }}"
                            class="h-20 w-20 dark:mix-blend-exclusion dark:invert"
                        />

                        <!-- Add Variants Information -->
                        <div class="flex flex-col items-center">
                            <p class="text-base font-semibold text-gray-400">
                                @lang('admin::app.dashboard.index.add-customer')
                            </p>

                            <p class="text-gray-400">
                                @lang('admin::app.dashboard.index.customer-info')
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-top-customers', {
            template: '#v-dashboard-top-customers-template',

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

                    filtets.type = 'top-customers';

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
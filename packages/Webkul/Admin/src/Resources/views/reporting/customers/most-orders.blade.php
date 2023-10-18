{{-- Customers with Most Orders Vue Component --}}
<v-reporting-customers-with-most-orders>
    <x-admin::shimmer.reporting.progress-bar/>
</v-reporting-customers-with-most-orders>

@pushOnce('scripts')
    <script type="text/x-template" id="v-reporting-customers-with-most-orders-template">
        <!-- Customers with Most Orders Section -->
        <div class="flex-1 relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
            <!-- Header -->
            <div class="flex items-center justify-between mb-[16px]">
                <p class="text-[16px] text-gray-600 dark:text-white font-semibold">
                    @lang('admin::app.reporting.customers.index.customers-with-most-orders')
                </p>

                <a
                    href="{{ route('admin.reporting.customers.view', ['type' => 'customers-with-most-orders']) }}"
                    class="text-[14px] text-blue-600 cursor-pointer transition-all hover:underline"
                >
                    @lang('admin::app.reporting.customers.index.view-details')
                </a>
            </div>

            <template v-if="isLoading">
                <x-admin::shimmer.reporting.progress-bar/>
            </template>

            <template v-else>
                <!-- Content -->
                <div class="grid gap-[16px]">
                    <!-- Customers with Most Orders -->
                    <template v-if="report.statistics">
                        <!-- Customers -->
                        <div class="grid gap-[27px]">
                            <div
                                class="grid"
                                v-for="customer in report.statistics"
                            >
                                <p class="dark:text-white">@{{ customer.full_name }}</p>

                                <div class="flex gap-[20px] items-center">
                                    <div class="w-full h-[8px] relative bg-slate-100">
                                        <div
                                            class="h-[8px] absolute left-0 bg-emerald-500"
                                            :style="{ 'width': customer.progress + '%' }"
                                        ></div>
                                    </div>

                                    <p class="text-[14px] text-gray-600 dark:text-gray-300 font-semibold">
                                        @{{ customer.orders }}
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
        app.component('v-reporting-customers-with-most-orders', {
            template: '#v-reporting-customers-with-most-orders-template',

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

                    this.$axios.get("{{ route('admin.reporting.customers.stats') }}", {
                            params: {
                                type: 'customers-with-most-orders'
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
<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.reporting.' . $entity . '.index.' . request()->query('type'))
    </x-slot>

    <v-reporting-stats-table>
        <!-- Shimmer -->
        <x-admin::shimmer.reporting.view />
    </v-reporting-stats-table>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-reporting-stats-table-template"
        >
            <div>
                <!-- Page Header -->
                <div class="flex gap-4 justify-between items-center mb-5 max-sm:flex-wrap">
                    <!-- Title -->
                    <div class="grid gap-1.5">
                        <p class="text-xl text-gray-800 dark:text-white font-bold leading-6">
                            @lang('admin::app.reporting.' . $entity . '.index.' . request()->query('type'))
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-1.5 items-center">
                        <x-admin::dropdown position="bottom-right">
                            <x-slot:toggle>
                                <span class="flex icon-setting p-1.5 rounded-md text-2xl cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800"></span>
                            </x-slot>

                            <x-slot:menu class="!p-0 shadow-[0_5px_20px_rgba(0,0,0,0.15)] dark:border-gray-800">
                                <x-admin::dropdown.menu.item>
                                    <span @click="exportReporting('csv')">
                                        @lang('admin::app.reporting.view.export-csv')
                                    </span>
                                </x-admin::dropdown.menu.item>

                                <x-admin::dropdown.menu.item>
                                    <span @click="exportReporting('xls')">
                                        @lang('admin::app.reporting.view.export-xls')
                                    </span>
                                </x-admin::dropdown.menu.item>
                            </x-slot>
                        </x-admin::dropdown>

                        @if (in_array(request()->query('type'), [
                            'total-sales',
                            'total-orders',
                            'average-sales',
                            'tax-collected',
                            'shipping-collected',
                            'refunds',
                            'total-customers',
                            'total-sold-quantities',
                            'total-products-added-to-wishlist',
                        ]))
                            <select
                                class="custom-select flex w-fit min-h-[39px] rounded-md border px-3 pl-2 pr-9 text-sm text-gray-600 transition-all hover:border-gray-400 dark:hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                                v-model="filters.period"
                            >
                                <option value="day">
                                    @lang('admin::app.reporting.view.day')
                                </option>

                                <option value="month">
                                    @lang('admin::app.reporting.view.month')
                                </option>

                                <option value="year">
                                    @lang('admin::app.reporting.view.year')
                                </option>
                            </select>
                        @endif

                        <x-admin::flat-picker.date class="!w-[140px]" ::allow-input="false">
                            <input
                                class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                                v-model="filters.start"
                                placeholder="@lang('admin::app.reporting.view.start-date')"
                            />
                        </x-admin::flat-picker.date>

                        <x-admin::flat-picker.date class="!w-[140px]" ::allow-input="false">
                            <input
                                class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                                v-model="filters.end"
                                placeholder="@lang('admin::app.reporting.view.end-date')"
                            />
                        </x-admin::flat-picker.date>
                    </div>
                </div>

                <div class="table-responsive grid w-full box-shadow rounded bg-white dark:bg-gray-900 overflow-hidden">
                    <template v-if="isLoading">
                        <x-admin::shimmer.datagrid.table.head />

                        <x-admin::shimmer.datagrid.table.body />
                    </template>

                    <template v-else>
                        <!-- Table Header -->
                        <div
                            class="row grid grid-cols-4 grid-rows-1 gap-2.5 items-center px-4 py-2.5 border-b dark:border-gray-800 text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900 font-semibold"
                            :style="`grid-template-columns: repeat(${reporing.statistics.columns.length}, minmax(0, 1fr))`"
                        >
                            <div
                                class="flex gap-2.5 cursor-pointer"
                                v-for="column in reporing.statistics.columns"
                            >
                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ column.label }}
                                </p>
                            </div>
                        </div>

                        <!-- Table Body -->
                        <div
                            class="row grid gap-2.5 items-center px-4 py-4 border-b dark:border-gray-800 text-gray-600 dark:text-gray-300 transition-all hover:bg-gray-50 dark:hover:bg-gray-950" style="grid-template-columns: repeat(4, minmax(0, 1fr));"
                            :style="`grid-template-columns: repeat(${reporing.statistics.columns.length}, minmax(0, 1fr))`"
                            v-if="reporing.statistics.records.length"
                            v-for="record in reporing.statistics.records"
                        >
                            <p
                                v-for="column in reporing.statistics.columns"
                            >
                                @{{ record[column.key] }}
                            </p>
                        </div>

                        <div
                            v-else
                            class="row grid gap-2.5 text-center px-4 py-4 border-b dark:border-gray-800 text-gray-600 dark:text-gray-300 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                        >
                            <p>@lang('admin::app.reporting.view.not-available')</p>
                        </div>
                    </template>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-reporting-stats-table', {
                template: '#v-reporting-stats-table-template',

                data() {
                    return {
                        filters: {
                            type: "{{ request()->query('type') }}",
                            period: 'day',
                            start: "{{ $startDate->format('Y-m-d') }}",
                            end: "{{ $endDate->format('Y-m-d') }}",
                        },

                        reporing: [],

                        isLoading: true,
                    }
                },

                mounted() {
                    this.getStats();
                },

                watch: {
                    filters: {
                        handler() {
                            this.getStats();
                        },

                        deep: true
                    }
                },

                methods: {
                    getStats() {
                        this.isLoading = true;

                        this.$axios.get("{{ route('admin.reporting.' . $entity . '.view.stats') }}", {
                                params: this.filters
                            })
                            .then(response => {
                                this.reporing = response.data;

                                this.isLoading = false;
                            })
                            .catch(error => {});
                    },

                    exportReporting(format) {
                        let filters = this.filters;

                        filters.format = format;

                        window.open(
                            "{{ route('admin.reporting.' . $entity . '.export') }}?"  + new URLSearchParams(filters).toString(),
                            '_blank'
                        );
                    }
                }
            });
        </script>
    @endPushOnce
</x-admin::layouts>

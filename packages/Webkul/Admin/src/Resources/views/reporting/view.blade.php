<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.reporting.' . $entity . '.index.' . request()->query('type'))
    </x-slot:title>

    <v-reporting-stats-table>
        {{-- Shimmer --}}
        <x-admin::shimmer.reporting.view/>
    </v-reporting-stats-table>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-reporting-stats-table-template">
            <div>
                <!-- Page Header -->
                <div class="flex gap-[16px] justify-between items-center mb-[20px] max-sm:flex-wrap">
                    <!-- Title -->
                    <div class="grid gap-[6px]">
                        <p class="text-[20px] text-gray-800 dark:text-white font-bold leading-[24px]">
                            @lang('admin::app.reporting.' . $entity . '.index.' . request()->query('type'))
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-[6px]">
                        <x-admin::dropdown position="bottom-right">
                            <x-slot:toggle>
                                <span class="flex icon-setting p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 "></span>
                            </x-slot:toggle>

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
                            </x-slot:menu>
                        </x-admin::dropdown>

                        <select
                            class="custom-select flex w-fit min-h-[39px] rounded-[6px] border px-3 pl-2 pr-[35px] text-[14px] text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
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

                        <x-admin::flat-picker.date class="!w-[140px]" ::allow-input="false">
                            <input
                                class="flex min-h-[39px] w-full rounded-[6px] border px-3 py-2 text-[14px] text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                                v-model="filters.start"
                                placeholder="@lang('admin::app.reporting.view.start-date')"
                            />
                        </x-admin::flat-picker.date>

                        <x-admin::flat-picker.date class="!w-[140px]" ::allow-input="false">
                            <input
                                class="flex min-h-[39px] w-full rounded-[6px] border px-3 py-2 text-[14px] text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                                v-model="filters.end"
                                placeholder="@lang('admin::app.reporting.view.end-date')"
                            />
                        </x-admin::flat-picker.date>
                    </div>
                </div>

                <div class="table-responsive grid w-full box-shadow rounded-[4px] bg-white dark:bg-gray-900 overflow-hidden">
                    <template v-if="isLoading">
                        <x-admin::shimmer.datagrid.table.head/>

                        <x-admin::shimmer.datagrid.table.body/>
                    </template>

                    <template v-else>
                        <!-- Table Header -->
                        <div
                            class="row grid grid-cols-4 grid-rows-1 gap-[10px] items-center px-[16px] py-[10px] border-b-[1px] dark:border-gray-800 text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900 font-semibold"
                            :style="`grid-template-columns: repeat(${reporing.statistics.columns.length}, 1fr)`"
                        >
                            <div
                                class="flex gap-[10px] cursor-pointer"
                                v-for="column in reporing.statistics.columns"
                            >
                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ column.label }}
                                </p>
                            </div>
                        </div>

                        <!-- Table Body -->
                        <div
                            class="row grid gap-[10px] items-center px-[16px] py-[16px] border-b-[1px] dark:border-gray-800 text-gray-600 dark:text-gray-300 transition-all hover:bg-gray-50 dark:hover:bg-gray-950" style="grid-template-columns: repeat(4, 1fr);"
                            :style="`grid-template-columns: repeat(${reporing.statistics.columns.length}, 1fr)`"
                            v-for="record in reporing.statistics.records"
                        >
                            <p v-for="column in reporing.statistics.columns">
                                @{{ record[column.key] }}
                            </p>
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
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
                <div class="mb-5 flex items-center justify-between gap-4 max-sm:flex-wrap">
                    <!-- Title -->
                    <div class="grid gap-1.5">
                        <p class="text-xl font-bold leading-6 text-gray-800 dark:text-white">
                            @lang('admin::app.reporting.' . $entity . '.index.' . request()->query('type'))
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-1.5">
                        <x-admin::dropdown position="bottom-right">
                            <x-slot:toggle>
                                <span class="icon-admin-export flex cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800"></span>
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
                        
                        <template v-if="channels.length > 2">
                            <x-admin::dropdown position="bottom-right">
                                <x-slot:toggle>
                                    <button
                                        type="button"
                                        class="inline-flex w-full cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center text-sm leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                    >
                                        @{{ channels.find(channel => channel.code == filters.channel).name }}
                                        
                                        <span class="icon-sort-down text-2xl"></span>
                                    </button>
                                </x-slot>

                                <x-slot:menu class="!p-0 shadow-[0_5px_20px_rgba(0,0,0,0.15)] dark:border-gray-800">
                                    <x-admin::dropdown.menu.item
                                        v-for="channel in channels"
                                        ::class="{'bg-gray-100 dark:bg-gray-950': channel.code == filters.channel}"
                                        @click="filters.channel = channel.code"
                                    >
                                        @{{ channel.name }}
                                    </x-admin::dropdown.menu.item>
                                </x-slot>
                            </x-admin::dropdown>
                        </template>

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
                            <div class="relative inline-flex w-full max-w-max">
                                <select
                                    class="w-full cursor-pointer appearance-none rounded-md border bg-white px-2.5 py-1.5 pr-8 text-center leading-6 text-gray-600 transition hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
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
                                
                                <span class="icon-sort-down pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 transform text-2xl text-gray-600 dark:text-gray-300"></span>
                            </div>
                        @endif

                        <x-admin::flat-picker.date class="!w-[140px]" ::allow-input="false">
                            <input
                                class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                                v-model="filters.start"
                                placeholder="@lang('admin::app.reporting.view.start-date')"
                            />
                        </x-admin::flat-picker.date>

                        <x-admin::flat-picker.date class="!w-[140px]" ::allow-input="false">
                            <input
                                class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                                v-model="filters.end"
                                placeholder="@lang('admin::app.reporting.view.end-date')"
                            />
                        </x-admin::flat-picker.date>
                    </div>
                </div>

                <div class="table-responsive box-shadow grid w-full overflow-hidden rounded bg-white dark:bg-gray-900">
                    <template v-if="isLoading">
                        <x-admin::shimmer.datagrid.table.head />

                        <x-admin::shimmer.datagrid.table.body />
                    </template>

                    <template v-else>
                        <!-- Table Header -->
                        <div
                            class="row grid grid-cols-4 grid-rows-1 items-center gap-2.5 border-b bg-gray-50 px-4 py-2.5 font-semibold text-gray-600 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                            :style="`grid-template-columns: repeat(${reporing.statistics.columns.length}, minmax(0, 1fr))`"
                        >
                            <div
                                class="flex cursor-pointer gap-2.5"
                                v-for="column in reporing.statistics.columns"
                            >
                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ column.label }}
                                </p>
                            </div>
                        </div>

                        <!-- Table Body -->
                        <div
                            class="row grid items-center gap-2.5 border-b px-4 py-4 text-gray-600 transition-all hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-950" style="grid-template-columns: repeat(4, minmax(0, 1fr));"
                            :style="`grid-template-columns: repeat(${reporing.statistics.columns.length}, minmax(0, 1fr))`"
                            v-if="reporing.statistics.records.length"
                            v-for="record in reporing.statistics.records"
                        >
                            <p v-for="column in reporing.statistics.columns">
                                @{{ record[column.key] }}
                            </p>
                        </div>

                        <div
                            v-else
                            class="row grid gap-2.5 border-b px-4 py-4 text-center text-gray-600 transition-all hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-950"
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
                        channels: [
                            {
                                name: "@lang('admin::app.reporting.view.all-channels')",
                                code: ''
                            },
                            ...@json(core()->getAllChannels()),
                        ],
                        
                        filters: {
                            type: "{{ request()->query('type') }}",
                            
                            period: 'day',

                            channel: '',

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

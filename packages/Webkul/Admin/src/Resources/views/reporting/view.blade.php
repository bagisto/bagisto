<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.reporting.' . $entity . '.index.' . request()->query('type'))
    </x-slot:title>

    {{-- Page Header --}}
    <div class="flex gap-[16px] justify-between items-center mb-[20px] max-sm:flex-wrap">
        <div class="grid gap-[6px]">
            <p class="pt-[6px] text-[20px] text-gray-800 dark:text-white font-bold leading-[24px]">
                @lang('admin::app.reporting.sales.index.title')
            </p>
        </div>
    </div>

    <v-reporting-stats-table>
        <div class="table-responsive grid w-full box-shadow rounded-[4px] bg-white dark:bg-gray-900 overflow-hidden">
            <x-admin::shimmer.datagrid.table.head></x-admin::shimmer.datagrid.table.head>

            <x-admin::shimmer.datagrid.table.body></x-admin::shimmer.datagrid.table.body>
        </div>
    </v-reporting-stats-table>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-reporting-stats-table-template">
            <div class="table-responsive grid w-full box-shadow rounded-[4px] bg-white dark:bg-gray-900 overflow-hidden">
                <template v-if="isLoading">
                    <x-admin::shimmer.datagrid.table.head></x-admin::shimmer.datagrid.table.head>

                    <x-admin::shimmer.datagrid.table.body></x-admin::shimmer.datagrid.table.body>
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
        </script>

        <script type="module">
            app.component('v-reporting-stats-table', {
                template: '#v-reporting-stats-table-template',

                data() {
                    return {
                        reporing: [],

                        isLoading: true,
                    }
                },

                mounted() {
                    this.getStats();
                },

                methods: {
                    getStats() {
                        this.isLoading = true;

                        this.$axios.get("{{ route('admin.reporting.' . $entity . '.view.stats') }}", {
                                params: {
                                    type: "{{ request()->query('type') }}",
                                }
                            })
                            .then(response => {
                                this.reporing = response.data;

                                this.isLoading = false;
                            })
                            .catch(error => {});
                    }
                }
            });
        </script>
    @endPushOnce
</x-admin::layouts>
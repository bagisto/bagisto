<v-datagrid src="{{ route('admin.catalog.attributes.index') }}"></v-datagrid>

@pushOnce('scripts')
    <script type="text/x-template" id="v-datagrid-template">
        <div>
            <!-- Toolbar -->
            <div class="flex  gap-[16px] justify-between items-center mt-[28px] max-md:flex-wrap">
                <!-- Left Toolbar -->
				<div class="flex gap-x-[4px] items-center w-full">
                    <!-- Filters -->
                    <div class="">
                        <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 font-semibold px-[4px] py-[6px] text-center w-full max-w-max bg-white border border-gray-300 rounded-[6px] cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-gratext-gray-600 transition-all hover:border-gray-400">
                            <span class="icon-filter text-[24px]"></span>

                            <span>Filter</span>

                            <span class="icon-arrow-up text-[24px]"></span>
                        </div>

                        <div class="hidden w-full z-10 bg-white divide-y divide-gray-100 rounded shadow">
                        </div>
                    </div>

					<!-- Search Panel -->
					<div class="flex items-center max-w-[445px] max-sm:max-w-full max-sm:w-full">
						<label for="organic-search" class="sr-only">
                            Search
                        </label>

						<div class="relative w-full">
							<div class="icon-serch text-[22px] absolute left-[12px] top-[6px] flex items-center pointer-events-none"></div>

							<input
                                type="text"
                                name="search"
                                value=""
                                class="bg-white border border-gray-300 rounded-lg block w-full px-[40px] py-[6px] leading-6 text-gray-400 transition-all hover:border-gray-400"
                                placeholder="Search"
                                @keyup.enter="filterPage"
                            >

                            <button type="button" class="icon-camera text-[22px] absolute top-[12px] right-[12px] flex items-center pr-[12px]"></button>
						</div>
					</div>
				</div>

                <!-- Right Toolbar -->
				<div class="flex gap-x-[16px]">
					<span class="icon-settings text-[24px] p-[6px] rounded-[6px] cursor-pointer transition-all hover:bg-gray-100"></span>

					<div class="flex gap-x-[8px] items-center">
						<div class="">
							<div class="inline-flex gap-x-[8px] items-center justify-between text-gray-600 py-[6px] px-[10px] text-center leading-[24px] w-full max-w-max bg-white border border-gray-300 rounded-[6px] cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400">
								<span v-text="applied.perPageOptions"></span>

                                <span class="icon-sort-down text-[24px]"></span>
							</div>

							<div class="hidden w-full z-10 bg-white divide-y divide-gray-100 rounded shadow">
							</div>
						</div>

						<p class="text-gray-600 whitespace-nowrap max-sm:hidden">per page</p>

						<div
                            class="inline-flex gap-x-[4px] items-center justify-between ml-[8px] text-gray-600 py-[6px] px-[8px] leading-[24px] text-center w-full max-w-max bg-white border border-gray-300 rounded-[6px] marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400 max-sm:hidden"
                            v-text="available.meta.current_page"
                        >
                        </div>

						<div class="text-gray-600 whitespace-nowrap">
                            <span>of </span>

                            <span v-text="available.meta.last_page"></span>
                        </div>

                        <!-- Pagination -->
						<div class="flex gap-[4px] items-center">
							<div
                                class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 p-[6px] text-center w-full max-w-max rounded-[6px] border border-transparent cursor-pointer transition-all active:border-gray-300 hover:bg-gray-100 marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black"
                                @click="changePage(available.links.previous)"
                            >
								<span class="icon-sort-left text-[24px]"></span>
							</div>

							<div
                                class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 p-[6px] text-center w-full max-w-max rounded-[6px] border border-transparent cursor-pointer transition-all active:border-gray-300 hover:bg-gray-100 marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black"
                                @click="changePage(available.links.next)"
                            >
								<span class="icon-sort-right text-[24px]"></span>
							</div>
						</div>
					</div>
				</div>
			</div>

            <div class="flex gap-2 mt-[30px]">
                <!-- Side Filter -->
                <div class="px-[16px] py-[23px] border border-gray-300 rounded-[4px] bg-white shadow-[0px_8px_10px_0px_rgba(0,_0,_0,_0.2)]">
                    <div class="">
                        <div class="flex justify-between items-center">
                            <p class="text-gray-800 font-medium leading-[24px]">Quick Filter</p>
                            <span class="icon-arrow-up text-[24px]"></span>
                        </div>
                        <div class="grid my-[8px]">
                            <div class="flex justify-between items-center py-[3px]">
                                <p class="text-[12px] text-blue-600 font-medium leading-[24px]">All Orders</p>
                                <p class="text-[12px] text-blue-600 font-medium leading-[24px]">2</p>
                            </div>
                            <div class="flex justify-between items-center py-[3px]">
                                <p class="text-[12px] text-blue-600 font-medium leading-[24px]">Today’s Order</p>
                                <p class="text-[12px] text-blue-600 font-medium leading-[24px]">10</p>
                            </div>
                            <div class="flex justify-between items-center py-[3px]">
                                <p class="text-[12px] text-blue-600 font-medium leading-[24px]">Saved Pending order Today</p>
                                <p class="text-[12px] text-blue-600 font-medium leading-[24px]"></p>
                            </div>
                            <div class="flex justify-between items-center py-[3px]">
                                <p class="text-[12px] text-blue-600 font-medium leading-[24px]">Save Guest Client</p>
                                <p class="icon-cross text-[16px] text-blue-600 font-medium cursor-pointer"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Mid Line -->
                    <span class="block w-full my-[5px] border border-[#E9E9E9]"></span>

                    <div class="">
                        <div class="flex justify-between items-center">
                            <p class="text-gray-800 font-medium leading-[24px]">Status</p>
                            <div class="flex gap-x-[5px] items-center">
                                <p class="text-[12px] text-blue-600 font-medium leading-[24px]">Clear All</p>
                                <span class="icon-arrow-up text-[24px]"></span>
                            </div>
                        </div>
                        <div class="grid my-[8px]">
                            <div class="flex justify-between items-center py-[3px]">
                                <p class="text-[12px] text-gray-600 font-medium leading-[24px]">Pending</p>
                                <p class="icon-tick text-[16px] text-blue-600  cursor-pointer"></p>
                            </div>
                            <div class="flex justify-between items-center py-[3px]">
                                <p class="text-[12px] text-gray-600 font-medium leading-[24px]">Processing</p>
                                <p class="icon-tick text-[16px] text-blue-600 cursor-pointer"></p>
                            </div>
                            <div class="flex justify-between items-center py-[3px]">
                                <p class="text-[12px] text-gray-600 font-medium leading-[24px]">Cancelled</p>
                                <p class="icon-tick text-[16px] text-blue-600  cursor-pointer"></p>
                            </div>
                            <div class="flex justify-between items-center py-[3px]">
                                <p class="text-[12px] text-gray-600 font-medium leading-[24px]">Closed</p>
                                <p class="text-[16px] text-blue-600 font-medium cursor-pointer"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Mid Line -->
                    <span class="block w-full my-[5px] border border-[#E9E9E9]"></span>

                    <div v-for="column in available.columns">
                        <div v-if="column.type === 'date_range'">
                            <div class="flex justify-between items-center">
                                <p
                                    class="text-gray-800 font-medium leading-[24px]"
                                    v-text="column.label"
                                >
                                </p>

                                <div class="flex gap-x-[5px] items-center">
                                    <p class="text-[12px] text-blue-600 font-medium leading-[24px]">
                                        Clear All
                                    </p>

                                    <span class="icon-arrow-up text-[24px]"></span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 my-[16px] gap-[5px]">
                                <p
                                    class="text-gray-600 font-medium text-center leading-[24px] px-[8px] py-[6px] border border-gray-300 rounded-[6px] cursor-pointer"
                                    @click="filterPage($event, column, { quickFilter: { isEnabled: true, value: 'today' }  })"
                                >
                                    Today
                                </p>

                                <p
                                    class="text-gray-600 font-medium text-center leading-[24px] px-[8px] py-[6px] border border-gray-300 rounded-[6px] cursor-pointer"
                                    @click="filterPage($event, column, { quickFilter: { isEnabled: true, value: 'yesterday' } })"
                                >
                                    Yesterday
                                </p>

                                <p
                                    class="text-gray-600 font-medium text-center leading-[24px] px-[8px] py-[6px] border border-gray-300 rounded-[6px] cursor-pointer"
                                    @click="filterPage($event, column, { quickFilter: { isEnabled: true, value: 'this_week' } })"
                                >
                                    This Week
                                </p>

                                <p
                                    class="text-gray-600 font-medium text-center leading-[24px] px-[8px] py-[6px] border border-gray-300 rounded-[6px] cursor-pointer"
                                    @click="filterPage($event, column, { quickFilter: { isEnabled: true, value: 'this_month' } })"
                                >
                                    This Month
                                </p>

                                <p
                                    class="text-gray-600 font-medium text-center leading-[24px] px-[8px] py-[6px] border border-gray-300 rounded-[6px] cursor-pointer"
                                    @click="filterPage($event, column, { quickFilter: { isEnabled: true, value: 'last_month' } })"
                                >
                                    Last Month
                                </p>

                                <p
                                    class="text-gray-600 font-medium text-center leading-[24px] px-[8px] py-[6px] border border-gray-300 rounded-[6px] cursor-pointer"
                                    @click="filterPage($event, column, { quickFilter: { isEnabled: true, value: 'last_three_months' } })"
                                >
                                    Last 3 Months
                                </p>

                                <p
                                    class="text-gray-600 font-medium text-center leading-[24px] px-[8px] py-[6px] border border-gray-300 rounded-[6px] cursor-pointer"
                                    @click="filterPage($event, column, { quickFilter: { isEnabled: true, value: 'last_six_months' } })"
                                >
                                    Last 6 Months
                                </p>

                                <p
                                    class="text-gray-600 font-medium text-center leading-[24px] px-[8px] py-[6px] border border-gray-300 rounded-[6px] cursor-pointer"
                                    @click="filterPage($event, column, { quickFilter: { isEnabled: true, value: 'this_year' } })"
                                >
                                    This Year
                                </p>

                                <div class="inline-flex gap-x-[8px] items-center justify-between text-[14px] text-gray-400 py-[6px] px-[10px] text-center leading-[24px] w-full bg-white border border-gray-300 rounded-[6px] cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black ">
                                    <input
                                        type="date"
                                        :name="`${column.index}[from]`"
                                        value=""
                                        :placeholder="column.label"
                                        :ref="`${column.index}[from]`"
                                        @change="filterPage($event, column, { range: { name: 'from' }, quickFilter: { isEnabled: false, value: '' } })"
                                    />
                                </div>

                                <div class="inline-flex gap-x-[8px] items-center justify-between text-[14px] text-gray-400 py-[6px] px-[10px] text-center leading-[24px] w-full bg-white border border-gray-300 rounded-[6px] cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black ">
                                    <input
                                        type="date"
                                        :name="`${column.index}[to]`"
                                        value=""
                                        :placeholder="column.label"
                                        :ref="`${column.index}[from]`"
                                        @change="filterPage($event, column, { range: { name: 'to' }, quickFilter: { isEnabled: false, value: '' } })"
                                    />
                                </div>
                            </div>
                        </div>

                        <div v-if="column.type === 'datetime_range'">
                            <div class="flex justify-between items-center">
                                <p
                                    class="text-gray-800 font-medium leading-[24px]"
                                    v-text="column.label"
                                >
                                </p>

                                <div class="flex gap-x-[5px] items-center">
                                    <p class="text-[12px] text-blue-600 font-medium leading-[24px]">
                                        Clear All
                                    </p>

                                    <span class="icon-arrow-up text-[24px]"></span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 my-[16px] gap-[5px]">
                                <p
                                    class="text-gray-600 font-medium text-center leading-[24px] px-[8px] py-[6px] border border-gray-300 rounded-[6px] cursor-pointer"
                                    @click="filterPage($event, column, { quickFilter: { isEnabled: true, value: 'today' }  })"
                                >
                                    Today
                                </p>

                                <p
                                    class="text-gray-600 font-medium text-center leading-[24px] px-[8px] py-[6px] border border-gray-300 rounded-[6px] cursor-pointer"
                                    @click="filterPage($event, column, { quickFilter: { isEnabled: true, value: 'yesterday' }  })"
                                >
                                    Yesterday
                                </p>

                                <p
                                    class="text-gray-600 font-medium text-center leading-[24px] px-[8px] py-[6px] border border-gray-300 rounded-[6px] cursor-pointer"
                                    @click="filterPage($event, column, { quickFilter: { isEnabled: true, value: 'this_week' }  })"
                                >
                                    This Week
                                </p>

                                <p
                                    class="text-gray-600 font-medium text-center leading-[24px] px-[8px] py-[6px] border border-gray-300 rounded-[6px] cursor-pointer"
                                    @click="filterPage($event, column, { quickFilter: { isEnabled: true, value: 'this_month' }  })"
                                >
                                    This Month
                                </p>

                                <p
                                    class="text-gray-600 font-medium text-center leading-[24px] px-[8px] py-[6px] border border-gray-300 rounded-[6px] cursor-pointer"
                                    @click="filterPage($event, column, { quickFilter: { isEnabled: true, value: 'last_month' }  })"
                                >
                                    Last Month
                                </p>

                                <p
                                    class="text-gray-600 font-medium text-center leading-[24px] px-[8px] py-[6px] border border-gray-300 rounded-[6px] cursor-pointer"
                                    @click="filterPage($event, column, { quickFilter: { isEnabled: true, value: 'last_three_months' }  })"
                                >
                                    Last 3 Months
                                </p>

                                <p
                                    class="text-gray-600 font-medium text-center leading-[24px] px-[8px] py-[6px] border border-gray-300 rounded-[6px] cursor-pointer"
                                    @click="filterPage($event, column, { quickFilter: { isEnabled: true, value: 'last_six_months' }  })"
                                >
                                    Last 6 Months
                                </p>

                                <p
                                    class="text-gray-600 font-medium text-center leading-[24px] px-[8px] py-[6px] border border-gray-300 rounded-[6px] cursor-pointer"
                                    @click="filterPage($event, column, { quickFilter: { isEnabled: true, value: 'this_year' }  })"
                                >
                                    This Year
                                </p>

                                <div class="inline-flex gap-x-[8px] items-center justify-between text-[14px] text-gray-400 py-[6px] px-[10px] text-center leading-[24px] w-full bg-white border border-gray-300 rounded-[6px] cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black ">
                                    <input
                                        type="datetime-local"
                                        :name="`${column.index}[from]`"
                                        value=""
                                        :placeholder="column.label"
                                        :ref="`${column.index}[from]`"
                                        @change="filterPage($event, column, { range: { name: 'from' }, quickFilter: { isEnabled: false, value: '' } })"
                                    />
                                </div>

                                <div class="inline-flex gap-x-[8px] items-center justify-between text-[14px] text-gray-400 py-[6px] px-[10px] text-center leading-[24px] w-full bg-white border border-gray-300 rounded-[6px] cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black ">
                                    <input
                                        type="datetime-local"
                                        :name="`${column.index}[to]`"
                                        value=""
                                        :placeholder="column.label"
                                        :ref="`${column.index}[from]`"
                                        @change="filterPage($event, column, { range: { name: 'to' }, quickFilter: { isEnabled: false, value: '' } })"
                                    />
                                </div>
                            </div>
                        </div>

                        <div v-else>
                            <div class="flex justify-between items-center">
                                <p
                                    class="text-gray-800 font-medium leading-[24px]"
                                    v-text="column.label"
                                >
                                </p>

                                <span class="icon-arrow-up text-[24px]"></span>
                            </div>

                            <div class="grid my-[16px]">
                                <input
                                    type="text"
                                    :name="column.index"
                                    class="text-[14px] bg-white border border-gray-300 rounded-[6px] block w-full px-[8px] py-[6px] leading-[24px] text-gray-400"
                                    :placeholder="column.label"
                                    @keyup.enter="filterPage($event, column)"
                                />
                            </div>
                        </div>

                        <span class="block w-full my-[5px] border border-[#E9E9E9]"></span>
                    </div>
                </div>

                <!-- Table -->
                <div>
                    <div class="relative overflow-x-auto border rounded-[4px] box-shadow">
                        <x-admin::table>
                            <x-admin::table.thead>
                                <x-admin::table.thead.tr>
                                    <x-admin::table.th
                                        v-for="column in available.columns"
                                        v-text="column.label"
                                        @click="sortPage(column)"
                                    >
                                    </x-admin::table.th>
                                </x-admin::table.thead.tr>
                            </x-admin::table.thead>

                            <x-admin::table.tbody>
                                <x-admin::table.tbody.tr
                                    v-for="record in available.records"
                                >
                                    <x-admin::table.td
                                        v-for="column in available.columns"
                                        v-text="record[column.index]"
                                    >
                                    </x-admin::table.td>
                                </x-admin::table.tbody.tr>
                            </x-admin::table.tbody>
                        </x-admin::table>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-datagrid', {
            template: '#v-datagrid-template',

            props: ['src'],

            data() {
                return {
                    available: {
                        actions: [],

                        columns: [],

                        records: [],

                        links: {},

                        meta: {},
                    },

                    applied: {
                        perPageOptions: 10,

                        sort: {
                            column: 'id',
                            order: 'desc',
                        },

                        filters: {
                            columns: [],
                        },
                    },
                };
            },

            mounted() {
                // refactor
                this.$axios
                    .get(this.src)
                    .then((response) => {
                        this.available.actions = response.data.actions;

                        this.available.columns = response.data.columns;

                        this.available.records = response.data.records;

                        this.available.links = response.data.links;

                        this.available.meta = response.data.meta;
                    });
            },

            methods: {
                changePage(link) {
                    if (! link) {
                        return;
                    }

                    this.$axios
                        .get(link)
                        .then((response) => {
                            this.available.actions = response.data.actions;

                            this.available.columns = response.data.columns;

                            this.available.records = response.data.records;

                            this.available.links = response.data.links;

                            this.available.meta = response.data.meta;
                        });
                },

                sortPage(column) {
                    // refactor
                    this.applied.sort = {
                        column: column.index,
                        order: this.applied.sort.order === 'asc' ? 'desc' : 'asc',
                    };

                    if (column.sortable) {
                        // refactor
                        this.$axios
                            .get(this.src, {
                                params: {
                                    sort_by: this.applied.sort.column,
                                    sort_order: this.applied.sort.order,
                                },
                            })
                            .then((response) => {
                                this.available.actions = response.data.actions;

                                this.available.columns = response.data.columns;

                                this.available.records = response.data.records;

                                this.available.links = response.data.links;

                                this.available.meta = response.data.meta;
                            });
                    }
                },

                applyFilter(column, requestedValue, additional = {}) {
                    let appliedColumn = this.findAppliedColumn(column?.index);

                    if (
                        ! requestedValue
                        || requestedValue == appliedColumn?.value
                    ) {
                        return;
                    }

                    if (! column) {
                        if (appliedColumn) {
                            appliedColumn.value.push(requestedValue);
                        } else {
                            this.applied.filters.columns.push({
                                index: 'all',
                                value: [requestedValue]
                            });
                        }
                    } else {
                        switch (column.type) {
                            case 'date_range':
                            case 'datetime_range':
                                let { range } = additional;

                                if (appliedColumn) {
                                    let appliedRanges = appliedColumn.value[0];

                                    if (range.name == 'from') {
                                        appliedRanges[0] = requestedValue;
                                    }

                                    if (range.name == 'to') {
                                        appliedRanges[1] = requestedValue;
                                    }

                                    appliedColumn.value = [appliedRanges];
                                } else {
                                    let appliedRanges = ['', ''];

                                    if (range.name == 'from') {
                                        appliedRanges[0] = requestedValue;
                                    }

                                    if (range.name == 'to') {
                                        appliedRanges[1] = requestedValue;
                                    }

                                    this.applied.filters.columns.push({
                                        ...column,
                                        value: [appliedRanges]
                                    });
                                }

                                break;

                            default:
                                if (appliedColumn) {
                                    appliedColumn.value.push(requestedValue);
                                } else {
                                    this.applied.filters.columns.push({
                                        ...column,
                                        value: [requestedValue]
                                    });
                                }

                                break;
                        }
                    }
                },

                filterPage($event, column = null, additional = {}) {
                    let quickFilter = additional?.quickFilter;

                    if (quickFilter?.isEnabled) {
                        let options = column.options[quickFilter.value];

                        switch (column.type) {
                            case 'date_range':
                            case 'datetime_range':
                                this.$refs[`${column.index}[from]`].value = options.from;
                                this.applyFilter(column, options.from, { range: { name: 'from' } });

                                this.$refs[`${column.index}[from]`].value = options.to;
                                this.applyFilter(column, options.to, { range: { name: 'to' } });

                                break;

                            default:
                                break;
                        }
                    } else {
                        this.applyFilter(column, $event.target.value, additional);
                    }

                    let params = {
                        sort_by: this.applied.sort.column,
                        sort_order: this.applied.sort.order,
                        filters: {},
                    };

                    this.applied.filters.columns.forEach(column => {
                        params.filters[column.index] = column.value;
                    });

                    this.$axios
                        .get(this.src, { params })
                        .then((response) => {
                            this.available.actions = response.data.actions;

                            this.available.columns = response.data.columns;

                            this.available.records = response.data.records;

                            this.available.links = response.data.links;

                            this.available.meta = response.data.meta;
                        });
                },

                findAppliedColumn(columnIndex) {
                    return this.applied.filters.columns.find(column => column.index === columnIndex);
                },

                getAppliedColumnValues(columnIndex) {
                    let appliedColumn = this.findAppliedColumn(columnIndex);

                    return appliedColumn?.value ?? [];
                },
            },
        });
    </script>
@endPushOnce

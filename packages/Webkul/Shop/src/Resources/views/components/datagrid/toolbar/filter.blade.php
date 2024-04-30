<v-datagrid-filter
    :is-loading="isLoading"
    :available="available"
    :applied="applied"
    @applyFilter="filter"
    @removeFilter="filter"
>
    {{ $slot }}
</v-datagrid-filter>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-datagrid-filter-template"
    >
        <slot
            name="filter"
            :available="available"
            :applied="applied"
            :filters="filters"
            :apply-filter="applyFilter"
            :apply-column-values="applyColumnValues"
            :find-applied-column="findAppliedColumn"
            :has-any-applied-column-values="hasAnyAppliedColumnValues"
            :get-applied-column-values="getAppliedColumnValues"
            :remove-applied-column-value="removeAppliedColumnValue"
            :remove-applied-column-all-values="removeAppliedColumnAllValues"
        >
            <template v-if="isLoading">
                <x-shop::shimmer.datagrid.toolbar.filter />
            </template>

            <template v-else>
                <x-shop::drawer
                    width="350px"
                    ref="filterDrawer"
                >
                    <x-slot:toggle>
                        <div
                            class="flex justify-between items-center gap-4 max-w-[200px] w-full ltr:pl-3 rtl:pr-3 ltr:pr-4 rtl:pl-4 py-2 rounded-lg bg-white border border-[#E9E9E9] text-sm transition-all hover:border-gray-400 focus:border-gray-400 max-md:ltr:pr-2.5 max-md:rtl:pl-2.5 max-md:ltr:pl-2.5 max-md:rtl:pr-2.5 max-md:w-[110px] cursor-pointer"
                            :class="{'[&>*]:text-blue-600': filters.columns.length > 0}"
                        >
                            <span class="flex justify-between items-center gap-1.5">
                                <span class="icon-filter text-2xl"></span>

                                @lang('shop::app.components.datagrid.toolbar.filter.title')
                            </span>
                        </div>
                    </x-slot>

                    <x-slot:header class="border-b border-[#E9E9E9]">
                            @lang('shop::app.components.datagrid.toolbar.filter.apply-filter')
                    </x-slot>

                    <x-slot:content>
                        <div v-for="column in available.columns">
                            <div v-if="column.filterable">
                                <!-- Boolean -->
                                <div v-if="column.type === 'boolean'">
                                    <div class="flex items-center justify-between">
                                        <p
                                            class="text-sm font-medium leading-6 text-gray-800"
                                            v-text="column.label"
                                        >
                                        </p>

                                        <div
                                            class="flex items-center gap-x-1.5"
                                            @click="removeAppliedColumnAllValues(column.index)"
                                        >
                                            <p
                                                class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                v-if="hasAnyAppliedColumnValues(column.index)"
                                            >
                                                @lang('shop::app.components.datagrid.toolbar.filter.custom-filters.clear-all')
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mb-2 mt-1.5">
                                        <x-shop::dropdown>
                                            <x-slot:toggle>
                                                <button
                                                    type="button"
                                                    class="flex justify-between items-center gap-4 w-full ltr:pl-4 rtl:pr-4 ltr:pr-3 rtl:pl-3 py-2 rounded-lg bg-white border border-[#E9E9E9] text-sm transition-all hover:border-gray-400 focus:border-gray-400 max-md:ltr:pr-2.5 max-md:rtl:pl-2.5 max-md:ltr:pl-2.5 max-md:rtl:pr-2.5 max-md:border-0 max-md:w-[110px] cursor-pointer"
                                                >
                                                    <span v-text="'@lang('shop::app.components.datagrid.toolbar.filter.dropdown.select')'"></span>

                                                    <span class="icon-sort-down text-2xl"></span>
                                                </button>
                                            </x-slot>

                                            <x-slot:menu>
                                                <x-shop::dropdown.menu.item
                                                    v-for="option in column.options"
                                                    v-text="option.label"
                                                    @click="applyFilter(option.value, column)"
                                                >
                                                </x-shop::dropdown.menu.item>
                                            </x-slot>
                                        </x-shop::dropdown>
                                    </div>

                                    <div class="mb-4 flex gap-2 flex-wrap">
                                        <p
                                            class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                            v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                                        >
                                            <!-- Retrieving the label from the options based on the applied column value. -->
                                            <span v-text="column.options.find((option => option.value == appliedColumnValue)).label"></span>

                                            <span
                                                class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                                            >
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Dropdown -->
                                <div v-else-if="column.type === 'dropdown'">
                                    <!-- Basic -->
                                    <div v-if="column.options.type === 'basic'">
                                        <div class="flex items-center justify-between">
                                            <p
                                                class="text-sm font-medium leading-6 text-gray-800"
                                                v-text="column.label"
                                            >
                                            </p>

                                            <div
                                                class="flex items-center gap-x-1.5"
                                                @click="removeAppliedColumnAllValues(column.index)"
                                            >
                                                <p
                                                    class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                    v-if="hasAnyAppliedColumnValues(column.index)"
                                                >
                                                    @lang('shop::app.components.datagrid.toolbar.filter.custom-filters.clear-all')
                                                </p>
                                            </div>
                                        </div>

                                        <div class="mb-2 mt-1.5">
                                            <x-shop::dropdown>
                                                <x-slot:toggle>
                                                    <button
                                                        type="button"
                                                        class="flex justify-between items-center gap-4 w-full ltr:pl-4 rtl:pr-4 ltr:pr-3 rtl:pl-3 py-2 rounded-lg bg-white border border-[#E9E9E9] text-sm transition-all hover:border-gray-400 focus:border-gray-400 max-md:ltr:pr-2.5 max-md:rtl:pl-2.5 max-md:ltr:pl-2.5 max-md:rtl:pr-2.5 max-md:border-0 max-md:w-[110px] cursor-pointer"
                                                    >
                                                        <span v-text="'@lang('shop::app.components.datagrid.toolbar.filter.dropdown.select')'"></span>

                                                        <span class="icon-arrow-down text-2xl"></span>
                                                    </button>
                                                </x-slot>

                                                <x-slot:menu>
                                                    <x-shop::dropdown.menu.item
                                                        v-for="option in column.options.params.options"
                                                        v-text="option.label"
                                                        @click="applyFilter(option.value, column)"
                                                    >
                                                    </x-shop::dropdown.menu.item>
                                                </x-slot>
                                            </x-shop::dropdown>
                                        </div>

                                        <div class="mb-4 flex gap-2 flex-wrap">
                                            <p
                                                class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                                            >
                                                <!-- Retrieving the label from the options based on the applied column value. -->
                                                <span v-text="column.options.params.options.find((option => option.value == appliedColumnValue)).label"></span>

                                                <span
                                                    class="icon-cancel cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                    @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                                                >
                                                </span>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Searchable -->
                                    <div v-else-if="column.options.type === 'searchable'">
                                        <div class="flex items-center justify-between">
                                            <p
                                                class="text-sm font-medium leading-6 text-gray-800"
                                                v-text="column.label"
                                            >
                                            </p>

                                            <div
                                                class="flex items-center gap-x-1.5"
                                                @click="removeAppliedColumnAllValues(column.index)"
                                            >
                                                <p
                                                    class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                    v-if="hasAnyAppliedColumnValues(column.index)"
                                                >
                                                    @lang('shop::app.components.datagrid.toolbar.filter.custom-filters.clear-all')
                                                </p>
                                            </div>
                                        </div>

                                        <div class="mb-2 mt-1.5">
                                            <v-datagrid-searchable-dropdown
                                                :datagrid-id="available.id"
                                                :column="column"
                                                @select-option="applyFilter($event, column)"
                                            >
                                            </v-datagrid-searchable-dropdown>
                                        </div>

                                        <div class="mb-4 flex gap-2 flex-wrap">
                                            <p
                                                class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                                            >
                                                <span v-text="appliedColumnValue"></span>

                                                <span
                                                    class="icon-cancel ltr:ml-1.5 rtl:mr-1.5 cursor-pointer text-lg text-white"
                                                    @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                                                >
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Date Range -->
                                <div v-else-if="column.type === 'date_range'">
                                    <div class="flex items-center justify-between">
                                        <p
                                            class="text-sm font-medium leading-6 text-gray-800"
                                            v-text="column.label"
                                        >
                                        </p>

                                        <div
                                            class="flex items-center gap-x-1.5"
                                            @click="removeAppliedColumnAllValues(column.index)"
                                        >
                                            <p
                                                class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                v-if="hasAnyAppliedColumnValues(column.index)"
                                            >
                                                @lang('shop::app.components.datagrid.toolbar.filter.custom-filters.clear-all')
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt-4 grid grid-cols-2 gap-1.5">
                                        <p
                                            class="cursor-pointer rounded-md border border-gray-300 px-2 py-1.5 text-center font-medium leading-6 text-gray-600"
                                            v-for="option in column.options"
                                            v-text="option.label"
                                            @click="applyFilter(
                                                $event,
                                                column,
                                                { quickFilter: { isActive: true, selectedFilter: option } }
                                            )"
                                        >
                                        </p>

                                        <x-shop::flat-picker.date ::allow-input="false">
                                            <input
                                                value=""
                                                class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400"
                                                :type="column.input_type"
                                                :name="`${column.index}[from]`"
                                                :placeholder="column.label"
                                                :ref="`${column.index}[from]`"
                                                @change="applyFilter(
                                                    $event,
                                                    column,
                                                    { range: { name: 'from' }, quickFilter: { isActive: false } }
                                                )"
                                            />
                                        </x-shop::flat-picker.date>

                                        <x-shop::flat-picker.date ::allow-input="false">
                                            <input
                                                type="column.input_type"
                                                value=""
                                                class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400"
                                                :name="`${column.index}[to]`"
                                                :placeholder="column.label"
                                                :ref="`${column.index}[from]`"
                                                @change="applyFilter(
                                                    $event,
                                                    column,
                                                    { range: { name: 'to' }, quickFilter: { isActive: false } }
                                                )"
                                            />
                                        </x-shop::flat-picker.date>

                                        <div class="mb-4 flex gap-2 flex-wrap">
                                            <p
                                                class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                                            >
                                                <span v-text="appliedColumnValue.join(' to ')"></span>

                                                <span
                                                    class="icon-cancel ltr:ml-1.5 rtl:mr-1.5 cursor-pointer text-lg text-white"
                                                    @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                                                >
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Date Time Range -->
                                <div v-else-if="column.type === 'datetime_range'">
                                    <div class="flex items-center justify-between">
                                        <p
                                            class="text-sm font-medium leading-6 text-gray-800"
                                            v-text="column.label"
                                        >
                                        </p>

                                        <div
                                            class="flex items-center gap-x-1.5"
                                            @click="removeAppliedColumnAllValues(column.index)"
                                        >
                                            <p
                                                class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                v-if="hasAnyAppliedColumnValues(column.index)"
                                            >
                                                @lang('shop::app.components.datagrid.toolbar.filter.custom-filters.clear-all')
                                            </p>
                                        </div>
                                    </div>

                                    <div class="my-4 grid grid-cols-2 gap-1.5">
                                        <p
                                            class="cursor-pointer rounded-md border border-gray-300 px-2 py-1.5 text-center font-medium leading-6 text-gray-600"
                                            v-for="option in column.options"
                                            v-text="option.label"
                                            @click="applyFilter(
                                                $event,
                                                column,
                                                { quickFilter: { isActive: true, selectedFilter: option } }
                                            )"
                                        >
                                        </p>

                                        <x-shop::flat-picker.datetime ::allow-input="false">
                                            <input
                                                value=""
                                                class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                                                :type="column.input_type"
                                                :name="`${column.index}[from]`"
                                                :placeholder="column.label"
                                                :ref="`${column.index}[from]`"
                                                @change="applyFilter(
                                                    $event,
                                                    column,
                                                    { range: { name: 'from' }, quickFilter: { isActive: false } }
                                                )"
                                            />
                                        </x-shop::flat-picker.datetime>

                                        <x-shop::flat-picker.datetime ::allow-input="false">
                                            <input
                                                type="column.input_type"
                                                value=""
                                                class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                                                :name="`${column.index}[to]`"
                                                :placeholder="column.label"
                                                :ref="`${column.index}[from]`"
                                                @change="applyFilter(
                                                    $event,
                                                    column,
                                                    { range: { name: 'to' }, quickFilter: { isActive: false } }
                                                )"
                                            />
                                        </x-shop::flat-picker.datetime>

                                        <div class="mb-4 flex gap-2 flex-wrap">
                                            <p
                                                class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                                            >
                                                <span v-text="appliedColumnValue.join(' to ')"></span>

                                                <span
                                                    class="icon-cancel ltr:ml-1.5 rtl:mr-1.5 cursor-pointer text-lg text-white"
                                                    @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                                                >
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Rest -->
                                <div v-else>
                                    <div class="flex items-center justify-between">
                                        <p
                                            class="text-sm font-medium leading-6 text-gray-800"
                                            v-text="column.label"
                                        >
                                        </p>

                                        <div
                                            class="flex items-center gap-x-1.5"
                                            @click="removeAppliedColumnAllValues(column.index)"
                                        >
                                            <p
                                                class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                v-if="hasAnyAppliedColumnValues(column.index)"
                                            >
                                                @lang('shop::app.components.datagrid.toolbar.filter.custom-filters.clear-all')
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mb-2 mt-1.5 grid">
                                        <input
                                            type="text"
                                            class="mb-3 w-full rounded border px-3 py-2 text-sm text-gray-600 shadow transition-all hover:border-gray-400 focus:border-gray-400"
                                            :name="column.index"
                                            :placeholder="column.label"
                                            @keyup.enter="applyFilter($event, column)"
                                        />
                                    </div>

                                    <div class="mb-4 flex gap-2 flex-wrap">
                                        <p
                                            class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                            v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                                        >
                                            <span v-text="appliedColumnValue"></span>

                                            <span
                                                class="icon-cancel ltr:ml-1.5 rtl:mr-1.5 cursor-pointer text-lg text-white"
                                                @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                                            >
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-slot>
                </x-shop::drawer>
            </template>
        </slot>
    </script>

    <script type="module">
        app.component('v-datagrid-filter', {
            template: '#v-datagrid-filter-template',

            props: ['isLoading', 'available', 'applied'],

            data() {
                return {
                    filters: {
                        columns: [],
                    },
                };
            },

            mounted() {
                this.filters.columns = this.applied.filters.columns.filter((column) => column.index !== 'all');
            },

            methods: {
                /**
                 * Apply filter.
                 *
                 * @param {Event} $event
                 * @param {object} column
                 * @param {object} additional
                 * @returns {void}
                 */
                applyFilter($event, column = null, additional = {}) {
                    let quickFilter = additional?.quickFilter;

                    if (quickFilter?.isActive) {
                        let options = quickFilter.selectedFilter;

                        switch (column.type) {
                            case 'date_range':
                            case 'datetime_range':
                                this.applyColumnValues(column, options.from, {
                                    range: {
                                        name: 'from'
                                    }
                                });

                                this.applyColumnValues(column, options.to, {
                                    range: {
                                        name: 'to'
                                    }
                                });

                                break;

                            default:
                                break;
                        }
                    } else {
                        /**
                         * Here, either a real event will come or a string value. If a string value is present, then
                         * we create a similar event-like structure to avoid any breakage and make it easy to use.
                         */
                        if ($event?.target?.value === undefined) {
                            $event = {
                                target: {
                                    value: $event,
                                }
                            };
                        }

                        this.applyColumnValues(column, $event.target.value, additional);

                        if (column) {
                            $event.target.value = '';
                        }
                    }

                    this.$emit('applyFilter', this.filters);

                    this.$refs.filterDrawer.close();
                },

                /**
                 * Apply column values.
                 *
                 * @param {object} column
                 * @param {string} requestedValue
                 * @param {object} additional
                 * @returns {void}
                 */
                applyColumnValues(column, requestedValue, additional = {}) {
                    let appliedColumn = this.findAppliedColumn(column?.index);

                    if (
                        requestedValue === undefined ||
                        requestedValue === '' ||
                        appliedColumn?.value.includes(requestedValue)
                    ) {
                        return;
                    }

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

                                this.filters.columns.push({
                                    ...column,
                                    value: [appliedRanges]
                                });
                            }

                            break;

                        default:
                            if (appliedColumn) {
                                appliedColumn.value.push(requestedValue);
                            } else {
                                this.filters.columns.push({
                                    ...column,
                                    value: [requestedValue]
                                });
                            }

                            break;
                    }
                },

                /**
                 * Find applied column.
                 *
                 * @param {string} columnIndex
                 * @returns {object}
                 */
                findAppliedColumn(columnIndex) {
                    return this.filters.columns.find(column => column.index === columnIndex);
                },

                /**
                 * Check if any values are applied for the specified column.
                 *
                 * @param {string} columnIndex
                 * @returns {boolean}
                 */
                hasAnyAppliedColumnValues(columnIndex) {
                    let appliedColumn = this.findAppliedColumn(columnIndex);

                    return appliedColumn?.value.length > 0;
                },

                /**
                 * Get applied values for the specified column.
                 *
                 * @param {string} columnIndex
                 * @returns {Array}
                 */
                getAppliedColumnValues(columnIndex) {
                    let appliedColumn = this.findAppliedColumn(columnIndex);

                    return appliedColumn?.value ?? [];
                },

                /**
                 * Remove a specific value from the applied values of the specified column.
                 *
                 * @param {string} columnIndex
                 * @param {any} appliedColumnValue
                 * @returns {void}
                 */
                removeAppliedColumnValue(columnIndex, appliedColumnValue) {
                    let appliedColumn = this.findAppliedColumn(columnIndex);

                    appliedColumn.value = appliedColumn?.value.filter(value => value !== appliedColumnValue);

                    /**
                     * Clean up is done here. If there are no applied values present, there is no point in including the applied column as well.
                     */
                    if (! appliedColumn.value.length) {
                        this.filters.columns = this.filters.columns.filter(column => column.index !== columnIndex);
                    }

                    this.$emit('removeFilter', this.filters);

                    this.$refs.filterDrawer.close();
                },

                /**
                 * Remove all values from the applied values of the specified column.
                 *
                 * @param {string} columnIndex
                 * @returns {void}
                 */
                removeAppliedColumnAllValues(columnIndex) {
                    this.filters.columns = this.filters.columns.filter(column => column.index !== columnIndex);

                    this.$emit('removeFilter', this.filters);
                },
            },
        });
    </script>

    <script type="text/x-template" id="v-datagrid-searchable-dropdown-template">
        <x-shop::dropdown ::close-on-click="false">
            <x-slot:toggle>
                <button
                    type="button"
                    class="flex justify-between items-center gap-4 w-full pl-4 pr-3 py-2 rounded-lg bg-white border border-[#E9E9E9] text-sm transition-all hover:border-gray-400 focus:border-gray-400 max-md:pr-2.5 max-md:pl-2.5 max-md:border-0 max-md:w-[110px] cursor-pointer"
                >
                    <span v-text="'@lang('shop::app.components.datagrid.toolbar.filter.dropdown.select')'"></span>

                    <span class="icon-arrow-down text-2xl"></span>
                </button>
            </x-slot>

            <!-- Dropdown Content -->
            <x-slot:menu>
                <div class="relative">
                    <div class="relative rounded">
                        <ul class="list-reset">
                            <li class="p-2">
                                <input
                                    class="block w-full rounded-md border border-gray-300 bg-white px-2 py-1.5 text-sm leading-6 text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                    @keyup="lookUp($event)"
                                >
                            </li>

                            <ul class="p-2">
                                <li v-if="! isMinimumCharacters">
                                    <p
                                        class="hover:bg-grey-light block cursor-pointer p-2 text-black"
                                        v-text="'@lang('shop::app.components.datagrid.toolbar.filter.dropdown.searchable.at-least-two-chars')'"
                                    >
                                    </p>
                                </li>

                                <li v-else-if="! searchedOptions.length">
                                    <p
                                        class="hover:bg-grey-light block cursor-pointer p-2 text-black"
                                        v-text="'@lang('shop::app.components.datagrid.toolbar.filter.dropdown.searchable.no-results')'"
                                    >
                                    </p>
                                </li>

                                <li
                                    v-for="option in searchedOptions"
                                    v-else
                                >
                                    <p
                                        class="hover:bg-grey-light block cursor-pointer p-2 text-black"
                                        v-text="option.label"
                                        @click="selectOption(option)"
                                    >
                                    </p>
                                </li>
                            </ul>
                        </ul>
                    </div>
                </div>
            </x-slot>
        </x-shop::dropdown>
    </script>

    <script type="module">
        app.component('v-datagrid-searchable-dropdown', {
            template: '#v-datagrid-searchable-dropdown-template',

            props: ['datagridId', 'column'],

            data() {
                return {
                    isMinimumCharacters: false,

                    searchedOptions: [],
                };
            },

            methods: {
                /**
                 * Perform a look up for options based on the search query.
                 *
                 * @param {Event} $event
                 * @returns {void}
                 */
                lookUp($event) {
                    let params = {
                        datagrid_id: this.datagridId,
                        column: this.column.index,
                        search: $event.target.value,
                    };

                    if (! (params['search'].length > 1)) {
                        this.searchedOptions = [];

                        this.isMinimumCharacters = false;

                        return;
                    }

                    this.$axios
                        .get('{{ route('shop.customer.datagrid.look_up') }}', {
                            params
                        })
                        .then(({
                            data
                        }) => {
                            this.isMinimumCharacters = true;

                            this.searchedOptions = data;
                        });
                },

                /**
                 * Select an option from the searched options.
                 *
                 * @param {object} option
                 * @returns {void}
                 */
                selectOption(option) {
                    this.searchedOptions = [];

                    this.$emit('select-option', {
                        target: {
                            value: option.value
                        }
                    });
                },
            },
        });
    </script>
@endpushOnce

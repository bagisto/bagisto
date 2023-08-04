<v-datagrid src="{{ route('admin.catalog.attributes.index') }}"></v-datagrid>

@pushOnce('scripts')
    <script type="text/x-template" id="v-datagrid-template">
        <div>
            <x-admin::datagrid.toolbar></x-admin::datagrid.toolbar>

            <div class="flex gap-2 mt-[30px]">
                <div v-if="showFilters">
                    <x-admin::datagrid.filters></x-admin::datagrid.filters>
                </div>

                <x-admin::datagrid.table></x-admin::datagrid.table>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-datagrid', {
            template: '#v-datagrid-template',

            props: ['src'],

            data() {
                return {
                    showFilters: false,

                    available: {
                        actions: [],

                        columns: [],

                        records: [],

                        meta: {},
                    },

                    applied: {
                        pagination: {
                            page: 1,

                            perPage: 10,
                        },

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
                this.get();
            },

            methods: {
                /**
                 * Get. This will prepare params from the `applied` props and fetch the data from the backend.
                 *
                 * @returns {void}
                 */
                get() {
                    let params = {
                        pagination: {
                            page: this.applied.pagination.page,
                            per_page: this.applied.pagination.perPage,
                        },

                        sort: {
                            column: this.applied.sort.column,
                            order: this.applied.sort.order,
                        },

                        filters: {},
                    };

                    this.applied.filters.columns.forEach(column => {
                        params.filters[column.index] = column.value;
                    });

                    this.$axios
                        .get(this.src, { params })
                        .then((response) => {
                            /**
                             * Precisely taking all the keys to the data prop to avoid adding any extra keys from the response.
                             */
                            const { actions, columns, records, meta } = response.data;

                            this.available.actions = actions;

                            this.available.columns = columns;

                            this.available.records = records;

                            this.available.meta = meta;
                        });
                },

                /**
                 * Change Page.
                 *
                 * The reason for choosing the numeric approach over the URL approach is to prevent any conflicts with our existing
                 * URLs. If we were to use the URL approach, it would introduce additional arguments in the `get` method, necessitating
                 * the addition of a `url` prop. Instead, by using the numeric approach, we can let Axios handle all the query parameters
                 * using the `applied` prop. This allows for a cleaner and more straightforward implementation.
                 *
                 * @param {string} direction
                 * @returns {void}
                 */
                changePage(direction) {
                    let newPage;

                    if (direction === 'previous') {
                        newPage = this.available.meta.current_page - 1;
                    } else if (direction === 'next') {
                        newPage = this.available.meta.current_page + 1;
                    } else {
                        console.error('Invalid direction provided: ' + direction);

                        return;
                    }

                    /**
                     * Check if the `newPage` is within the valid range.
                     */
                    if (newPage >= 1 && newPage <= this.available.meta.last_page) {
                        this.applied.pagination.page = newPage;

                        this.get();
                    } else {
                        console.warn('Invalid page provided: ' + newPage);
                    }
                },

                /**
                 * Change per page option.
                 *
                 * @param {integer} option
                 * @returns {void}
                 */
                changePerPageOption(option) {
                    this.applied.pagination.perPage = option;

                    this.get();
                },

                /**
                 * Sort Page.
                 *
                 * @param {object} column
                 * @returns {void}
                 */
                sortPage(column) {
                    if (column.sortable) {
                        this.applied.sort = {
                            column: column.index,
                            order: this.applied.sort.order === 'asc' ? 'desc' : 'asc',
                        };

                        this.get();
                    }
                },

                /**
                 * Filter Page.
                 *
                 * @param {object} $event
                 * @param {object} column
                 * @param {object} additional
                 * @returns {void}
                 */
                filterPage($event, column = null, additional = {}) {
                    let quickFilter = additional?.quickFilter;

                    if (quickFilter?.isActive) {
                        let options = quickFilter.selectedFilter;

                        switch (column.type) {
                            case 'date_range':
                            case 'datetime_range':
                                this.applyFilter(column, options.from, { range: { name: 'from' } });

                                this.applyFilter(column, options.to, { range: { name: 'to' } });

                                break;

                            default:
                                break;
                        }
                    } else {
                        this.applyFilter(column, $event.target.value, additional);

                        $event.target.value = '';
                    }

                    this.get();
                },

                applyFilter(column, requestedValue, additional = {}) {
                    let appliedColumn = this.findAppliedColumn(column?.index);

                    if (
                        ! requestedValue
                        || requestedValue == appliedColumn?.value
                    ) {
                        return;
                    }

                    /**
                     * If no column is found, it means that search from the toolbar have been
                     * activated. In this case, we will search for `all` indices and update the
                     * value accordingly.
                     */
                    if (! column) {
                        let appliedColumn = this.findAppliedColumn('all');

                        if (appliedColumn) {
                            appliedColumn.value = [requestedValue];
                        } else {
                            this.applied.filters.columns.push({
                                index: 'all',
                                value: [requestedValue]
                            });
                        }

                    /**
                     * Else, we will look into the sidebar filters and update the value accordingly.
                     */
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

                //================================================================

                findAppliedColumn(columnIndex) {
                    return this.applied.filters.columns.find(column => column.index === columnIndex);
                },

                hasAnyAppliedColumnValues(columnIndex) {
                    let appliedColumn = this.findAppliedColumn(columnIndex);

                    return appliedColumn?.value.length > 0;
                },

                getAppliedColumnValues(columnIndex) {
                    let appliedColumn = this.findAppliedColumn(columnIndex);

                    return appliedColumn?.value ?? [];
                },

                removeAppliedColumnValue(columnIndex, appliedColumnValue) {
                    let appliedColumn = this.findAppliedColumn(columnIndex);

                    appliedColumn.value = appliedColumn?.value.filter(value => value !== appliedColumnValue);

                    /**
                     * Clean up is done here. If there are no applied values present, there is no point in including the applied column as well.
                     */
                    if (! appliedColumn.value.length) {
                        this.applied.filters.columns = this.applied.filters.columns.filter(column => column.index !== columnIndex);
                    }

                    this.get();
                },

                removeAppliedColumnAllValues(columnIndex) {
                    this.applied.filters.columns = this.applied.filters.columns.filter(column => column.index !== columnIndex);

                    this.get();
                },

                //================================================================

                // refactor when not in that much use case
                performAction(action) {
                    switch (action.method.toLowerCase()) {
                        case 'get':
                            window.location.href = action.url;

                            break;

                        case 'post':
                            this.$axios
                                .post(action.url)
                                .then(response => {
                                    this.get();
                                });

                            break;

                        case 'put':
                            this.$axios
                                .put(action.url)
                                .then(response => {
                                    this.get();
                                });

                            break;

                        case 'patch':
                            this.$axios
                                .patch(action.url)
                                .then(response => {
                                    this.get();
                                });

                            break;

                        case 'delete':
                            this.$axios
                                .delete(action.url)
                                .then(response => {
                                    this.get();
                                });

                            break;

                        default:
                            console.error('Method not supported.');

                            break;
                    }
                },

                toggleFilters() {
                    this.showFilters = ! this.showFilters;
                },
            },
        });
    </script>
@endPushOnce

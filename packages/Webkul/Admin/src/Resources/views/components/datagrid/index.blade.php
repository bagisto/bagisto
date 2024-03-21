@props(['isMultiRow' => false])

<v-datagrid {{ $attributes }}>
    <x-admin::shimmer.datagrid :isMultiRow="$isMultiRow" />

    {{ $slot }}
</v-datagrid>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-datagrid-template"
    >
        <div>
            <x-admin::datagrid.toolbar />

            <div class="flex mt-4">
                <x-admin::datagrid.table :isMultiRow="$isMultiRow">
                    <template #header>
                        <slot
                            name="header"
                            :columns="available.columns"
                            :actions="available.actions"
                            :mass-actions="available.massActions"
                            :records="available.records"
                            :meta="available.meta"
                            :sort-page="sortPage"
                            :selectAllRecords="selectAllRecords"
                            :available="available"
                            :applied="applied"
                            :is-loading="isLoading"
                        >
                        </slot>
                    </template>

                    <template #body>
                        <slot
                            name="body"
                            :columns="available.columns"
                            :actions="available.actions"
                            :mass-actions="available.massActions"
                            :records="available.records"
                            :meta="available.meta"
                            :setCurrentSelectionMode="setCurrentSelectionMode"
                            :performAction="performAction"
                            :available="available"
                            :applied="applied"
                            :is-loading="isLoading"
                        >
                        </slot>
                    </template>
                </x-admin::datagrid.table>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-datagrid', {
            template: '#v-datagrid-template',

            props: ['src'],

            data() {
                return {
                    isLoading: false,

                    available: {
                        id: null,

                        columns: [],

                        actions: [],

                        massActions: [],

                        records: [],

                        meta: {},
                    },

                    applied: {
                        massActions: {
                            meta: {
                                mode: 'none',

                                action: null,
                            },

                            indices: [],

                            value: null,
                        },

                        pagination: {
                            page: 1,

                            perPage: 10,
                        },

                        sort: {
                            column: null,

                            order: null,
                        },

                        filters: {
                            columns: [
                                {
                                    index: 'all',
                                    value: [],
                                },
                            ],
                        },
                    },
                };
            },

            mounted() {
                this.boot();
            },

            methods: {
                /**
                 * Initialization: This function checks for any previously saved filters in local storage and applies them as needed.
                 *
                 * @returns {void}
                 */
                boot() {
                    let datagrids = this.getDatagrids();

                    const urlParams = new URLSearchParams(window.location.search);

                    if (urlParams.has('search')) {
                        let searchAppliedColumn = this.findAppliedColumn('all');

                        searchAppliedColumn.value = [urlParams.get('search')];
                    }

                    if (datagrids?.length) {
                        const currentDatagrid = datagrids.find(({ src }) => src === this.src);

                        if (currentDatagrid) {
                            this.applied.pagination = currentDatagrid.applied.pagination;

                            this.applied.sort = currentDatagrid.applied.sort;

                            this.applied.filters = currentDatagrid.applied.filters;

                            if (urlParams.has('search')) {
                                let searchAppliedColumn = this.findAppliedColumn('all');

                                searchAppliedColumn.value = [urlParams.get('search')];
                            }

                            this.get();

                            return;
                        }
                    }

                    this.get();
                },

                /**
                 * Get. This will prepare params from the `applied` props and fetch the data from the backend.
                 *
                 * @returns {void}
                 */
                get(extraParams = {}) {
                    let params = {
                        pagination: {
                            page: this.applied.pagination.page,
                            per_page: this.applied.pagination.perPage,
                        },

                        sort: {},

                        filters: {},
                    };

                    if (
                        this.applied.sort.column &&
                        this.applied.sort.order
                    ) {
                        params.sort = this.applied.sort;
                    }

                    this.applied.filters.columns.forEach(column => {
                        params.filters[column.index] = column.value;
                    });

                    this.isLoading = true;

                    // this.$refs['filterDrawer'].close();

                    this.$axios
                        .get(this.src, {
                            params: { ...params, ...extraParams }
                        })
                        .then((response) => {
                            /**
                             * Precisely taking all the keys to the data prop to avoid adding any extra keys from the response.
                             */
                            const {
                                id,
                                columns,
                                actions,
                                mass_actions,
                                records,
                                meta
                            } = response.data;

                            this.available.id = id;

                            this.available.columns = columns;

                            this.available.actions = actions;

                            this.available.massActions = mass_actions;

                            this.available.records = records;

                            this.available.meta = meta;

                            this.setCurrentSelectionMode();

                            this.updateDatagrids();

                           /**
                            * This event should be fired at the end, but only in the GET method. This allows the export feature to listen to it
                            * and update its properties accordingly.
                            */
                            this.$emitter.emit('change-datagrid', {
                                available: this.available,
                                applied: this.applied
                            });

                            this.isLoading = false;
                        });
                },

                /**
                 * Change Page. When the child component has handled all the cases, it will send the
                 * valid new page; otherwise, it will block. Here, we are certain that we have
                 * a new page, so the parent will simply call the AJAX based on the new page.
                 *
                 * @param {integer} newPage
                 * @returns {void}
                 */
                changePage(newPage) {
                    this.applied.pagination.page = newPage;

                    this.get();
                },

                /**
                 * Change per page option.
                 *
                 * @param {integer} option
                 * @returns {void}
                 */
                changePerPageOption(option) {
                    this.applied.pagination.perPage = option;

                    /**
                     * When the total records are less than the number of data per page, we need to reset the page.
                     */
                    if (this.available.meta.last_page >= this.applied.pagination.page) {
                        this.applied.pagination.page = 1;
                    }

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

                        /**
                         * When the sorting changes, we need to reset the page.
                         */
                        this.applied.pagination.page = 1;

                        this.get();
                    }
                },

                /**
                 * Search Page.
                 *
                 * @param {object} filters
                 * @returns {void}
                 */
                searchPage(filters) {
                    this.applied.filters.columns = [
                        ...(this.applied.filters.columns.filter((column) => column.index !== 'all')),
                        ...filters.columns,
                    ];

                    /**
                     * We need to reset the page on filtering.
                     */
                    this.applied.pagination.page = 1;

                    this.get();
                },

                /**
                 * Filter Page.
                 *
                 * @param {object} filters
                 * @returns {void}
                 */
                 filterPage(filters) {
                    this.applied.filters.columns = [
                        ...(this.applied.filters.columns.filter((column) => column.index === 'all')),
                        ...filters.columns,
                    ];

                    /**
                     * We need to reset the page on filtering.
                     */
                    this.applied.pagination.page = 1;

                    this.get();
                },

                //================================================================
                // Mass actions logic, will move it from here once completed.
                //================================================================

                setCurrentSelectionMode() {
                    this.applied.massActions.meta.mode = 'none';

                    if (! this.available.records.length) {
                        return;
                    }

                    let selectionCount = 0;

                    this.available.records.forEach(record => {
                        const id = record[this.available.meta.primary_column];

                        if (this.applied.massActions.indices.includes(id)) {
                            this.applied.massActions.meta.mode = 'partial';

                            ++selectionCount;
                        }
                    });

                    if (this.available.records.length === selectionCount) {
                        this.applied.massActions.meta.mode = 'all';
                    }
                },

                selectAllRecords() {
                    this.setCurrentSelectionMode();

                    if (['all', 'partial'].includes(this.applied.massActions.meta.mode)) {
                        this.available.records.forEach(record => {
                            const id = record[this.available.meta.primary_column];

                            this.applied.massActions.indices = this.applied.massActions.indices.filter(selectedId => selectedId !== id);
                        });

                        this.applied.massActions.meta.mode = 'none';
                    } else {
                        this.available.records.forEach(record => {
                            const id = record[this.available.meta.primary_column];

                            let found = this.applied.massActions.indices.find(selectedId => selectedId === id);

                            if (!found) {
                                this.applied.massActions.indices.push(id);
                            }
                        });

                        this.applied.massActions.meta.mode = 'all';
                    }
                },

                validateMassAction() {
                    if (! this.applied.massActions.indices.length) {
                        this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('admin::app.components.datagrid.index.no-records-selected')" });

                        return false;
                    }

                    if (! this.applied.massActions.meta.action) {
                        this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('admin::app.components.datagrid.index.must-select-a-mass-action')" });

                        return false;
                    }

                    if (
                        this.applied.massActions.meta.action?.options?.length &&
                        this.applied.massActions.value === null
                    ) {
                        this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('admin::app.components.datagrid.index.must-select-a-mass-action-option')" });

                        return false;
                    }

                    return true;
                },

                performMassAction({ currentAction, currentOption }) {
                    this.applied.massActions.meta.action = currentAction;

                    if (currentOption) {
                        this.applied.massActions.value = currentOption.value;
                    }

                    if (! this.validateMassAction()) {
                        return;
                    }

                    const { action } = this.applied.massActions.meta;

                    const method = action.method.toLowerCase();

                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            switch (method) {
                                case 'post':
                                case 'put':
                                case 'patch':
                                    this.$axios[method](action.url, {
                                            indices: this.applied.massActions.indices,
                                            value: this.applied.massActions.value,
                                        })
                                        .then(response => {
                                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                            this.get();
                                        })
                                        .catch((error) => {
                                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                                        });

                                    break;

                                case 'delete':
                                    this.$axios[method](action.url, {
                                            indices: this.applied.massActions.indices
                                        })
                                        .then(response => {
                                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                            this.get();
                                        })
                                        .catch((error) => {
                                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                                        });

                                    break;

                                default:
                                    console.error('Method not supported.');

                                    break;
                            }

                            this.applied.massActions.indices  = [];
                        }
                    });
                },

                //=======================================================================================
                // Support for previous applied values in datagrids. All code is based on local storage.
                //=======================================================================================

                updateDatagrids() {
                    let datagrids = this.getDatagrids();

                    if (datagrids?.length) {
                        const currentDatagrid = datagrids.find(({ src }) => src === this.src);

                        if (currentDatagrid) {
                            datagrids = datagrids.map(datagrid => {
                                if (datagrid.src === this.src) {
                                    return {
                                        ...datagrid,
                                        requestCount: ++datagrid.requestCount,
                                        available: this.available,
                                        applied: this.applied,
                                    };
                                }

                                return datagrid;
                            });
                        } else {
                            datagrids.push(this.getDatagridInitialProperties());
                        }
                    } else {
                        datagrids = [this.getDatagridInitialProperties()];
                    }

                    this.setDatagrids(datagrids);
                },

                getDatagridInitialProperties() {
                    return {
                        src: this.src,
                        requestCount: 0,
                        available: this.available,
                        applied: this.applied,
                    };
                },

                getDatagridsStorageKey() {
                    return 'datagrids';
                },

                getDatagrids() {
                    let datagrids = localStorage.getItem(
                        this.getDatagridsStorageKey()
                    );

                    return JSON.parse(datagrids) ?? [];
                },

                setDatagrids(datagrids) {
                    localStorage.setItem(
                        this.getDatagridsStorageKey(),
                        JSON.stringify(datagrids)
                    );
                },

                //================================================================
                // Remaining logic, will check.
                //================================================================

                // refactor when not in that much use case...
                performAction(action) {
                    const method = action.method.toLowerCase();

                    switch (method) {
                        case 'get':
                            window.location.href = action.url;

                            break;

                        case 'post':
                        case 'put':
                        case 'patch':
                        case 'delete':
                            this.$emitter.emit('open-confirm-modal', {
                                agree: () => {
                                    this.$axios[method](action.url)
                                        .then(response => {
                                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                            this.get();
                                        })
                                        .catch((error) => {
                                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                                        });
                                }
                            });

                            break;

                        default:
                            console.error('Method not supported.');

                            break;
                    }
                },
            },
        });
    </script>
@endPushOnce

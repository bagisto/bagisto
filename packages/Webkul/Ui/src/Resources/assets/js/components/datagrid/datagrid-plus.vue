<template>
    <div class="table" v-if="isDataLoaded" :key="dataGridIndex">
        <div class="grid-container">
            <div class="grid-top">
                <div class="datagrid-filters">
                    <div class="filter-left">
                        <datagrid-extra-filters
                            :extra-filters="extraFilters"
                            :translations="translations"
                            @onFilter="changeExtraFilter($event)"
                        ></datagrid-extra-filters>
                    </div>
                </div>

                <div class="datagrid-filters" id="datagrid-filters">
                    <div class="">
                        <search-filter
                            :translations="translations"
                            @onFilter="searchData($event)"
                        ></search-filter>
                    </div>

                    <div class="filter-right">
                        <div class="dropdown-filters per-page">
                            <page-filter
                                :per-page="perPage"
                                :translations="translations"
                                @onFilter="paginateData($event)"
                            ></page-filter>
                        </div>

                        <div class="dropdown-filters">
                            <column-filter
                                :columns="columns"
                                :translations="translations"
                                @onFilter="filterData($event)"
                            ></column-filter>
                        </div>

                        <slot name="extra-filters"></slot>
                    </div>
                </div>
            </div>

            <div class="filter-advance">
                <datagrid-filter-tags
                    :filters="filters"
                    :translations="translations"
                    @onRemoveFilter="removeFilter($event)"
                    @onRemoveAllFilter="clearAllFilters()"
                ></datagrid-filter-tags>

                <div class="records-count-container">
                    <span class="datagrid-count">
                        {{ records.total }} {{ translations.recordsFound }}
                    </span>
                </div>
            </div>

            <div class="table-responsive">
                <datagrid-table
                    :actions="actions"
                    :columns="columns"
                    :enable-actions="enableActions"
                    :enable-mass-actions="enableMassActions"
                    :index="index"
                    :mass-actions="massActions"
                    :mass-action-targets="massActionTargets"
                    :records="records"
                    :translations="translations"
                    @onSorting="filterData($event)"
                    @onActionSuccess="refresh()"
                ></datagrid-table>
            </div>

            <datagrid-pagination
                :paginated="paginated"
                :records="records"
                @onChangePage="changePage($event)"
            ></datagrid-pagination>
        </div>
    </div>
</template>

<script>
import ColumnFilter from './filters/column-filter';
import PageFilter from './filters/page-filter';
import SearchFilter from './filters/search-filter';
import PersistDatagridAttributes from './mixins/persist-datagrid-attributes';
import DatagridExtraFilters from './partials/datagrid-extra-filters';
import DatagridFilterTags from './partials/datagrid-filter-tags';
import DatagridPagination from './partials/datagrid-pagination';
import DatagridTable from './partials/datagrid-table';

export default {
    props: ['src'],

    components: {
        ColumnFilter,
        PageFilter,
        SearchFilter,
        DatagridFilterTags,
        DatagridPagination,
        DatagridTable,
        DatagridExtraFilters,
        DatagridFilterTags,
    },

    mixins: [PersistDatagridAttributes],

    data: function () {
        return {
            dataGridIndex: 0,
            currentSort: null,
            filters: [],
            id: btoa(this.src),
            isDataLoaded: false,
            massActionTargets: [],
            url: this.src,
        };
    },

    mounted: function () {
        this.makeURL();
    },

    methods: {
        makeURL() {
            let newParams = '';

            for (let i = 0; i < this.filters.length; i++) {
                if (
                    this.filters[i].column == 'status' ||
                    this.filters[i].column == 'value_per_locale' ||
                    this.filters[i].column == 'value_per_channel' ||
                    this.filters[i].column == 'is_unique'
                ) {
                    if (this.filters[i].val.includes('True')) {
                        this.filters[i].val = 1;
                    } else if (this.filters[i].val.includes('False')) {
                        this.filters[i].val = 0;
                    }
                }

                let condition = '';

                if (this.filters[i].cond !== undefined) {
                    condition = '[' + this.filters[i].cond + ']';
                }

                newParams = `${newParams}&${this.filters[i].column}${condition}=${this.filters[i].val}`;
            }

            this.url = `${this.src}?v=1${newParams}`;

            this.refresh();
        },

        refresh() {
            let self = this;

            this.analyzeDatagridsInfo();

            axios
                .get(this.url)
                .then(function (response) {
                    if (response.status === 200) {
                        let results = response.data;

                        if (
                            ! results.records.data.length &&
                            results.records.prev_page_url
                        ) {
                            self.url = results.records.prev_page_url;

                            self.refresh();

                            return;
                        }

                        self.initResponseProps(results);

                        self.initDatagrid();

                        self.dataGridIndex += 1;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        initResponseProps(results) {
            for (let property in results) {
                this[property] = results[property];
            }
        },

        initDatagrid() {
            this.setParamsAndUrl();

            this.initDataParams();
        },

        setParamsAndUrl() {
            for (let id in this.massActions) {
                this.massActionTargets.push({
                    id: parseInt(id),
                    type: this.massActions[id].type,
                    action: this.massActions[id].action,
                    confirm_text: this.massActions[id].confirm_text,
                });
            }
        },

        initDataParams() {
            if (this.filters.length) {
                for (let i = 0; i < this.filters.length; i++) {
                    if (this.filters[i].column === 'perPage') {
                        this.perPage = this.filters[i].val;
                    }
                }
            }

            this.isDataLoaded = true;
            this.perPage = this.itemsPerPage;
        },

        formURL(column, condition, response, label, type) {
            let obj = {};

            if (
                column === '' ||
                condition === '' ||
                response === '' ||
                column === null ||
                condition === null ||
                response === null
            ) {
                alert(this.translations.filterFieldsMissing);

                return false;
            }

            if (this.filters.length > 0) {
                if (column !== 'sort' && column !== 'search') {
                    let filterRepeated = false;

                    for (let j = 0; j < this.filters.length; j++) {
                        if (this.filters[j].column === column) {
                            if (
                                this.filters[j].cond === condition &&
                                this.filters[j].val === response
                            ) {
                                filterRepeated = true;

                                alert(this.translations.filterExists);

                                return false;
                            }

                            if (
                                this.filters[j].cond === condition &&
                                this.filters[j].val !== response
                            ) {
                                filterRepeated = true;

                                this.filters[j].val = response;

                                this.makeURL();
                            }
                        }
                    }

                    if (filterRepeated === false) {
                        obj.type = type;
                        obj.column = column;
                        obj.cond = condition;
                        obj.val = response;
                        obj.label = label;

                        this.filters.push(obj);
                        obj = {};

                        this.makeURL();
                    }
                }

                if (column === 'sort') {
                    let sortExists = false;

                    for (let j = 0; j < this.filters.length; j++) {
                        if (this.filters[j].column === 'sort') {
                            if (
                                this.filters[j].column === column &&
                                this.filters[j].cond === condition
                            ) {
                                this.findCurrentSort();

                                if (this.currentSort === 'asc') {
                                    this.filters[j].column = column;
                                    this.filters[j].cond = condition;
                                    this.filters[j].val = 'desc';

                                    this.makeURL();
                                } else {
                                    this.filters[j].column = column;
                                    this.filters[j].cond = condition;
                                    this.filters[j].val = 'asc';

                                    this.makeURL();
                                }
                            } else {
                                this.filters[j].column = column;
                                this.filters[j].cond = condition;
                                this.filters[j].val = response;
                                this.filters[j].label = label;

                                this.makeURL();
                            }

                            sortExists = true;
                        }
                    }

                    if (sortExists === false) {
                        if (this.currentSort === null) this.currentSort = 'asc';

                        obj.column = column;
                        obj.cond = condition;
                        obj.val = this.currentSort;
                        obj.label = label;

                        this.filters.push(obj);

                        obj = {};

                        this.makeURL();
                    }
                }

                if (column === 'search') {
                    let searchFound = false;

                    for (let j = 0; j < this.filters.length; j++) {
                        if (this.filters[j].column === 'search') {
                            this.filters[j].column = column;
                            this.filters[j].cond = condition;
                            this.filters[j].val = encodeURIComponent(response);
                            this.filters[j].label = label;

                            this.makeURL();
                        }
                    }

                    for (let j = 0; j < this.filters.length; j++) {
                        if (this.filters[j].column === 'search') {
                            searchFound = true;
                        }
                    }

                    if (searchFound === false) {
                        obj.column = column;
                        obj.cond = condition;
                        obj.val = encodeURIComponent(response);
                        obj.label = label;

                        this.filters.push(obj);

                        obj = {};

                        this.makeURL();
                    }
                }
            } else {
                obj.type = type;
                obj.column = column;
                obj.cond = condition;
                obj.val = encodeURIComponent(response);
                obj.label = label;

                this.filters.push(obj);

                obj = {};

                this.makeURL();
            }
        },

        findCurrentSort() {
            for (let i in this.filters) {
                if (this.filters[i].column === 'sort') {
                    this.currentSort = this.filters[i].val;
                }
            }
        },

        changeExtraFilter($event) {
            const { type, value } = $event.data;

            let url = new URL(this.src);
            url.searchParams.set(type, value);

            this.url = url.href;
            this.refresh();
        },

        searchData($event) {
            const searchValue = $event.data.searchValue.trim();

            this.formURL(
                'search',
                'all',
                searchValue,
                this.translations.searchTitle,
                'search'
            );
        },

        paginateData($event) {
            const currentPerPageSelection = $event.data.perPage;

            for (let i = 0; i < this.filters.length; i++) {
                if (this.filters[i].column == 'perPage') {
                    this.filters.splice(i, 1);
                }
            }

            this.filters.push({
                column: 'perPage',
                cond: 'eq',
                val: currentPerPageSelection,
            });

            this.makeURL();
        },

        filterData($event) {
            const { type, column, condition, response, label } = $event.data;

            this.formURL(column, condition, response, label, type);
        },

        removeFilter($event) {
            const { filter } = $event.data;

            for (let i in this.filters) {
                if (
                    this.filters[i].column === filter.column &&
                    this.filters[i].cond === filter.cond &&
                    this.filters[i].val === filter.val
                ) {
                    this.filters.splice(i, 1);

                    this.makeURL();
                }
            }
        },
        
        clearAllFilters() {
            this.filters = [];

            this.makeURL();
        },

        changePage($event) {
            const { pageLink } = $event.data;

            if (pageLink) {
                this.url = pageLink;
                this.refresh();
            }
        }
    }
};
</script>

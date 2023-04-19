<template>
    <div class="table" v-if="isDataLoaded" :key="dataGridIndex">
        <div class="grid-container">
            <div class="grid-top">
                <div class="datagrid-filters" id="datagrid-filters">
                    <datagrid-extra-filters
                        :extra-filters="extraFilters"
                        :translations="translations"
                        @onFilter="changeExtraFilter($event)"
                    ></datagrid-extra-filters>
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
                    :mass-actions="massActions"
                    :mass-action-targets="massActionTargets"
                    :data-id="indexes"
                    :mass-actions-toggle="massActionsToggle"
                    :extra-filters="extraFilters"
                    :item-count="itemCount"
                    :total = records.total
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
                    :records="records"
                    :translations="translations"
                    @onSorting="filterData($event)"
                    @onActionSuccess="refresh()"
                    @onSelect="getDataIds"
                    @onSelectAll="massActionToggle"
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
            indexes: [],
            massActionsToggle: false,
            itemCount: 0
        };
    },

    mounted: function () {
        this.makeURL();
    },

    methods: {
        makeURL() {
            let url = new URL(this.src);
            url.searchParams.set('v', 1);

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

                url.searchParams.set(`${this.filters[i].column}${condition}`, this.filters[i].val);
            }

            if (this.extraFilters) {
                url.searchParams.set('channel', this.extraFilters.current.channel);
                url.searchParams.set('locale', this.extraFilters.current.locale);
            }

            this.url = url.href;
            this.refresh();
        },

        refresh() {
            let self = this;

            this.analyzeDatagridsInfo();

            axios
                .get(this.url)
                .then(function (response) {
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
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.itemCount = 0;
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

            /**
             * For channel, given a quick fix because the long term solution is time taking. Will
             * implement in the new theme with new datagrid.
             */
            let channel = document.querySelector('select[name=channel]')?.value;

            if (channel) {
                url.searchParams.set('channel', channel);
            }

            /**
             * For locale, given a quick fix because the long term solution is time taking. Will
             * implement in the new theme with new datagrid.
             */
            let locale = document.querySelector('select[name=locale]')?.value;

            if (locale) {
                url.searchParams.set('locale', locale);
            }

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
        },

        getDataIds(dataIds) {
            if (dataIds[0].target.checked) {
                this.indexes.push(dataIds[0].target.value);
            } else {
                this.indexes.map((value, index) => {
                    if (value ==  dataIds[0].target.value) {
                        this.indexes.splice(index, 1);
                    }
                })
            }

            this.itemCount = dataIds[2];

            this.massActionsToggle = dataIds[1];
        },

        massActionToggle(massAction) {
            this.indexes = massAction[0];
            this.massActionsToggle = massAction[1];
            this.itemCount = massAction[2];
        }
    }
};
</script>

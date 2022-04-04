<template>
    <div class="table" v-if="isDataLoaded" :key="dataGridIndex">
        <div class="grid-container">
            <div class="grid-top">
                <div class="datagrid-filters">
                    <div class="filter-left">
                        <div
                            class="dropdown-filters per-page"
                            v-if="extraFilters.channels != undefined"
                        >
                            <channel-filter
                                :extra-filters="extraFilters"
                                :translations="translations"
                                @onFilter="changeExtraFilter($event)"
                            ></channel-filter>
                        </div>

                        <div
                            class="dropdown-filters per-page"
                            v-if="extraFilters.locales != undefined"
                        >
                            <locale-filter
                                :extra-filters="extraFilters"
                                :translations="translations"
                                @onFilter="changeExtraFilter($event)"
                            ></locale-filter>
                        </div>

                        <div
                            class="dropdown-filters per-page"
                            v-if="extraFilters.customer_groups != undefined"
                        >
                            <customer-group-filter
                                :extra-filters="extraFilters"
                                :translations="translations"
                                @onFilter="changeExtraFilter($event)"
                            ></customer-group-filter>
                        </div>
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
                                :per-page-count="perPageCount"
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
                    @onRemoveFilter="removeFilter($event)"
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
                    @onActionSuccess="hitUrl()"
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
import ChannelFilter from './filters/channel-filter';
import ColumnFilter from './filters/column-filter';
import CustomerGroupFilter from './filters/customer-group-filter';
import LocaleFilter from './filters/locale-filter';
import PageFilter from './filters/page-filter';
import SearchFilter from './filters/search-filter';
import PersistDatagridAttributes from './mixins/persist-datagrid-attributes';
import DatagridFilterTags from './datagrid-filter-tags';
import DatagridPagination from './datagrid-pagination';
import DatagridTable from './datagrid-table';

export default {
    props: ['src'],

    components: {
        ChannelFilter,
        ColumnFilter,
        CustomerGroupFilter,
        LocaleFilter,
        PageFilter,
        SearchFilter,
        DatagridFilterTags,
        DatagridPagination,
        DatagridTable,
        DatagridFilterTags
    },

    mixins: [PersistDatagridAttributes],

    data: function() {
        return {
            dataGridIndex: 0,
            currentSort: null,
            filters: [],
            id: btoa(this.src),
            isDataLoaded: false,
            massActionTargets: [],
            perPageCount: [10, 20, 30, 40, 50],
            url: this.src
        };
    },

    mounted: function() {
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

            this.hitUrl();
        },

        hitUrl() {
            let self = this;

            this.analyzeDatagridsInfo();

            axios
                .get(this.url)
                .then(function(response) {
                    if (response.status === 200) {
                        let results = response.data;

                        self.initResponseProps(results);

                        self.initDatagrid();

                        self.dataGridIndex += 1;
                    }
                })
                .catch(function(error) {
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
            let params = new URL(this.src).search;

            if (params.slice(1, params.length).length > 0) {
                this.arrayFromUrl();
            }

            for (let id in this.massActions) {
                this.massActionTargets.push({
                    id: parseInt(id),
                    type: this.massActions[id].type,
                    action: this.massActions[id].action,
                    confirm_text: this.massActions[id].confirm_text
                });
            }
        },

        arrayFromUrl: function() {
            let obj = {};
            const processedUrl = this.url.search.slice(1, this.url.length);
            let splitted = [];
            let moreSplitted = [];

            splitted = processedUrl.split('&');

            for (let i = 0; i < splitted.length; i++) {
                moreSplitted.push(splitted[i].split('='));
            }

            for (let i = 0; i < moreSplitted.length; i++) {
                const key = decodeURI(moreSplitted[i][0]);
                let value = decodeURI(moreSplitted[i][1]);

                if (value.includes('+')) {
                    value = value.replace('+', ' ');
                }

                obj.column = key.replace(']', '').split('[')[0];
                obj.cond = key.replace(']', '').split('[')[1];
                obj.val = value;

                switch (obj.column) {
                    case 'search':
                        obj.label = this.translations.searchTitle;
                        break;
                    case 'channel':
                        obj.label = this.translations.channel;
                        if ('channels' in this.extraFilters) {
                            obj.prettyValue = this.extraFilters[
                                'channels'
                            ].find(channel => channel.code == obj.val);

                            if (obj.prettyValue !== undefined) {
                                obj.prettyValue = obj.prettyValue.name;
                            }
                        }
                        break;
                    case 'locale':
                        obj.label = this.translations.locale;
                        if ('locales' in this.extraFilters) {
                            obj.prettyValue = this.extraFilters['locales'].find(
                                locale => locale.code === obj.val
                            );

                            if (obj.prettyValue !== undefined) {
                                obj.prettyValue = obj.prettyValue.name;
                            }
                        }
                        break;
                    case 'customer_group':
                        obj.label = this.translations.customerGroup;
                        if ('customer_groups' in this.extraFilters) {
                            obj.prettyValue = this.extraFilters[
                                'customer_groups'
                            ].find(
                                customer_group =>
                                    customer_group.id === parseInt(obj.val, 10)
                            );

                            if (obj.prettyValue !== undefined) {
                                obj.prettyValue = obj.prettyValue.name;
                            }
                        }
                        break;
                    case 'sort':
                        for (let colIndex in this.columns) {
                            if (this.columns[colIndex].index === obj.cond) {
                                obj.label = this.columns[colIndex].label;
                                break;
                            }
                        }
                        break;
                    default:
                        for (let colIndex in this.columns) {
                            if (this.columns[colIndex].index === obj.column) {
                                obj.label = this.columns[colIndex].label;

                                if (this.columns[colIndex].type === 'boolean') {
                                    if (obj.val === '1') {
                                        obj.val = this.translations.true;
                                    } else {
                                        obj.val = this.translations.false;
                                    }
                                }
                            }
                        }
                        break;
                }

                if (
                    obj.column !== undefined &&
                    obj.column !== 'admin_locale' &&
                    obj.val !== undefined
                ) {
                    this.filters.push(obj);
                }

                obj = {};
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

            if (this.perPageCount.indexOf(parseInt(this.perPage)) === -1) {
                this.perPageCount.unshift(this.perPage);
            }

            this.isDataLoaded = true;
            this.perPage = this.itemsPerPage;
        },

        formURL(column, condition, response, label) {
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
            } else {
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
                                } else if (
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
                        let sort_exists = false;

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

                                sort_exists = true;
                            }
                        }

                        if (sort_exists === false) {
                            if (this.currentSort === null)
                                this.currentSort = 'asc';

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
                        let search_found = false;

                        for (let j = 0; j < this.filters.length; j++) {
                            if (this.filters[j].column === 'search') {
                                this.filters[j].column = column;
                                this.filters[j].cond = condition;
                                this.filters[j].val = encodeURIComponent(
                                    response
                                );
                                this.filters[j].label = label;

                                this.makeURL();
                            }
                        }

                        for (let j = 0; j < this.filters.length; j++) {
                            if (this.filters[j].column === 'search') {
                                search_found = true;
                            }
                        }

                        if (search_found === false) {
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
                    obj.column = column;
                    obj.cond = condition;
                    obj.val = encodeURIComponent(response);
                    obj.label = label;

                    this.filters.push(obj);

                    obj = {};

                    this.makeURL();
                }
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
            this.hitUrl();
        },

        searchData($event) {
            const searchValue = $event.data.searchValue;

            this.formURL(
                'search',
                'all',
                searchValue,
                this.translations.searchTitle
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
                val: currentPerPageSelection
            });

            this.makeURL();
        },

        filterData($event) {
            const { column, condition, response, label } = $event.data;

            this.formURL(column, condition, response, label);
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

        changePage($event) {
            const { pageLink } = $event.data;

            if (pageLink) {
                this.url = pageLink;
                this.hitUrl();
            }
        }
    }
};
</script>

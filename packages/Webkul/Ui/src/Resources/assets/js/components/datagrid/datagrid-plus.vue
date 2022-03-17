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
                            <div class="control-group">
                                <select
                                    class="control"
                                    name="channel"
                                    @change="
                                        changeExtraFilter($event, 'channel')
                                    "
                                >
                                    <option
                                        value="all"
                                        :selected="
                                            extraFilters.current.channel ==
                                                'all'
                                        "
                                        v-text="translations.allChannels"
                                    ></option>

                                    <option
                                        :key="channelKey"
                                        v-for="(channel,
                                        channelKey) in extraFilters.channels"
                                        v-text="channel.name"
                                        :value="channel.code"
                                        :selected="
                                            channel.code ==
                                                extraFilters.current.channel
                                        "
                                    ></option>
                                </select>
                            </div>
                        </div>

                        <div
                            class="dropdown-filters per-page"
                            v-if="extraFilters.locales != undefined"
                        >
                            <div class="control-group">
                                <select
                                    class="control"
                                    name="locale"
                                    @change="
                                        changeExtraFilter($event, 'locale')
                                    "
                                >
                                    <option
                                        value="all"
                                        :selected="
                                            extraFilters.current.locale == 'all'
                                        "
                                        v-text="translations.allLocales"
                                    ></option>

                                    <option
                                        :key="localeKey"
                                        v-for="(locale,
                                        localeKey) in extraFilters.locales"
                                        v-text="locale.name"
                                        :value="locale.code"
                                        :selected="
                                            locale.code ==
                                                extraFilters.current.locale
                                        "
                                    ></option>
                                </select>
                            </div>
                        </div>

                        <div
                            class="dropdown-filters per-page"
                            v-if="extraFilters.customer_groups != undefined"
                        >
                            <div class="control-group">
                                <select
                                    class="control"
                                    id="customer-group-switcher"
                                    name="customer_group"
                                    @change="
                                        changeExtraFilter(
                                            $event,
                                            'customer_group'
                                        )
                                    "
                                >
                                    <option
                                        value="all"
                                        :selected="
                                            extraFilters.current
                                                .customer_group == 'all'
                                        "
                                        v-text="translations.allCustomerGroups"
                                    ></option>

                                    <option
                                        :key="customerGroupKey"
                                        v-for="(customerGroup,
                                        customerGroupKey) in extraFilters.customer_groups"
                                        v-text="customerGroup.name"
                                        :value="customerGroup.id"
                                        :selected="
                                            customerGroup.id ==
                                                extraFilters.current
                                                    .customer_group
                                        "
                                    ></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="datagrid-filters" id="datagrid-filters">
                    <div class="">
                        <div class="search-filter">
                            <input
                                type="search"
                                id="search-field"
                                class="control"
                                :placeholder="translations.search"
                                v-model="searchValue"
                                v-on:keyup.enter="searchCollection(searchValue)"
                            />

                            <div class="icon-wrapper">
                                <span
                                    class="icon search-icon search-btn"
                                    v-on:click="searchCollection(searchValue)"
                                ></span>
                            </div>
                        </div>
                    </div>

                    <div class="filter-right">
                        <div class="dropdown-filters per-page">
                            <div class="control-group">
                                <label
                                    class="per-page-label"
                                    for="perPage"
                                    v-text="translations.itemsPerPage"
                                ></label>

                                <select
                                    id="perPage"
                                    name="perPage"
                                    class="control"
                                    v-model="perPage"
                                    v-on:change="paginate"
                                >
                                    <option
                                        v-for="index in this.perPageProduct"
                                        v-text="index"
                                        :key="index"
                                        :value="index"
                                    ></option>
                                </select>
                            </div>
                        </div>

                        <div class="dropdown-filters">
                            <div class="dropdown-toggle">
                                <div class="grid-dropdown-header">
                                    <span
                                        class="name"
                                        v-text="translations.filter"
                                    ></span>
                                    <i class="icon arrow-down-icon active"></i>
                                </div>
                            </div>

                            <div
                                class="dropdown-list dropdown-container"
                                style="display: none"
                            >
                                <ul>
                                    <li>
                                        <div class="control-group">
                                            <select
                                                class="filter-column-select control"
                                                v-model="filterColumn"
                                                v-on:change="
                                                    getColumnOrAlias(
                                                        filterColumn
                                                    )
                                                "
                                            >
                                                <option
                                                    v-text="translations.column"
                                                    selected
                                                    disabled
                                                ></option>

                                                <option
                                                    :key="columnKey"
                                                    v-for="(column,
                                                    columnKey) in columns"
                                                    :value="column.index"
                                                    v-text="column.label"
                                                    v-if="
                                                        typeof column.filterable !==
                                                            'undefined' &&
                                                            column.filterable
                                                    "
                                                ></option>
                                            </select>
                                        </div>
                                    </li>

                                    <li v-if="stringConditionSelect">
                                        <div class="control-group">
                                            <select
                                                class="control"
                                                v-model="stringCondition"
                                            >
                                                <option
                                                    v-text="
                                                        translations.condition
                                                    "
                                                    selected
                                                    disabled
                                                ></option>

                                                <option
                                                    v-text="
                                                        translations.contains
                                                    "
                                                    value="like"
                                                ></option>

                                                <option
                                                    v-text="
                                                        translations.ncontains
                                                    "
                                                    value="nlike"
                                                ></option>

                                                <option
                                                    v-text="translations.equals"
                                                    value="eq"
                                                ></option>

                                                <option
                                                    v-text="
                                                        translations.nequals
                                                    "
                                                    value="neqs"
                                                ></option>
                                            </select>
                                        </div>
                                    </li>

                                    <li v-if="stringCondition != null">
                                        <div class="control-group">
                                            <input
                                                type="text"
                                                class="control response-string"
                                                :placeholder="
                                                    translations.valueHere
                                                "
                                                v-model="stringValue"
                                            />
                                        </div>
                                    </li>

                                    <li v-if="numberConditionSelect">
                                        <div class="control-group">
                                            <select
                                                class="control"
                                                v-model="numberCondition"
                                            >
                                                <option
                                                    v-text="
                                                        translations.condition
                                                    "
                                                    selected
                                                    disabled
                                                ></option>

                                                <option
                                                    v-text="translations.equals"
                                                    value="eq"
                                                ></option>

                                                <option
                                                    v-text="
                                                        translations.nequals
                                                    "
                                                    value="neqs"
                                                ></option>

                                                <option
                                                    v-text="
                                                        translations.greater
                                                    "
                                                    value="gt"
                                                ></option>

                                                <option
                                                    v-text="translations.less"
                                                    value="lt"
                                                ></option>

                                                <option
                                                    v-text="
                                                        translations.greatere
                                                    "
                                                    value="gte"
                                                ></option>

                                                <option
                                                    v-text="translations.lesse"
                                                    value="lte"
                                                ></option>
                                            </select>
                                        </div>
                                    </li>

                                    <li v-if="numberCondition != null">
                                        <div class="control-group">
                                            <input
                                                type="text"
                                                class="control response-number"
                                                v-on:input="filterNumberInput"
                                                v-model="numberValue"
                                                :placeholder="
                                                    translations.numericValueHere
                                                "
                                            />
                                        </div>
                                    </li>

                                    <li v-if="booleanConditionSelect">
                                        <div class="control-group">
                                            <select
                                                class="control"
                                                v-model="booleanCondition"
                                            >
                                                <option
                                                    v-text="
                                                        translations.condition
                                                    "
                                                    selected
                                                    disabled
                                                ></option>

                                                <option
                                                    v-text="translations.equals"
                                                    value="eq"
                                                ></option>

                                                <option
                                                    v-text="
                                                        translations.nequals
                                                    "
                                                    value="neqs"
                                                ></option>
                                            </select>
                                        </div>
                                    </li>

                                    <li v-if="booleanCondition != null">
                                        <div class="control-group">
                                            <select
                                                class="control"
                                                v-model="booleanValue"
                                            >
                                                <option
                                                    v-text="translations.value"
                                                    selected
                                                    disabled
                                                ></option>

                                                <option
                                                    v-text="translations.true"
                                                    value="1"
                                                ></option>

                                                <option
                                                    v-text="translations.false"
                                                    value="0"
                                                ></option>
                                            </select>
                                        </div>
                                    </li>

                                    <li v-if="datetimeConditionSelect">
                                        <div class="control-group">
                                            <select
                                                class="control"
                                                v-model="datetimeCondition"
                                            >
                                                <option
                                                    v-text="
                                                        translations.condition
                                                    "
                                                    selected
                                                    disabled
                                                ></option>

                                                <option
                                                    v-text="translations.equals"
                                                    value="eq"
                                                ></option>

                                                <option
                                                    v-text="
                                                        translations.nequals
                                                    "
                                                    value="neqs"
                                                ></option>

                                                <option
                                                    v-text="
                                                        translations.greater
                                                    "
                                                    value="gt"
                                                ></option>

                                                <option
                                                    v-text="translations.less"
                                                    value="lt"
                                                ></option>

                                                <option
                                                    v-text="
                                                        translations.greatere
                                                    "
                                                    value="gte"
                                                ></option>

                                                <option
                                                    v-text="translations.lesse"
                                                    value="lte"
                                                ></option>
                                            </select>
                                        </div>
                                    </li>

                                    <li v-if="datetimeCondition != null">
                                        <div class="control-group">
                                            <input
                                                class="control"
                                                v-model="datetimeValue"
                                                type="date"
                                            />
                                        </div>
                                    </li>

                                    <button
                                        v-text="translations.apply"
                                        class="btn btn-sm btn-primary apply-filter"
                                        v-on:click="getResponse"
                                    ></button>
                                </ul>
                            </div>
                        </div>

                        <slot name="extra-filters"></slot>
                    </div>
                </div>
            </div>

            <div class="filter-advance">
                <div class="filtered-tags">
                    <span
                        :key="filterKey"
                        class="filter-tag"
                        v-if="filters.length > 0"
                        v-for="(filter, filterKey) in filters"
                        style="text-transform: capitalize"
                    >
                        <span v-if="filter.column == 'perPage'">perPage</span>

                        <span v-else>{{ filter.label }}</span>

                        <span class="wrapper" v-if="filter.prettyValue">
                            {{ filter.prettyValue }}
                            <span
                                class="icon cross-icon"
                                v-on:click="removeFilter(filter)"
                            ></span>
                        </span>

                        <span class="wrapper" v-else>
                            {{ decodeURIComponent(filter.val) }}
                            <span
                                class="icon cross-icon"
                                v-on:click="removeFilter(filter)"
                            ></span>
                        </span>
                    </span>
                </div>

                <div class="records-count-container">
                    <span class="datagrid-count">
                        {{ records.total }} {{ translations.recordsFound }}
                    </span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead v-if="massActionsToggle">
                        <tr
                            class="mass-action"
                            v-if="massActionsToggle"
                            style="height: 65px"
                        >
                            <th colspan="100%">
                                <div
                                    class="mass-action-wrapper"
                                    style="display: flex; flex-direction: row; align-items: center; justify-content: flex-start;"
                                >
                                    <span
                                        class="massaction-remove"
                                        v-on:click="removeMassActions"
                                        style="margin-right: 10px; margin-top: 5px"
                                    >
                                        <span
                                            class="icon checkbox-dash-icon"
                                        ></span>
                                    </span>

                                    <form
                                        method="POST"
                                        id="mass-action-form"
                                        style="display: inline-flex"
                                        action=""
                                        :onsubmit="
                                            `return confirm('${massActionConfirmText}')`
                                        "
                                    >
                                        <input
                                            type="hidden"
                                            name="_token"
                                            :value="csrf"
                                        />

                                        <input
                                            type="hidden"
                                            id="indexes"
                                            name="indexes"
                                            v-model="dataIds"
                                        />

                                        <div class="control-group">
                                            <select
                                                class="control"
                                                v-model="massActionType"
                                                @change="changeMassActionTarget"
                                                name="massaction-type"
                                                required
                                            >
                                                <option
                                                    v-for="(massAction,
                                                    index) in massActions"
                                                    v-text="massAction.label"
                                                    :key="index"
                                                    :value="{
                                                        id: index,
                                                        value: massAction.type
                                                    }"
                                                ></option>
                                            </select>
                                        </div>

                                        <div
                                            class="control-group"
                                            style="margin-left: 10px"
                                            v-if="
                                                massActionType.value == 'update'
                                            "
                                        >
                                            <select
                                                class="control"
                                                v-model="massActionUpdateValue"
                                                name="update-options"
                                                required
                                            >
                                                <option
                                                    :key="id"
                                                    v-for="(massActionValue,
                                                    id) in massActionValues"
                                                    :value="massActionValue"
                                                    v-text="id"
                                                ></option>
                                            </select>
                                        </div>

                                        <button
                                            v-text="translations.submit"
                                            type="submit"
                                            class="btn btn-sm btn-primary"
                                            style="margin-left: 10px; white-space: nowrap;"
                                        ></button>
                                    </form>
                                </div>
                            </th>
                        </tr>
                    </thead>

                    <thead v-if="massActionsToggle == false">
                        <tr style="height: 65px">
                            <th
                                v-if="enableMassActions"
                                class="grid_head"
                                id="mastercheckbox"
                                style="width: 50px"
                            >
                                <span class="checkbox">
                                    <input
                                        type="checkbox"
                                        v-model="allSelected"
                                        v-on:change="selectAll"
                                        :disabled="!records.data.length"
                                    />

                                    <label
                                        class="checkbox-view"
                                        for="checkbox"
                                    ></label>
                                </span>
                            </th>

                            <th
                                :key="columnKey"
                                v-for="(column, columnKey) in columns"
                                v-text="column.label"
                                class="grid_head"
                                :class="{ sortable: column.sortable }"
                                :style="
                                    typeof column.width !== 'undefined' &&
                                    column.width
                                        ? `width: ${column.width}`
                                        : ''
                                "
                                v-on:click="
                                    typeof column.sortable !== 'undefined' &&
                                    column.sortable
                                        ? sortCollection(column.index)
                                        : {}
                                "
                            ></th>

                            <th
                                v-if="enableActions"
                                v-text="translations.actions"
                            ></th>
                        </tr>
                    </thead>

                    <tbody>
                        <template v-if="records.data.length">
                            <tr
                                :key="recordKey"
                                v-for="(record, recordKey) in records.data"
                            >
                                <td v-if="enableMassActions">
                                    <span class="checkbox">
                                        <input
                                            type="checkbox"
                                            v-model="dataIds"
                                            @change="select"
                                            :value="record[index]"
                                        />

                                        <label
                                            class="checkbox-view"
                                            for="checkbox"
                                        ></label>
                                    </span>
                                </td>

                                <td
                                    :key="columnKey"
                                    v-for="(column, columnKey) in columns"
                                    v-html="record[column.index]"
                                    :data-value="column.label"
                                ></td>

                                <td
                                    class="actions"
                                    style="white-space: nowrap; width: 100px"
                                    :data-value="translations.actions"
                                >
                                    <div class="action">
                                        <a
                                            :key="actionIndex"
                                            v-for="(action,
                                            actionIndex) in actions"
                                            v-if="
                                                record[
                                                    `${action.key}_to_display`
                                                ]
                                            "
                                            :id="
                                                record[
                                                    typeof action.index !==
                                                        'undefined' &&
                                                    action.index
                                                        ? action.index
                                                        : index
                                                ]
                                            "
                                            :href="
                                                action.method == 'GET'
                                                    ? record[
                                                          `${action.key}_url`
                                                      ]
                                                    : 'javascript:void(0);'
                                            "
                                            v-on:click="
                                                action.method != 'GET'
                                                    ? doAction($event)
                                                    : {}
                                            "
                                            :data-method="action.method"
                                            :data-action="
                                                record[`${action.key}_url`]
                                            "
                                            :data-token="csrf"
                                            :target="
                                                typeof action.target !==
                                                    'undefined' && action.target
                                                    ? action.target
                                                    : ''
                                            "
                                            :title="
                                                typeof action.title !==
                                                    'undefined' && action.title
                                                    ? action.title
                                                    : ''
                                            "
                                        >
                                            <span :class="action.icon"></span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <template v-else>
                            <tr>
                                <td colspan="10">
                                    <p
                                        style="text-align: center"
                                        v-text="translations.norecords"
                                    ></p>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <div
            class="pagination shop mt-50"
            v-if="
                typeof paginated !== 'undefined' &&
                    paginated &&
                    records.last_page > 1
            "
        >
            <a
                v-for="(link, index) in records.links"
                :key="index"
                href="javascript:void(0);"
                :data-page="link.url"
                :class="
                    `page-item ${index == 0 ? 'previous' : ''} ${
                        link.active ? 'active' : ''
                    } ${index == records.links.length - 1 ? 'next' : ''}`
                "
                @click="changePage(link.url)"
            >
                <i class="icon angle-left-icon" v-if="index == 0"></i>

                <i
                    class="icon angle-right-icon"
                    v-else-if="index == records.links.length - 1"
                ></i>

                <span v-text="link.label" v-else></span>
            </a>
        </div>
    </div>
</template>

<script>
export default {
    props: ['src'],

    data: function() {
        return {
            id: btoa(this.src),
            url: this.src,
            isDataLoaded: false,
            dataGridIndex: 0,
            massActionsToggle: false,
            massActionTarget: null,
            massActionType: this.getDefaultMassActionType(),
            massActionValues: [],
            massActionTargets: [],
            massActionUpdateValue: null,
            currentSort: null,
            dataIds: [],
            allSelected: false,
            sortDesc: 'desc',
            sortAsc: 'asc',
            sortUpIcon: 'sort-up-icon',
            sortDownIcon: 'sort-down-icon',
            currentSortIcon: null,
            isActive: false,
            isHidden: true,
            searchValue: '',
            filterColumn: true,
            filters: [],
            columnOrAlias: '',
            type: null,
            stringCondition: null,
            booleanCondition: null,
            numberCondition: null,
            datetimeCondition: null,
            stringValue: null,
            booleanValue: null,
            datetimeValue: '2000-01-01',
            numberValue: 0,
            stringConditionSelect: false,
            booleanConditionSelect: false,
            numberConditionSelect: false,
            datetimeConditionSelect: false,
            perPageProduct: [10, 20, 30, 40, 50]
        };
    },

    mounted: function() {
        this.getCsrf();

        this.makeURL();
    },

    methods: {
        hitUrl: function() {
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

        analyzeDatagridsInfo: function() {
            if (!this.isDataLoaded && this.url === `${this.src}?v=1`) {
                let datagridInfo = this.getCurrentDatagridInfo();

                if (datagridInfo) {
                    /**
                     * Will check this later. Don't remove it.
                     */
                    // this.filterCurrentDatagridFromDatagridsInfo();

                    this.url = datagridInfo.previousUrl;
                    this.filters = datagridInfo.previousFilters;
                }
            } else {
                let datagridsInfo = this.getDatagridsInfo();

                if (datagridsInfo && datagridsInfo.length > 0) {
                    if (this.isCurrentDatagridInfoExists()) {
                        datagridsInfo = datagridsInfo.map(datagrid => {
                            if (datagrid.id === this.id) {
                                return this.getDatagridsInfoDefaults();
                            }

                            return datagrid;
                        });
                    } else {
                        datagridsInfo.push(this.getDatagridsInfoDefaults());
                    }
                } else {
                    datagridsInfo = [this.getDatagridsInfoDefaults()];
                }

                this.updateDatagridsInfo(datagridsInfo);
            }
        },

        isCurrentDatagridInfoExists: function() {
            let datagridsInfo = this.getDatagridsInfo();

            return !!datagridsInfo.find(({ id }) => id === this.id);
        },

        getCurrentDatagridInfo: function() {
            let datagridsInfo = this.getDatagridsInfo();

            return this.isCurrentDatagridInfoExists()
                ? datagridsInfo.find(({ id }) => id === this.id)
                : null;
        },

        getDatagridsInfoStorageKey: function() {
            return 'datagridsInfo';
        },

        getDatagridsInfoDefaults: function() {
            return {
                id: this.id,
                previousFilters: this.filters,
                previousUrl: this.url
            };
        },

        getDatagridsInfo: function() {
            let storageInfo = localStorage.getItem(
                this.getDatagridsInfoStorageKey()
            );

            return !this.isValidJsonString(storageInfo)
                ? []
                : JSON.parse(storageInfo) ?? [];
        },

        updateDatagridsInfo: function(info) {
            localStorage.setItem(
                this.getDatagridsInfoStorageKey(),
                JSON.stringify(info)
            );
        },

        filterCurrentDatagridFromDatagridsInfo: function() {
            let datagridsInfo = this.getDatagridsInfo();

            datagridsInfo = datagridsInfo.filter(({ id }) => id !== this.id);

            this.updateDatagridsInfo(datagridsInfo);
        },

        isValidJsonString: function(str) {
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        },

        initDatagrid: function() {
            this.setParamsAndUrl();

            this.initDataParams();
        },

        initResponseProps: function(results) {
            for (let property in results) {
                this[property] = results[property];
            }
        },

        initDataParams: function() {
            if (this.filters.length) {
                for (let i = 0; i < this.filters.length; i++) {
                    if (this.filters[i].column === 'perPage') {
                        this.perPage = this.filters[i].val;
                    }
                }
            }

            if (this.perPageProduct.indexOf(parseInt(this.perPage)) === -1) {
                this.perPageProduct.unshift(this.perPage);
            }

            this.isDataLoaded = true;
            this.filterIndex = this.index;
            this.gridCurrentData = this.records;
            this.perPage = this.itemsPerPage;
            this.massActionConfirmText = this.translations.clickOnAction;
        },

        changePage: function(url) {
            if (url) {
                this.url = url;
                this.hitUrl();
            }
        },

        changeExtraFilter: function(event, key) {
            let url = new URL(this.src);
            url.searchParams.set(key, event.target.value);

            this.url = url.href;
            this.hitUrl();
        },

        getColumnOrAlias: function(columnOrAlias) {
            this.columnOrAlias = columnOrAlias;

            for (let column in this.columns) {
                if (this.columns[column].index === this.columnOrAlias) {
                    this.type = this.columns[column].type;

                    switch (this.type) {
                        case 'string': {
                            this.stringConditionSelect = true;
                            this.datetimeConditionSelect = false;
                            this.booleanConditionSelect = false;
                            this.numberConditionSelect = false;

                            this.nullify();
                            break;
                        }

                        case 'datetime': {
                            this.datetimeConditionSelect = true;
                            this.stringConditionSelect = false;
                            this.booleanConditionSelect = false;
                            this.numberConditionSelect = false;

                            this.nullify();
                            break;
                        }

                        case 'boolean': {
                            this.booleanConditionSelect = true;
                            this.datetimeConditionSelect = false;
                            this.stringConditionSelect = false;
                            this.numberConditionSelect = false;

                            this.nullify();
                            break;
                        }

                        case 'number': {
                            this.numberConditionSelect = true;
                            this.booleanConditionSelect = false;
                            this.datetimeConditionSelect = false;
                            this.stringConditionSelect = false;

                            this.nullify();
                            break;
                        }

                        case 'price': {
                            this.numberConditionSelect = true;
                            this.booleanConditionSelect = false;
                            this.datetimeConditionSelect = false;
                            this.stringConditionSelect = false;

                            this.nullify();
                            break;
                        }
                    }
                }
            }
        },

        nullify: function() {
            this.stringCondition = null;
            this.datetimeCondition = null;
            this.booleanCondition = null;
            this.numberCondition = null;
        },

        filterNumberInput: function(e) {
            this.numberValue = e.target.value.replace(/[^0-9\,\.]+/g, '');
        },

        getResponse: function() {
            let label = '';

            for (let colIndex in this.columns) {
                if (this.columns[colIndex].index == this.columnOrAlias) {
                    label = this.columns[colIndex].label;
                    break;
                }
            }

            if (this.type === 'string' && this.stringValue !== null) {
                this.formURL(
                    this.columnOrAlias,
                    this.stringCondition,
                    encodeURIComponent(this.stringValue),
                    label
                );
            } else if (this.type === 'number') {
                let indexConditions = true;

                if (
                    this.filterIndex === this.columnOrAlias &&
                    (this.numberValue === 0 || this.numberValue < 0)
                ) {
                    indexConditions = false;

                    alert(this.translations.zeroIndex);
                }

                if (indexConditions) {
                    this.formURL(
                        this.columnOrAlias,
                        this.numberCondition,
                        this.numberValue,
                        label
                    );
                }
            } else if (this.type === 'boolean') {
                this.formURL(
                    this.columnOrAlias,
                    this.booleanCondition,
                    this.booleanValue,
                    label
                );
            } else if (this.type === 'datetime') {
                this.formURL(
                    this.columnOrAlias,
                    this.datetimeCondition,
                    this.datetimeValue,
                    label
                );
            } else if (this.type === 'price') {
                this.formURL(
                    this.columnOrAlias,
                    this.numberCondition,
                    this.numberValue,
                    label
                );
            }
        },

        sortCollection: function(alias) {
            let label = '';

            for (let colIndex in this.columns) {
                if (this.columns[colIndex].index === alias) {
                    let matched = 0;
                    label = this.columns[colIndex].label;
                    break;
                }
            }

            this.formURL('sort', alias, this.sortAsc, label);
        },

        searchCollection: function(searchValue) {
            this.formURL(
                'search',
                'all',
                searchValue,
                this.translations.searchTitle
            );
        },

        setParamsAndUrl: function() {
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

        findCurrentSort: function() {
            for (let i in this.filters) {
                if (this.filters[i].column === 'sort') {
                    this.currentSort = this.filters[i].val;
                }
            }
        },

        getDefaultMassActionType: function() {
            return {
                id: null,
                value: null
            };
        },

        changeMassActionTarget: function() {
            if (this.massActionType.value === 'delete') {
                for (let i in this.massActionTargets) {
                    if (this.massActionTargets[i].type === 'delete') {
                        this.massActionTarget = this.massActionTargets[
                            i
                        ].action;
                        this.massActionConfirmText = this.massActionTargets[i]
                            .confirm_text
                            ? this.massActionTargets[i].confirm_text
                            : this.massActionConfirmText;

                        break;
                    }
                }
            }

            if (this.massActionType.value === 'update') {
                for (let i in this.massActionTargets) {
                    if (this.massActionTargets[i].type === 'update') {
                        this.massActionValues = this.massActions[
                            this.massActionType.id
                        ].options;
                        this.massActionTarget = this.massActionTargets[
                            i
                        ].action;
                        this.massActionConfirmText = this.massActionTargets[i]
                            .confirm_text
                            ? this.massActionTargets[i].confirm_text
                            : this.massActionConfirmText;

                        break;
                    }
                }
            }

            document.getElementById(
                'mass-action-form'
            ).action = this.massActionTarget;
        },

        formURL: function(column, condition, response, label) {
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
                                        this.filters[j].val = this.sortDesc;

                                        this.makeURL();
                                    } else {
                                        this.filters[j].column = column;
                                        this.filters[j].cond = condition;
                                        this.filters[j].val = this.sortAsc;

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
                                this.currentSort = this.sortAsc;

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

        makeURL: function() {
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

        removeFilter: function(filter) {
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

        select: function() {
            this.allSelected = false;

            if (this.dataIds.length === 0) {
                this.massActionsToggle = false;
                this.massActionType = this.getDefaultMassActionType();
            } else {
                this.massActionsToggle = true;
            }
        },

        selectAll: function() {
            this.dataIds = [];

            this.massActionsToggle = true;

            if (this.allSelected) {
                if (this.gridCurrentData.hasOwnProperty('data')) {
                    for (let currentData in this.gridCurrentData.data) {
                        let i = 0;
                        for (let currentId in this.gridCurrentData.data[
                            currentData
                        ]) {
                            if (i == 0) {
                                this.dataIds.push(
                                    this.gridCurrentData.data[currentData][
                                        this.filterIndex
                                    ]
                                );
                            }

                            i++;
                        }
                    }
                } else {
                    for (let currentData in this.gridCurrentData) {
                        let i = 0;
                        for (let currentId in this.gridCurrentData[
                            currentData
                        ]) {
                            if (i === 0)
                                this.dataIds.push(
                                    this.gridCurrentData[currentData][currentId]
                                );

                            i++;
                        }
                    }
                }
            }
        },

        captureColumn: function(id) {
            element = document.getElementById(id);
        },

        removeMassActions: function() {
            this.dataIds = [];

            this.massActionsToggle = false;

            this.allSelected = false;

            this.massActionType = this.getDefaultMassActionType();
        },

        paginate: function(e) {
            for (let i = 0; i < this.filters.length; i++) {
                if (this.filters[i].column == 'perPage') {
                    this.filters.splice(i, 1);
                }
            }

            this.filters.push({
                column: 'perPage',
                cond: 'eq',
                val: e.target.value
            });

            this.makeURL();
        },

        doAction: function(e, message, type) {
            let self = this;

            let element = e.currentTarget;

            if (message) {
                element = e.target.parentElement;
            }

            message = message || this.translations.massActionDelete;

            if (confirm(message)) {
                axios
                    .post(element.getAttribute('data-action'), {
                        _token: element.getAttribute('data-token'),
                        _method: element.getAttribute('data-method')
                    })
                    .then(function(response) {
                        /**
                         * If refirect is true, then pass redirect url in the response.
                         *
                         * Else, it will reload table only.
                         */
                        if (response.data.redirect) {
                            window.location.href = response.data.redirectUrl;
                        } else {
                            self.hitUrl();

                            window.flashMessages.push({
                                type: 'alert-success',
                                message: response.data.message
                            });

                            self.$root.addFlashMessages();
                        }
                    })
                    .catch(function(error) {
                        let response = error.response;

                        window.flashMessages.push({
                            type: 'alert-error',
                            message:
                                response.data.message ?? 'Something went wrong!'
                        });

                        self.$root.addFlashMessages();
                    });

                e.preventDefault();
            } else {
                e.preventDefault();
            }
        },

        getCsrf: function() {
            let token = document.head.querySelector('meta[name="csrf-token"]');

            if (token) {
                this.csrf = token.content;
            } else {
                console.error(
                    'CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token'
                );
            }
        }
    }
};
</script>

@php
    /* all locales */
    $locales = core()->getAllLocales();

    /* request and fallback handling */
    $locale = core()->getRequestedLocaleCode();
    $channel = core()->getRequestedChannelCode();
    $customer_group = core()->getRequestedCustomerGroupCode();

    /* handling cases for new locale if not present in current channel */
    if ($channel !== 'all') {
        $channelLocales = app('Webkul\Core\Repositories\ChannelRepository')->findOneByField('code', $channel)->locales;

        if ($channelLocales->contains('code', $locale)) {
            $locales = $channelLocales;
        } else {
            $channel = 'all';
        }
    }
@endphp

<div class="table">
    <datagrid-filters></datagrid-filters>

    @if (isset($results['paginated']) && $results['paginated'])
        @include('ui::datagrid.pagination', ['results' => $results['records']])
    @endif

    @push('scripts')
        <script type="text/x-template" id="datagrid-filters">
            <div class="grid-container">

                <div class="grid-top">

                    <div class="datagrid-filters">
                        <div class="filter-left">
                            @if (isset($results['extraFilters']['channels']))
                                <div class="dropdown-filters per-page">
                                    <div class="control-group">
                                        <select class="control" id="channel-switcher" name="channel"
                                                onchange="reloadPage('channel', this.value)">
                                            <option value="all" {{ ! isset($channel) ? 'selected' : '' }}>
                                                {{ __('admin::app.admin.system.all-channels') }}
                                            </option>
                                            @foreach ($results['extraFilters']['channels'] as $channelModel)
                                                <option
                                                    value="{{ $channelModel->code }}"
                                                    {{ (isset($channel) && ($channelModel->code) == $channel) ? 'selected' : '' }}>
                                                    {{ core()->getChannelName($channelModel) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                            @if (isset($results['extraFilters']['locales']))
                                <div class="dropdown-filters per-page">
                                    <div class="control-group">
                                        <select class="control" id="locale-switcher" name="locale"
                                                onchange="reloadPage('locale', this.value)">
                                            <option value="all" {{ ! isset($locale) ? 'selected' : '' }}>
                                                {{ __('admin::app.admin.system.all-locales') }}
                                            </option>
                                            @foreach ($locales as $localeModel)
                                                <option
                                                    value="{{ $localeModel->code }}" {{ (isset($locale) && ($localeModel->code) == $locale) ? 'selected' : '' }}>
                                                    {{ $localeModel->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                            @if (isset($results['extraFilters']['customer_groups']))
                                <div class="dropdown-filters per-page">
                                    <div class="control-group">
                                        <select class="control" id="customer-group-switcher" name="customer_group"
                                                onchange="reloadPage('customer_group', this.value)">
                                            <option value="all" {{ ! isset($customer_group) ? 'selected' : '' }}>
                                                {{ __('admin::app.admin.system.all-customer-groups') }}
                                            </option>
                                            @foreach ($results['extraFilters']['customer_groups'] as $customerGroupModel)
                                                <option
                                                    value="{{ $customerGroupModel->id }}"
                                                    {{ (isset($customer_group) && ($customerGroupModel->id) == $customer_group) ? 'selected' : '' }}>
                                                    {{ $customerGroupModel->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="datagrid-filters" id="datagrid-filters">
                        <div>
                            <div class="search-filter">
                                <input type="search" id="search-field" class="control"
                                    placeholder="{{ __('ui::app.datagrid.search') }}" v-model="searchValue"
                                    v-on:keyup.enter="searchCollection(searchValue)"/>
                                <div class="icon-wrapper">
                                    <span class="icon search-icon search-btn"
                                        v-on:click="searchCollection(searchValue)"></span>
                                </div>
                            </div>
                        </div>

                        <div class="filter-right">
                            <div class="dropdown-filters per-page">
                                <div class="control-group">
                                    <label class="per-page-label" for="perPage">
                                        {{ __('ui::app.datagrid.items-per-page') }}
                                    </label>

                                    <select id="perPage" name="perPage" class="control" v-model="perPage"
                                            v-on:change="paginate">
                                        <option v-for="index in this.perPageProduct" :key="index" :value="index"> @{{ index }} </option>
                                    </select>
                                </div>
                            </div>

                            <div class="dropdown-filters">
                                <div class="dropdown-toggle">
                                    <div class="grid-dropdown-header">
                                        <span class="name">{{ __('ui::app.datagrid.filter') }}</span>
                                        <i class="icon arrow-down-icon active"></i>
                                    </div>
                                </div>

                                <div class="dropdown-list dropdown-container" style="display: none;">
                                    <ul>
                                        <li>
                                            <div class="control-group">
                                                <select class="filter-column-select control" v-model="filterColumn"
                                                        v-on:change="getColumnOrAlias(filterColumn)">
                                                    <option selected disabled>{{ __('ui::app.datagrid.column') }}</option>
                                                    @foreach($results['columns'] as $column)
                                                        @if(isset($column['filterable']) && $column['filterable'])
                                                            <option value="{{ $column['index'] }}">
                                                                {{ $column['label'] }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </li>

                                        {{-- suitable for string columns --}}
                                        <li v-if='stringConditionSelect'>
                                            <div class="control-group">
                                                <select class="control" v-model="stringCondition">
                                                    <option selected
                                                            disabled>{{ __('ui::app.datagrid.condition') }}</option>
                                                    <option value="like">{{ __('ui::app.datagrid.contains') }}</option>
                                                    <option value="nlike">{{ __('ui::app.datagrid.ncontains') }}</option>
                                                    <option value="eq">{{ __('ui::app.datagrid.equals') }}</option>
                                                    <option value="neqs">{{ __('ui::app.datagrid.nequals') }}</option>
                                                </select>
                                            </div>
                                        </li>

                                        {{-- Response fields based on the type of columns to be filtered --}}
                                        <li v-if='stringCondition != null'>
                                            <div class="control-group">
                                                <input type="text" class="control response-string"
                                                    placeholder="{{ __('ui::app.datagrid.value-here') }}"
                                                    v-model="stringValue"/>
                                            </div>
                                        </li>

                                        {{-- suitable for numeric columns --}}
                                        <li v-if='numberConditionSelect'>
                                            <div class="control-group">
                                                <select class="control" v-model="numberCondition">
                                                    <option selected
                                                            disabled>{{ __('ui::app.datagrid.condition') }}</option>
                                                    <option value="eq">{{ __('ui::app.datagrid.equals') }}</option>
                                                    <option value="neqs">{{ __('ui::app.datagrid.nequals') }}</option>
                                                    <option value="gt">{{ __('ui::app.datagrid.greater') }}</option>
                                                    <option value="lt">{{ __('ui::app.datagrid.less') }}</option>
                                                    <option value="gte">{{ __('ui::app.datagrid.greatere') }}</option>
                                                    <option value="lte">{{ __('ui::app.datagrid.lesse') }}</option>
                                                </select>
                                            </div>
                                        </li>

                                        <li v-if='numberCondition != null'>
                                            <div class="control-group">
                                                <input type="text" class="control response-number" v-on:input="filterNumberInput" placeholder="{{ __('ui::app.datagrid.numeric-value-here') }}"  v-model="numberValue"/>
                                            </div>
                                        </li>

                                        {{-- suitable for boolean columns --}}
                                        <li v-if='booleanConditionSelect'>
                                            <div class="control-group">
                                                <select class="control" v-model="booleanCondition">
                                                    <option selected
                                                            disabled>{{ __('ui::app.datagrid.condition') }}</option>
                                                    <option value="eq">{{ __('ui::app.datagrid.equals') }}</option>
                                                    <option value="neqs">{{ __('ui::app.datagrid.nequals') }}</option>
                                                </select>
                                            </div>
                                        </li>

                                        <li v-if='booleanCondition != null'>
                                            <div class="control-group">
                                                <select class="control" v-model="booleanValue">
                                                    <option selected disabled>{{ __('ui::app.datagrid.value') }}</option>
                                                    <option value="1">{{ __('ui::app.datagrid.true') }}</option>
                                                    <option value="0">{{ __('ui::app.datagrid.false') }}</option>
                                                </select>
                                            </div>
                                        </li>

                                        {{-- suitable for date/time columns --}}
                                        <li v-if='datetimeConditionSelect'>
                                            <div class="control-group">
                                                <select class="control" v-model="datetimeCondition">
                                                    <option selected disabled>{{ __('ui::app.datagrid.condition') }}</option>
                                                    <option value="eq">{{ __('ui::app.datagrid.equals') }}</option>
                                                    <option value="neqs">{{ __('ui::app.datagrid.nequals') }}</option>
                                                    <option value="gt">{{ __('ui::app.datagrid.greater') }}</option>
                                                    <option value="lt">{{ __('ui::app.datagrid.less') }}</option>
                                                    <option value="gte">{{ __('ui::app.datagrid.greatere') }}</option>
                                                    <option value="lte">{{ __('ui::app.datagrid.lesse') }}</option>
                                                </select>
                                            </div>
                                        </li>

                                        <li v-if='datetimeCondition != null'>
                                            <div class="control-group">
                                                <input class="control" v-model="datetimeValue" type="date">
                                            </div>
                                        </li>

                                        <button class="btn btn-sm btn-primary apply-filter"
                                                v-on:click="getResponse">{{ __('ui::app.datagrid.apply') }}</button>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="filter-advance">
                    <div class="filtered-tags">
                        <span class="filter-tag" v-if="filters.length > 0" v-for="filter in filters" style="text-transform: capitalize;">
                            <span v-if="filter.column == 'perPage'">perPage</span>
                            <span v-else>@{{ filter.label }}</span>

                            <span class="wrapper" v-if="filter.prettyValue">
                                @{{ filter.prettyValue }}
                                <span class="icon cross-icon" v-on:click="removeFilter(filter)"></span>
                            </span>
                            <span class="wrapper" v-else>
                                @{{ decodeURIComponent(filter.val) }}
                                <span class="icon cross-icon" v-on:click="removeFilter(filter)"></span>
                            </span>
                        </span>
                    </div>
                    
                    <div class="records-count-container">
                        <span class="datagrid-count">
                            {{ $results['records']->total()  }}   {{ __('admin::app.admin.system.records-found') }}
                        </span>
                    </div>
                </div>                                        
                
                <div class="table-responsive">
                    <table class="table">
                        @include('ui::datagrid.partials.mass-action-header')

                        @include('ui::datagrid.partials.default-header')

                        @include('ui::datagrid.body', ['records' => $results['records'], 'actions' => $results['actions'], 'index' => $results['index'], 'columns' => $results['columns'],'enableMassActions' => $results['enableMassActions'], 'enableActions' => $results['enableActions'], 'norecords' => $results['norecords']])
                    </table>
                </div>                
            </div>
        </script>

        <script>
            Vue.component('datagrid-filters', {
                template: '#datagrid-filters',

                data: function () {
                    return {
                        filterIndex: @json($results['index']),
                        gridCurrentData: @json($results['records']),
                        massActions: @json($results['massactions']),
                        massActionsToggle: false,
                        massActionTarget: null,
                        massActionConfirmText: '{{ __('ui::app.datagrid.click_on_action') }}',
                        massActionType: this.getDefaultMassActionType(),
                        massActionValues: [],
                        massActionTargets: [],
                        massActionUpdateValue: null,
                        url: new URL(window.location.href),
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
                        columns: @json($results['columns']),
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
                        perPage: {{ $results['itemsPerPage'] ?: 10 }},
                        perPageProduct: [10, 20, 30, 40, 50],
                        extraFilters: @json($results['extraFilters']),
                    }
                },

                mounted: function () {
                    this.setParamsAndUrl();

                    this.checkedSelectedCheckbox();

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
                },

                methods: {
                    getColumnOrAlias: function (columnOrAlias) {
                        this.columnOrAlias = columnOrAlias;

                        for (column in this.columns) {
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

                    nullify: function () {
                        this.stringCondition = null;
                        this.datetimeCondition = null;
                        this.booleanCondition = null;
                        this.numberCondition = null;
                    },

                    filterNumberInput: function(e){
                        this.numberValue = e.target.value.replace(/[^0-9\,\.]+/g, '');
                    },

                    getResponse: function() {
                        label = '';

                        for (let colIndex in this.columns) {
                            if (this.columns[colIndex].index == this.columnOrAlias) {
                                label = this.columns[colIndex].label;
                                break;
                            }
                        }

                        if (this.type === 'string' && this.stringValue !== null) {
                            this.formURL(this.columnOrAlias, this.stringCondition, encodeURIComponent(this.stringValue), label)
                        } else if (this.type === 'number') {
                            indexConditions = true;

                            if (this.filterIndex === this.columnOrAlias
                                && (this.numberValue === 0 || this.numberValue < 0)) {
                                indexConditions = false;

                                alert('{{__('ui::app.datagrid.zero-index')}}');
                            }

                            if (indexConditions) {
                                this.formURL(this.columnOrAlias, this.numberCondition, this.numberValue, label);
                            }
                        } else if (this.type === 'boolean') {
                            this.formURL(this.columnOrAlias, this.booleanCondition, this.booleanValue, label);
                        } else if (this.type === 'datetime') {
                            this.formURL(this.columnOrAlias, this.datetimeCondition, this.datetimeValue, label);
                        } else if (this.type === 'price') {
                            this.formURL(this.columnOrAlias, this.numberCondition, this.numberValue, label);
                        }
                    },

                    sortCollection: function (alias) {
                        let label = '';

                        for (let colIndex in this.columns) {
                            if (this.columns[colIndex].index === alias) {
                                matched = 0;
                                label = this.columns[colIndex].label;
                                break;
                            }
                        }

                        this.formURL("sort", alias, this.sortAsc, label);
                    },

                    searchCollection: function (searchValue) {
                        this.formURL("search", 'all', searchValue, '{{ __('ui::app.datagrid.search-title') }}');
                    },

                    /**
                     * Function triggered to check whether the query exists or not and then
                     * call the make filters from the url.
                     */
                    setParamsAndUrl: function () {
                        params = (new URL(window.location.href)).search;

                        if (params.slice(1, params.length).length > 0) {
                            this.arrayFromUrl();
                        }

                        for (let id in this.massActions) {
                            this.massActionTargets.push({
                                'id': parseInt(id),
                                'type': this.massActions[id].type,
                                'action': this.massActions[id].action,
                                'confirm_text': this.massActions[id].confirm_text
                            });
                        }
                    },

                    findCurrentSort: function () {
                        for (let i in this.filters) {
                            if (this.filters[i].column === 'sort') {
                                this.currentSort = this.filters[i].val;
                            }
                        }
                    },

                    /**
                     * Reset mass action type.
                     *
                     * @return {!object}
                     */
                    getDefaultMassActionType: function () {
                        return {
                            id: null,
                            value: null
                        };
                    },

                    /**
                     * Change mass action target.
                     */
                    changeMassActionTarget: function () {
                        if (this.massActionType.value === 'delete') {
                            for (let i in this.massActionTargets) {
                                if (this.massActionTargets[i].type === 'delete') {
                                    this.massActionTarget = this.massActionTargets[i].action;
                                    this.massActionConfirmText = this.massActionTargets[i].confirm_text ? this.massActionTargets[i].confirm_text : this.massActionConfirmText;

                                    break;
                                }
                            }
                        }

                        if (this.massActionType.value === 'update') {
                            for (let i in this.massActionTargets) {
                                if (this.massActionTargets[i].type === 'update') {
                                    this.massActionValues = this.massActions[this.massActionType.id].options;
                                    this.massActionTarget = this.massActionTargets[i].action;
                                    this.massActionConfirmText = this.massActionTargets[i].confirm_text ? this.massActionTargets[i].confirm_text : this.massActionConfirmText;

                                    break;
                                }
                            }
                        }

                        document.getElementById('mass-action-form').action = this.massActionTarget;
                    },

                    /**
                     * Make array of filters, sort and search.
                     */
                    formURL: function (column, condition, response, label) {
                        var obj = {};

                        if (column === "" || condition === "" || response === ""
                            || column === null || condition === null || response === null) {
                            alert('{{ __('ui::app.datagrid.filter-fields-missing') }}');

                            return false;
                        } else {

                            if (this.filters.length > 0) {
                                if (column !== "sort" && column !== "search") {
                                    let filterRepeated = false;

                                    for (let j = 0; j < this.filters.length; j++) {
                                        if (this.filters[j].column === column) {
                                            if (this.filters[j].cond === condition && this.filters[j].val === response) {
                                                filterRepeated = true;

                                                alert('{{ __('ui::app.datagrid.filter-exists') }}');

                                                return false;
                                            } else if (this.filters[j].cond === condition && this.filters[j].val !== response) {
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

                                if (column === "sort") {
                                    let sort_exists = false;

                                    for (let j = 0; j < this.filters.length; j++) {
                                        if (this.filters[j].column === "sort") {
                                            if (this.filters[j].column === column && this.filters[j].cond === condition) {
                                                this.findCurrentSort();

                                                if (this.currentSort === "asc") {
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

                                if (column === "search") {
                                    let search_found = false;

                                    for (let j = 0; j < this.filters.length; j++) {
                                        if (this.filters[j].column === "search") {
                                            this.filters[j].column = column;
                                            this.filters[j].cond = condition;
                                            this.filters[j].val = encodeURIComponent(response);
                                            this.filters[j].label = label;

                                            this.makeURL();
                                        }
                                    }

                                    for (let j = 0; j < this.filters.length; j++) {
                                        if (this.filters[j].column === "search") {
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

                    /**
                     * Make the url from the array and redirect.
                     */
                    makeURL: function () {
                        newParams = '';

                        for(let i = 0; i < this.filters.length; i++) {
                            if (this.filters[i].column == 'status' || this.filters[i].column == 'value_per_locale' || this.filters[i].column == 'value_per_channel' || this.filters[i].column == 'is_unique') {
                                if (this.filters[i].val.includes("True")) {
                                    this.filters[i].val = 1;
                                } else if (this.filters[i].val.includes("False")) {
                                    this.filters[i].val = 0;
                                }
                            }

                            let condition = '';
                            if (this.filters[i].cond !== undefined) {
                                condition = '[' + this.filters[i].cond + ']';
                            }

                            if (i == 0) {
                                newParams = '?' + this.filters[i].column + condition + '=' + this.filters[i].val;
                            } else {
                                newParams = newParams + '&' + this.filters[i].column + condition + '=' + this.filters[i].val;
                            }
                        }

                        var uri = window.location.href.toString();

                        var clean_uri = uri.substring(0, uri.indexOf("?")).trim();

                        window.location.href = clean_uri + newParams;
                    },

                    /**
                     * Make the filter array from url after being redirected.
                     */
                    arrayFromUrl: function () {

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
                            obj.cond = key.replace(']', '').split('[')[1]
                            obj.val = value;

                            switch (obj.column) {
                                case "search":
                                    obj.label = "{{ __('ui::app.datagrid.search-title') }}";
                                    break;
                                case "channel":
                                    obj.label = "{{ __('ui::app.datagrid.channel') }}";
                                    if ('channels' in this.extraFilters) {
                                        obj.prettyValue = this.extraFilters['channels'].find(channel => channel.code == obj.val);

                                        if (obj.prettyValue !== undefined) {
                                            obj.prettyValue = obj.prettyValue.name;
                                        }
                                    }
                                    break;
                                case "locale":
                                    obj.label = "{{ __('ui::app.datagrid.locale') }}";
                                    if ('locales' in this.extraFilters) {
                                        obj.prettyValue = this.extraFilters['locales'].find(locale => locale.code === obj.val);

                                        if (obj.prettyValue !== undefined) {
                                            obj.prettyValue = obj.prettyValue.name;
                                        }
                                    }
                                    break;
                                case "customer_group":
                                    obj.label = "{{ __('ui::app.datagrid.customer-group') }}";
                                    if ('customer_groups' in this.extraFilters) {
                                        obj.prettyValue = this.extraFilters['customer_groups'].find(customer_group => customer_group.id === parseInt(obj.val, 10));

                                        if (obj.prettyValue !== undefined) {
                                            obj.prettyValue = obj.prettyValue.name;
                                        }
                                    }
                                    break;
                                case "sort":
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
                                                    obj.val = '{{ __('ui::app.datagrid.true') }}';
                                                } else {
                                                    obj.val = '{{ __('ui::app.datagrid.false') }}';
                                                }
                                            }
                                        }
                                    }
                                    break;
                            }

                            if (obj.column !== undefined && obj.column !== 'admin_locale' && obj.val !== undefined) {
                                this.filters.push(obj);
                            }

                            obj = {};
                        }
                    },

                    removeFilter: function (filter) {
                        for (let i in this.filters) {
                            if (this.filters[i].column === filter.column
                                && this.filters[i].cond === filter.cond
                                && this.filters[i].val === filter.val) {
                                this.filters.splice(i, 1);

                                this.makeURL();
                            }
                        }
                    },

                    paginate: function (e) {
                        for (let i = 0; i < this.filters.length; i++) {
                            if (this.filters[i].column == 'perPage') {
                                this.filters.splice(i, 1);
                            }
                        }

                        this.filters.push({"column": "perPage", "cond": "eq", "val": e.target.value});

                        this.makeURL();
                    },

                    /**
                     * Get current page ids.
                     */
                    getCurrentIds: function () {
                        let currentIds = [];

                        if (this.gridCurrentData.hasOwnProperty("data")) {
                            for (let currentData in this.gridCurrentData.data) {
                                currentIds.push(this.gridCurrentData.data[currentData][this.filterIndex]);
                            }
                        } else {
                            for (let currentData in this.gridCurrentData) {
                                let i = 0;
                                for (let currentId in this.gridCurrentData[currentData]) {
                                    if (i === 0) {
                                        currentIds.push(this.gridCurrentData[currentData][currentId]);
                                    }
                                    i++;
                                }
                            }
                        }

                        return currentIds;
                    },

                    /**
                     * Get page key.
                     */
                    getPageKey: function () {
                        let routeSegments = this.gridCurrentData.path.split('/');

                        return routeSegments[routeSegments.length - 1];
                    },

                    /**
                     * Set selected indexes.
                     */
                    setSelectedIndexes: function () {
                        let routeIndexObj = {};

                        routeIndexObj[this.getPageKey()] = this.dataIds;

                        localStorage.dataGridIndexes = JSON.stringify(routeIndexObj);
                    },

                    /**
                     * Get selected indexes.
                     */
                    getSelectedIndexes: function () {
                        let selectedIndexes = localStorage.getItem("dataGridIndexes");

                        if (selectedIndexes !== null && selectedIndexes !== '') {
                            return selectedIndexes;
                        }

                        return JSON.stringify({});
                    },

                    /**
                     * Triggered when any select box is clicked in the datagrid.
                     */
                    select: function (event) {
                        let checkboxElement = event.target;

                        if (checkboxElement.checked) {
                            if (! this.dataIds.includes(checkboxElement.value)) {
                                this.dataIds.push(checkboxElement.value);
                            }
                        } else {
                            if (this.dataIds.includes(checkboxElement.value)) {
                                this.dataIds.pop(checkboxElement.value);
                            }
                        }

                        this.allSelected = false;

                        if (this.dataIds.length === 0) {
                            this.massActionsToggle = false;
                            this.massActionType = this.getDefaultMassActionType();
                        } else {
                            this.massActionsToggle = true;
                        }

                        this.setSelectedIndexes();
                    },

                    /**
                     * Triggered when master checkbox is clicked.
                     */
                    selectAll: function () {
                        this.massActionsToggle = true;

                        if (this.allSelected) {
                            if (this.gridCurrentData.hasOwnProperty("data")) {
                                for (let currentData in this.gridCurrentData.data) {
                                    this.dataIds.push(this.gridCurrentData.data[currentData][this.filterIndex]);
                                }
                            } else {
                                for (let currentData in this.gridCurrentData) {
                                    let i = 0;
                                    for (let currentId in this.gridCurrentData[currentData]) {
                                        if (i === 0) {
                                            this.dataIds.push(this.gridCurrentData[currentData][currentId]);
                                        }
                                        i++;
                                    }
                                }
                            }

                            this.setSelectedIndexes();
                        }
                    },

                    /**
                     * Triggered when master checkbox is unchecked.
                     */
                    removeMassActions: function () {
                        let currentIds = this.getCurrentIds();

                        this.dataIds = this.dataIds.filter(id => currentIds.indexOf(parseInt(id)) == -1);

                        this.massActionsToggle = false;

                        this.allSelected = false;

                        this.massActionType = this.getDefaultMassActionType();

                        this.setSelectedIndexes();
                    },

                    /**
                     * Triggered when page load for checking the selected ids.
                     */
                    checkedSelectedCheckbox: function () {
                        let pageKey = this.getPageKey();

                        let selectedIndexes = JSON.parse(this.getSelectedIndexes());

                        if (Object.keys(selectedIndexes).length) {
                            for (key in selectedIndexes) {
                                if (key === pageKey) {
                                    this.dataIds = selectedIndexes[key];
                                    this.massActionsToggle = true;
                                    this.allSelected = false;

                                    if (this.dataIds.length === 0) {
                                        this.massActionsToggle = false;
                                        this.massActionType = this.getDefaultMassActionType();
                                    }
                                } else {
                                    delete selectedIndexes[key];
                                }
                            }
                        }

                        this.setSelectedIndexes();
                    },

                    /**
                     * Do actions.
                     */
                    doAction: function (e, message, type) {
                        let element = e.currentTarget;

                        if (message) {
                            element = e.target.parentElement;
                        }

                        message = message || '{{__('ui::app.datagrid.massaction.delete') }}';

                        if (confirm(message)) {
                            axios.post(element.getAttribute('data-action'), {
                                _token: element.getAttribute('data-token'),
                                _method: element.getAttribute('data-method')
                            }).then(function (response) {
                                this.result = response;

                                if (response.data.redirect) {
                                    window.location.href = response.data.redirect;
                                } else {
                                    location.reload();
                                }
                            }).catch(function (error) {
                                location.reload();
                            });

                            e.preventDefault();
                        } else {
                            e.preventDefault();
                        }
                    }
                },
            });
        </script>
    @endpush
</div>

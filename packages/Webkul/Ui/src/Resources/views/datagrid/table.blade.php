<div class="table">
    <datagrid-filters></datagrid-filters>

    @if (config('datagrid.paginate'))
        @include('ui::datagrid.pagination', ['results' => $results['records']])
    @endif

    @push('scripts')
        <script type="text/x-template" id="datagrid-filters">
            {{-- start filter here --}}
            <div class="grid-container">
                <div class="filter-row-one" id="datagrid-filters">
                    <div class="search-filter">
                        <input type="search" id="search-field" class="control" placeholder="Search Here..." v-model="searchValue" />

                        <div class="icon-wrapper">
                            <span class="icon search-icon search-btn" v-on:click="searchCollection(searchValue)"></span>
                        </div>
                    </div>

                    <div class="dropdown-filters">
                        <div class="more-filters">
                            <div class="dropdown-toggle">
                                <div class="dropdown-header">
                                    <span class="name">Filter</span>
                                    <i class="icon arrow-down-icon active"></i>
                                </div>
                            </div>

                            <div class="dropdown-list bottom-right" style="display: none;">
                                <div class="dropdown-container">
                                    <ul>
                                        <li class="filter-column-dropdown">
                                            <div class="control-group">
                                                <select class="filter-column-select control" v-model="filterColumn" v-on:click="getColumnOrAlias(filterColumn)">
                                                    <option selected disabled>Select Column</option>
                                                    @foreach($results['columns'] as $column)
                                                        <option value="{{ $column['index'] }}">
                                                            {{ $column['label'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </li>

                                        {{-- suitable for string columns --}}
                                        <li class="filter-condition-dropdown-string" v-if='stringConditionSelect'>
                                            <div class="control-group">
                                                <select class="control" v-model="stringCondition">
                                                    <option selected disabled>Select Condition</option>
                                                    <option value="like">Contains</option>
                                                    <option value="nlike">Does not contains</option>
                                                    <option value="eq">Is equal to</option>
                                                    <option value="neqs">Is not equal to</option>
                                                </select>
                                            </div>
                                        </li>

                                        {{-- Response fields based on the type of columns to be filtered --}}
                                        <li class="filter-condition-dropdown-string" v-if='stringCondition != null'>
                                            <div class="control-group">
                                                <input type="text" class="control response-string" placeholder="String Value here" v-model="stringValue" />
                                            </div>
                                        </li>

                                        {{-- suitable for numeric columns --}}
                                        <li class="filter-condition-dropdown-number" v-if='numberConditionSelect'>
                                            <div class="control-group">
                                                <select class="control" v-model="numberCondition">
                                                    <option selected disabled>Select Condition</option>
                                                    <option value="eq">Is equal to</option>
                                                    <option value="neqs">Is not equal to</option>
                                                    <option value="gt">Greater than</option>
                                                    <option value="lt">Less than</option>
                                                    <option value="gte">Greater than equals to</option>
                                                    <option value="lte">Less than equals to</option>
                                                </select>
                                            </div>
                                        </li>

                                        <li class="filter-response-number" v-if='numberCondition != null'>
                                            <div class="control-group">
                                                <input type="number" class="control response-number" placeholder="Numeric Value here"  v-model="numberValue"/>
                                            </div>
                                        </li>

                                        {{-- suitable for boolean columns --}}
                                        <li class="filter-condition-dropdown-boolean" v-if='booleanConditionSelect'>
                                            <div class="control-group">
                                                <select class="control" v-model="booleanCondition">
                                                    <option selected disabled>Select Condition</option>
                                                    <option value="eq">Is equal to</option>
                                                    <option value="neqs">Is no equal to</option>
                                                </select>
                                            </div>
                                        </li>

                                        <li class="filter-condition-dropdown-boolean" v-if='booleanCondition != null'>
                                            <div class="control-group">
                                                <select class="control" v-model="booleanValue">
                                                    <option selected disabled>Select Value</option>
                                                    <option value="1">True / Active</option>
                                                    <option value="0">False / Inactive</option>
                                                </select>
                                            </div>
                                        </li>

                                        {{-- suitable for date/time columns --}}
                                        <li class="filter-condition-dropdown-datetime" v-if='datetimeConditionSelect'>
                                            <div class="control-group">
                                                <select class="control" v-model="datetimeCondition">
                                                    <option selected disabled>Select Condition</option>
                                                    <option value="eq">Is equal to</option>
                                                    <option value="neqs">Is not equal to</option>
                                                    <option value="gt">Greater than</option>
                                                    <option value="lt">Less than</option>
                                                    <option value="gte">Greater than equals to</option>
                                                    <option value="lte">Less than equals to</option>
                                                    {{-- <option value="btw">Is Between</option> --}}
                                                </select>
                                            </div>
                                        </li>

                                        <li class="filter-condition-dropdown-boolean" v-if='datetimeCondition != null'>
                                            <div class="control-group">
                                                <input class="control" v-model="datetimeValue" type="date">
                                            </div>
                                        </li>

                                        <button class="btn btn-sm btn-primary apply-filter" v-on:click="getResponse">Apply</button>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="filter-row-two">
                    <span class="filter-tag" v-if="filters.length > 0" v-for="filter in filters" style="text-transform: capitalize;">
                        <span v-if="filter.column == 'sort'">@{{ filter.label }}</span>
                        <span v-else-if="filter.column == 'search'">Search</span>
                        <span v-else>@{{ filter.label }}</span>

                        <span class="wrapper">
                            @{{ filter.val }}
                            <span class="icon cross-icon" v-on:click="removeFilter(filter)"></span>
                        </span>
                    </span>
                </div>

                <table>
                    <thead v-if="massActionsToggle">
                        @if (isset($results['massactions']))
                            <tr class="mass-action" style="height: 63px;" v-if="massActionsToggle">
                                <th colspan="10" style="width: 100%;">
                                    <div class="mass-action-wrapper" style="display: flex; flex-direction: row; align-items: center; justify-content: flex-start;">

                                        <span class="massaction-remove" v-on:click="removeMassActions" style="margin-right: 10px;">
                                            <span class="icon checkbox-dash-icon"></span>
                                        </span>

                                        <form method="POST" id="mass-action-form" style="display: inline-flex;" action="">
                                            @csrf()

                                            <input type="hidden" id="indexes" name="indexes" v-model="dataIds">

                                            <div class="control-group">
                                                <select class="control" v-model="massActionType" @change="changeMassActionTarget" name="massaction-type" required>
                                                    <option v-for="(massAction, index) in massActions" :key="index" :value="massAction.type">@{{ massAction.label }}</option>
                                                </select>
                                            </div>

                                            <div class="control-group" style="margin-left: 10px;" v-if="massActionType == 'update'">
                                                <select class="control" v-model="massActionUpdateValue" name="update-options" required>
                                                    <option v-for="(massActionValue, id) in massActionValues" :value="massActionValue">@{{ id }}</option>
                                                </select>
                                            </div>

                                            <input type="submit" class="btn btn-sm btn-primary" style="margin-left: 10px;">
                                        </form>
                                    </div>
                                </th>
                            </tr>
                        @endif
                    </thead>

                    <thead v-if="massActionsToggle == false">
                        <tr>
                            @if (count($results['records']) && $results['enableMassActions'])
                                <th class="grid_head" id="mastercheckbox" style="width: 50px;">
                                    <span class="checkbox">
                                        <input type="checkbox" v-model="allSelected" v-on:change="selectAll">

                                        <label class="checkbox-view" for="checkbox"></label>
                                    </span>
                                </th>
                            @endif

                            @foreach($results['columns'] as $key => $column)
                                <th class="grid_head"
                                    @if(isset($column['width']))
                                        style="width: {{ $column['width'] }}"
                                    @endif

                                    @if(isset($column['sortable']) && $column['sortable'])
                                        v-on:click="sortCollection('{{ $column['index'] }}')"
                                    @endif
                                >
                                    {{ $column['label'] }}
                                </th>
                            @endforeach

                            @if ($results['enableActions'])
                                <th>
                                    {{ __('ui::app.datagrid.actions') }}
                                </th>
                            @endif
                        </tr>
                    </thead>

                    @include('ui::datagrid.body', ['records' => $results['records'], 'actions' => $results['actions'], 'index' => $results['index'], 'columns' => $results['columns'],'enableMassActions' => $results['enableMassActions'], 'enableActions' => $results['enableActions'], 'norecords' => $results['norecords']])
                </table>
            </div>
        </script>

        <script>
            Vue.component('datagrid-filters', {
                template: '#datagrid-filters',

                data: () => ({
                    gridCurrentData: @json($results['records']),
                    massActions: @json($results['massactions']),
                    massActionsToggle: false,
                    massActionTarget: null,
                    massActionType: null,
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
                    columns : @json($results['columns']),
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
                    datetimeConditionSelect: false
                }),

                mounted: function() {
                    this.setParamsAndUrl();
                },

                methods: {
                    getColumnOrAlias(columnOrAlias) {
                        this.columnOrAlias = columnOrAlias;

                        for(column in this.columns) {
                            if (this.columns[column].index == this.columnOrAlias) {
                                this.type = this.columns[column].type;

                                if (this.type == 'string') {
                                    this.stringConditionSelect = true;
                                    this.datetimeConditionSelect = false;
                                    this.booleanConditionSelect = false;
                                    this.numberConditionSelect = false;

                                    this.nullify();
                                } else if (this.type == 'datetime') {
                                    this.datetimeConditionSelect = true;
                                    this.stringConditionSelect = false;
                                    this.booleanConditionSelect = false;
                                    this.numberConditionSelect = false;

                                    this.nullify();
                                } else if (this.type == 'boolean') {
                                    this.booleanConditionSelect = true;
                                    this.datetimeConditionSelect = false;
                                    this.stringConditionSelect = false;
                                    this.numberConditionSelect = false;

                                    this.nullify();
                                } else if (this.type == 'number') {
                                    this.numberConditionSelect = true;
                                    this.booleanConditionSelect = false;
                                    this.datetimeConditionSelect = false;
                                    this.stringConditionSelect = false;

                                    this.nullify();
                                } else if (this.type == 'price') {
                                    this.numberConditionSelect = true;
                                    this.booleanConditionSelect = false;
                                    this.datetimeConditionSelect = false;
                                    this.stringConditionSelect = false;

                                    this.nullify();
                                }
                            }
                        }
                    },

                    nullify() {
                        this.stringCondition = null;
                        this.datetimeCondition = null;
                        this.booleanCondition = null;
                        this.numberCondition = null;
                    },

                    getResponse() {
                        label = '';

                        for(colIndex in this.columns) {
                            if(this.columns[colIndex].index == this.columnOrAlias) {
                                label = this.columns[colIndex].label;
                            }
                        }

                        if (this.type == 'string') {
                            this.formURL(this.columnOrAlias, this.stringCondition, this.stringValue, label)
                        } else if (this.type == 'number') {
                            this.formURL(this.columnOrAlias, this.numberCondition, this.numberValue, label);
                        } else if (this.type == 'boolean') {
                            this.formURL(this.columnOrAlias, this.booleanCondition, this.booleanValue, label);
                        } else if (this.type == 'datetime') {
                            this.formURL(this.columnOrAlias, this.datetimeCondition, this.datetimeValue, label);
                        } else if (this.type == 'price') {
                            this.formURL(this.columnOrAlias, this.numberCondition, this.numberValue, label);
                        }
                    },

                    sortCollection(alias) {
                        label = '';

                        for(colIndex in this.columns) {
                            if(this.columns[colIndex].index == alias) {
                                matched = 0;
                                label = this.columns[colIndex].label;
                            }
                        }

                        this.formURL("sort", alias, this.sortAsc, label);
                    },

                    searchCollection(searchValue) {
                        label = 'Search';

                        this.formURL("search", 'all', searchValue, label);
                    },

                    // function triggered to check whether the query exists or not and then call the make filters from the url
                    setParamsAndUrl() {
                        params = (new URL(window.location.href)).search;

                        if (params.slice(1, params.length).length > 0) {
                            this.arrayFromUrl();
                        }

                        for(id in this.massActions) {
                            targetObj = {
                                'type': this.massActions[id].type,
                                'action': this.massActions[id].action
                            };

                            this.massActionTargets.push(targetObj);

                            targetObj = {};

                            if (this.massActions[id].type == 'update') {
                                this.massActionValues = this.massActions[id].options;
                            }
                        }
                    },

                    findCurrentSort() {
                        for(i in this.filters) {
                            if (this.filters[i].column == 'sort') {
                                this.currentSort = this.filters[i].val;

                                // if (this.currentSort = 'asc') {
                                //     this.currentSortIcon = this.sortUpIcon;
                                // } else {
                                //     this.currentSortIcon = this.sortDownIcon;
                                // }
                            }
                        }
                    },

                    changeMassActionTarget() {
                        if (this.massActionType == 'delete') {
                            for(i in this.massActionTargets) {
                                if (this.massActionTargets[i].type == 'delete') {
                                    this.massActionTarget = this.massActionTargets[i].action;

                                    break;
                                }
                            }
                        }

                        if (this.massActionType == 'update') {
                            for(i in this.massActionTargets) {
                                if (this.massActionTargets[i].type == 'update') {
                                    this.massActionTarget = this.massActionTargets[i].action;

                                    break;
                                }
                            }
                        }

                        document.getElementById('mass-action-form').action = this.massActionTarget;
                    },

                    //make array of filters, sort and search
                    formURL(column, condition, response, label) {
                        var obj = {};

                        if (column == "" || condition == "" || response == "" || column == null || condition == null || response == null) {
                            alert('{{ __('ui::app.datagrid.filter-fields-missing') }}');

                            return false;
                        } else {
                            if (this.filters.length > 0) {
                                if (column != "sort" && column != "search") {
                                    filterRepeated = 0;

                                    for(j = 0; j < this.filters.length; j++) {
                                        if (this.filters[j].column == column) {
                                            if (this.filters[j].cond == condition && this.filters[j].val == response) {
                                                filterRepeated = 1;

                                                return false;
                                            } else if(this.filters[j].cond == condition && this.filters[j].val != response) {
                                                filterRepeated = 1;

                                                this.filters[j].val = response;

                                                this.makeURL();
                                            }
                                        }
                                    }

                                    if (filterRepeated == 0) {
                                        obj.column = column;
                                        obj.cond = condition;
                                        obj.val = response;
                                        obj.label = label;

                                        this.filters.push(obj);
                                        obj = {};

                                        this.makeURL();
                                    }
                                }

                                if (column == "sort") {
                                    sort_exists = 0;

                                    for (j = 0; j < this.filters.length; j++) {
                                        if (this.filters[j].column == "sort") {
                                            if (this.filters[j].column == column && this.filters[j].cond == condition) {
                                                this.findCurrentSort();

                                                if (this.currentSort == "asc") {
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

                                            sort_exists = 1;
                                        }
                                    }

                                    if (sort_exists == 0) {
                                        if (this.currentSort == null)
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

                                if (column == "search") {
                                    search_found = 0;

                                    for(j = 0; j < this.filters.length; j++) {
                                        if (this.filters[j].column == "search") {
                                            this.filters[j].column = column;
                                            this.filters[j].cond = condition;
                                            this.filters[j].val = response;
                                            this.filters[j].label = label;

                                            this.makeURL();
                                        }
                                    }

                                    for (j = 0;j < this.filters.length;j++) {
                                        if (this.filters[j].column == "search") {
                                            search_found = 1;
                                        }
                                    }

                                    if (search_found == 0) {
                                        obj.column = column;
                                        obj.cond = condition;
                                        obj.val = response;
                                        obj.label = label;

                                        this.filters.push(obj);

                                        obj = {};

                                        this.makeURL();
                                    }
                                }
                            } else {
                                obj.column = column;
                                obj.cond = condition;
                                obj.val = response;
                                obj.label = label;

                                this.filters.push(obj);

                                obj = {};

                                this.makeURL();
                            }
                        }
                    },

                    // make the url from the array and redirect
                    makeURL() {
                        newParams = '';

                        for(i = 0; i < this.filters.length; i++) {
                            if (i == 0) {
                                newParams = '?' + this.filters[i].column + '[' + this.filters[i].cond + ']' + '=' + this.filters[i].val;
                            } else {
                                newParams = newParams + '&' + this.filters[i].column + '[' + this.filters[i].cond + ']' + '=' + this.filters[i].val;
                            }
                        }

                        var uri = window.location.href.toString();

                        var clean_uri = uri.substring(0, uri.indexOf("?")).trim();

                        window.location.href = clean_uri + newParams;
                    },

                    //make the filter array from url after being redirected
                    arrayFromUrl() {
                        var obj = {};
                        processedUrl = this.url.search.slice(1, this.url.length);
                        splitted = [];
                        moreSplitted = [];

                        splitted = processedUrl.split('&');

                        for(i = 0; i < splitted.length; i++) {
                            moreSplitted.push(splitted[i].split('='));
                        }

                        for(i = 0; i < moreSplitted.length; i++) {
                            col = moreSplitted[i][0].replace(']','').split('[')[0];
                            cond = moreSplitted[i][0].replace(']','').split('[')[1]
                            val = moreSplitted[i][1];

                            label = 'cannotfindthislabel';

                            // for(colIndex in this.columns) {
                            //     if (this.columns[colIndex].alias == this.columnOrAlias) {
                            //         label = this.columns[colIndex].label;
                            //     }
                            // }

                            obj.column = col;
                            obj.cond = cond;
                            obj.val = val;

                            if(col == "sort") {
                                // console.log('sort', obj.cond);
                                label = '';

                                for(colIndex in this.columns) {
                                    if(this.columns[colIndex].index == obj.cond) {

                                        obj.label = this.columns[colIndex].label;
                                    }
                                }
                            } else if (col == "search") {
                                obj.label = 'Search';
                            } else {
                                obj.label = '';

                                for(colIndex in this.columns) {
                                    if(this.columns[colIndex].index == obj.column) {
                                        obj.label = this.columns[colIndex].label;
                                    }
                                }
                            }

                            if (col != undefined && cond != undefined && val != undefined)
                                this.filters.push(obj);

                            obj = {};
                        }
                    },

                    removeFilter(filter) {
                        for(i in this.filters) {
                            if (this.filters[i].col == filter.col && this.filters[i].cond == filter.cond && this.filters[i].val == filter.val) {
                                this.filters.splice(i, 1);

                                this.makeURL();
                            }
                        }
                    },

                    //triggered when any select box is clicked in the datagrid
                    select() {
                        this.allSelected = false;

                        if(this.dataIds.length == 0)
                            this.massActionsToggle = false;
                        else
                            this.massActionsToggle = true;
                    },

                    //triggered when master checkbox is clicked
                    selectAll() {
                        this.dataIds = [];

                        this.massActionsToggle = true;

                        if (this.allSelected) {
                            if (this.gridCurrentData.hasOwnProperty("data")) {
                                for (currentData in this.gridCurrentData.data) {

                                    i = 0;
                                    for(currentId in this.gridCurrentData.data[currentData]) {
                                        if (i==0)
                                            this.dataIds.push(this.gridCurrentData.data[currentData][currentId]);

                                        i++;
                                    }
                                }
                            } else {
                                for (currentData in this.gridCurrentData) {

                                    i = 0;
                                    for(currentId in this.gridCurrentData[currentData]) {
                                        if (i==0)
                                            this.dataIds.push(this.gridCurrentData[currentData][currentId]);

                                        i++;
                                    }
                                }
                            }
                        }
                    },

                    removeMassActions() {
                        this.dataIds = [];

                        this.massActionsToggle = false;

                        this.allSelected = false;
                    }
                }
            });
        </script>
    @endpush
</div>
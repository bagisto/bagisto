<div class="table">
    <table>
        <testgrid-filters></testgrid-filters>

        @include('ui::testgrid.body', ['records' => $results['records']])
    </table>

    @push('scripts')
        <script type="text/x-template" id="testgrid-filters">
            <div id="testgrid-filters" class="filter-row-one" style="width: 100%; display: flex; flex-direction: row; align-items:center; justify-content: space-between;">
                <div class="search-filter" style="display: inline-flex; align-items: center;">
                    <input type="search" id="search-field" class="control" placeholder="Search Here..." v-model="searchValue" />
                    <div class="ic-wrapper">
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
                                        <select class="filter-column-select" v-model="filterColumn" v-on:click="getColumnOrAlias(filterColumn)">
                                            <option selected disabled>Select Column</option>
                                            @foreach($results['columns'] as $column)
                                                <option value="{{ $column['alias'] }}">
                                                    {{ $column['label'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </li>

                                    {{-- suitable for string columns --}}
                                    <li class="filter-condition-dropdown-string" v-if='stringConditionSelect'>
                                        <select class="control" v-model="stringCondition">
                                            <option selected disabled>Select Condition</option>
                                            <option value="like">Contains</option>
                                            <option value="nlike">Does not contains</option>
                                            <option value="eq">Is equal to</option>
                                            <option value="neqs">Is not equal to</option>
                                        </select>
                                    </li>

                                    {{-- Response fields based on the type of columns to be filtered --}}
                                    <li class="filter-condition-dropdown-string" v-if='stringCondition != null'>
                                        <input type="text" class="control response-string" placeholder="String Value here" v-model="stringValue" />
                                    </li>

                                    {{-- suitable for numeric columns --}}
                                    <li class="filter-condition-dropdown-number" v-if='numberConditionSelect'>
                                        <select class="control" v-model="numberCondition">
                                            <option selected disabled>Select Condition</option>
                                            <option value="eq">Is equal to</option>
                                            <option value="neqs">Is not equal to</option>
                                            <option value="gt">Greater than</option>
                                            <option value="lt">Less than</option>
                                            <option value="gte">Greater than equals to</option>
                                            <option value="lte">Less than equals to</option>
                                        </select>
                                    </li>

                                    <li class="filter-response-number" v-if='numberCondition != null'>
                                        <input type="number" class="control response-number" placeholder="Numeric Value here"  v-model="numberValue"/>
                                    </li>

                                    {{-- suitable for boolean columns --}}
                                    <li class="filter-condition-dropdown-boolean" v-if='booleanConditionSelect'>
                                        <select class="control" v-model="booleanCondition">
                                            <option selected disabled>Select Condition</option>
                                            <option value="eq">Is equal to</option>
                                            <option value="neqs">Is no equal to</option>
                                        </select>
                                    </li>

                                    <li class="filter-condition-dropdown-boolean" v-if='booleanCondition != null'>
                                        <select class="control" v-model="booleanValue">
                                            <option selected disabled>Select Value</option>
                                            <option value="1">True / Active</option>
                                            <option value="0">False / Inactive</option>
                                        </select>
                                    </li>

                                    {{-- suitable for date/time columns --}}
                                    <li class="filter-condition-dropdown-datetime" v-if='datetimeConditionSelect'>
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
                                    </li>

                                    <li class="filter-condition-dropdown-boolean" v-if='datetimeCondition != null'>
                                        <input class="control" v-model="datetimeValue" type="date">
                                    </li>

                                    <button class="btn btn-sm btn-primary apply-filter" v-on:click="getResponse">Apply</button>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="filter-row-two" style="height: 100px; width: 100%">
                    {{-- <span class="filter-one">
                        <span class="filter-name">
                            Stock
                        </span>
                        <span class="filter-value">
                            Available
                            <span class="icon cross-icon"></span>
                        </span>
                    </span> --}}
                    <span class="filter-tag" v-if="filters.length > 0" v-for="filter in filters" style="height: 60px; width: 60px; background-color: red;">
                        <span class="col" v-if="filter.column == 'sort'">@{{ filter.cond }}</span>
                        <span class="col" v-else-if="filter.column == 'search'">Search</span>
                        <span class="col" v-else>@{{ filter.colum }}</span>
                        <span class="val">@{{ filter.val }}</span>
                        <span class="icon cross-icon"></span>
                    </span>
                </div>
                <thead>
                    <tr>
                        <th class="grid_head" id="mastercheckbox" style="width: 50px;">
                            <span class="checkbox">
                                <input type="checkbox" id="mastercheckbox">
                                <label class="checkbox-view" for="checkbox"></label>
                            </span>
                        </th>

                        @foreach($results['columns'] as $key => $column)
                            <th class="grid_head" data-column-alias="{{ $column['alias'] }}" data-column-name="{{ $column['column'] }}" data-column-sortable="{{ $column['sortable'] }}" data-column-type="{{ $column['type'] }}" style="width: {{ $column['width'] }}" v-on:click="sortCollection('{{ $column['alias'] }}')">{{ $column['label'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <slot></slot>
            </div>
        </script>

        <script>
            Vue.component('testgrid-filters', {

                template: '#testgrid-filters',

                data: () => ({
                    url: new URL(window.location.href),
                    currentSort: null,
                    sortDesc: 'desc',
                    sortAsc: 'asc',
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
                    datetimeConditionSelect: false,
                }),

                mounted: function() {
                    this.setParamsAndUrl();
                },

                methods: {
                    getColumnOrAlias(columnOrAlias) {
                        this.columnOrAlias = columnOrAlias;

                        for(column in this.columns) {
                            if (this.columns[column].alias == this.columnOrAlias) {
                                this.type = this.columns[column].type;

                                if(this.type == 'string') {
                                    this.stringConditionSelect = true;
                                    this.datetimeConditionSelect = false;
                                    this.booleanConditionSelect = false;
                                    this.numberConditionSelect = false;

                                    this.nullify();
                                } else if(this.type == 'datetime') {
                                    this.datetimeConditionSelect = true;
                                    this.stringConditionSelect = false;
                                    this.booleanConditionSelect = false;
                                    this.numberConditionSelect = false;

                                    this.nullify();
                                } else if(this.type == 'boolean') {
                                    this.booleanConditionSelect = true;
                                    this.datetimeConditionSelect = false;
                                    this.stringConditionSelect = false;
                                    this.numberConditionSelect = false;

                                    this.nullify();
                                } else if(this.type == 'number') {
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
                        if(this.type == 'string') {
                            console.log(this.columnOrAlias, this.stringCondition, this.stringValue);
                            this.formURL(this.columnOrAlias, this.stringCondition, this.stringValue)
                        } else if(this.type == 'number') {
                            console.log(this.columnOrAlias, this.numberCondition, this.numberValue);
                            this.formURL(this.columnOrAlias, this.numberCondition, this.numberValue);
                        } else if(this.type == 'boolean') {
                            console.log(this.columnOrAlias, this.booleanCondition, this.booleanValue);
                            this.formURL(this.columnOrAlias, this.booleanCondition, this.booleanValue);
                        } else if(this.type == 'datetime') {
                            console.log(this.columnOrAlias, this.datetimeCondition, this.datetimeValue);
                            this.formURL(this.columnOrAlias, this.datetimeCondition, this.datetimeValue);
                        }
                    },

                    sortCollection(alias) {
                        this.formURL("sort", alias, this.sortAsc);
                    },

                    searchCollection(searchValue) {
                        this.formURL("search", 'all', searchValue);
                    },

                    //function triggered to check whether the query exists or not and then call the make filters from the url
                    setParamsAndUrl() {
                        params = (new URL(window.location.href)).search;

                        if(params.slice(1, params.length).length > 0) {
                            this.arrayFromUrl();
                        }

                    },

                    findCurrentSort() {
                        for(i in this.filters) {
                            if(this.filters[i].column == 'sort') {
                                this.currentSort = this.filters[i].val;
                            }
                        }
                    },

                    //make array of filters, sort and search
                    formURL(column, condition, response) {
                        var obj = {};

                        if(column == "" || condition == "" || response == "" || column == null || condition == null || response == null) {
                            alert('{{ __('ui::app.datagrid.filter-fields-missing') }}');

                            return false;
                        } else {
                            if(this.filters.length > 0) {
                                if(column != "sort" && column != "search") {
                                    filter_repeated = 0;

                                    for(j = 0; j < this.filters.length; j++) {
                                        if(this.filters[j].column == column) {
                                            if(this.filters[j].cond == condition && this.filters[j].val == response) {
                                                return false;
                                            }

                                            filter_repeated = 1;

                                            this.filters[j].cond = condition;
                                            this.filters[j].val = response;

                                            this.makeURL();
                                        }
                                    }

                                    if(filter_repeated == 0) {
                                        obj.column = column;
                                        obj.cond = condition;
                                        obj.val = response;


                                        this.filters.push(obj);
                                        obj = {};

                                        this.makeURL();
                                    }
                                }

                                if(column == "sort") {
                                    sort_exists = 0;

                                    for(j = 0; j < this.filters.length; j++) {
                                        if(this.filters[j].column == "sort") {
                                            if(this.filters[j].column == column && this.filters[j].cond == condition) {
                                                this.findCurrentSort();

                                                if(this.currentSort == "asc") {
                                                    this.filters[j].column = column;
                                                    this.filters[j].cond = condition;
                                                    this.filters[j].val = this.sortDesc;

                                                    this.makeURL();
                                                } else {
                                                    this.filters[j].column = column;
                                                    this.filters[j].cond = condition;
                                                    this.filters[j].val = this.sortAsc;

                                                    console.log(this.filters[j].val, 2);

                                                    this.makeURL();
                                                }
                                            } else {
                                                this.filters[j].column = column;
                                                this.filters[j].cond = condition;
                                                this.filters[j].val = response;

                                                this.makeURL();
                                            }

                                            sort_exists = 1;
                                        }
                                    }

                                    if(sort_exists == 0) {
                                        if(this.currentSort == null)
                                            this.currentSort = this.sortAsc;

                                        obj.column = column;
                                        obj.cond = condition;
                                        obj.val = this.currentSort;

                                        this.filters.push(obj);

                                        obj = {};

                                        this.makeURL();
                                    }
                                }

                                if(column == "search") {
                                    search_found = 0;

                                    for(j = 0;j < this.filters.length;j++) {
                                        if(this.filters[j].column == "search") {
                                            this.filters[j].column = column;
                                            this.filters[j].cond = condition;
                                            this.filters[j].val = response;

                                            this.makeURL();
                                        }
                                    }

                                    for(j = 0;j < this.filters.length;j++) {
                                        if(this.filters[j].column == "search") {
                                            search_found = 1;
                                        }
                                    }

                                    if(search_found == 0) {
                                        obj.column = column;
                                        obj.cond = condition;
                                        obj.val = response;

                                        this.filters.push(obj);

                                        obj = {};

                                        this.makeURL();
                                    }
                                }
                            } else {
                                obj.column = column;
                                obj.cond = condition;
                                obj.val = response;

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
                            if(i == 0) {
                                newParams = '?' + this.filters[i].column + '[' + this.filters[i].cond + ']' + '=' + this.filters[i].val;
                            } else {
                                newParams = newParams + '&' + this.filters[i].column + '[' + this.filters[i].cond + ']' + '=' + this.filters[i].val;
                            }
                        }

                        var uri = window.location.href.toString();

                        var clean_uri = uri.substring(0, uri.indexOf("?")).trim();

                        window.location.href = clean_uri+newParams;
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

                            obj.column = col;
                            obj.cond = cond;
                            obj.val = val;

                            if(col != undefined && cond != undefined && val != undefined)
                                this.filters.push(obj);

                            obj = {};
                        }

                        console.log(this.filters);
                    }

                    // //Use the label to prevent the display of column name on the body
                    // function makeTags() {
                    //     var filterRepeat = 0;

                    //     if(this.filters.length > 0)
                    //     for(var i = 0;i<this.filters.length;i++) {

                    //         if(this.filters[i].column == "sort") {
                    //             col_label_tag = $('li[data-name="'+this.filters[i].cond+'"]').text();

                    //             var filter_card = '<span class="filter-one" id="'+ i +'"><span class="filter-name">'+ col_label_tag +'</span><span class="filter-value"><span class="f-value">'+ this.filters[i].val +'</span><span class="icon cross-icon remove-filter"></span></span></span>';

                    //             sorted_col = this.filters[i].cond;

                    //             var apply_on_column = $('th[data-column-name="'+sorted_col+'"]').children('.icon');

                    //             if(this.filters[i].val == "asc") {
                    //                 apply_on_column.addClass('sort-down-icon');
                    //             } else {
                    //                 apply_on_column.addClass('sort-up-icon');
                    //             }

                    //             $('.filter-row-two').append(filter_card);

                    //         } else if(this.filters[i].column == "search") {
                    //             col_label_tag = "Search";

                    //             var filter_card = '<span class="filter-one" id="'+ i +'"><span class="filter-name">'+ col_label_tag +'</span><span class="filter-value"><span class="f-value">'+ this.filters[i].val +'</span><span class="icon cross-icon remove-filter"></span></span></span>';

                    //             $('.filter-row-two').append(filter_card);

                    //         } else {
                    //             col_label_tag = $('li[data-name="'+this.filters[i].column+'"]').text().trim();

                    //             var filter_card = '<span class="filter-one" id="'+ i +'"><span class="filter-name">'+ col_label_tag +'</span><span class="filter-value"><span class="f-value">'+ this.filters[i].val +'</span><span class="icon cross-icon remove-filter"></span></span></span>';

                    //             $('.filter-row-two').append(filter_card);
                    //         }
                    //     }
                    // }
                }
            });
        </script>
    @endpush
</div>
<div class="{{ $css->filter }}filter-wrapper">
    {{-- for loading the filters from another file --}}
    <div class="filter-row-one">
        <div class="search-filter" style="display: inline-flex; align-items: center;">
            <input type="search" class="control search-field" placeholder="Search Users" value="" />
            <div class="ic-wrapper">
                <span class="icon search-icon search-btn"></span>
            </div>
        </div>
        <div class="dropdown-filters">
            <div class="column-filter">
                <div class="dropdown-list bottom-right" style="display: none;">
                    <div class="dropdown-container">
                        <ul>
                            @foreach($columns as $column)
                            <li data-name="{{ $column->name }}">
                                {{ $column->label }}
                                <span class="checkbox"><input type="checkbox" id="{{ $column->id }}" name="checkbox1[]"> <label for="checkbox1" class="checkbox-view"></label></span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="more-filters">
                <div class="dropdown-toggle">
                    <div class="dropdown-header">
                        <span class="name">Filter</span> {{-- <span class="role">Filter</span> --}}
                        <i class="icon arrow-down-icon active"></i>
                    </div>
                </div>
                <div class="dropdown-list bottom-right" style="display: none;">
                    <div class="dropdown-container">
                        <ul>
                            <li class="filter-column-dropdown">
                                <select class="filter-column-select">
                                    <option selected disabled>Select Column</option>
                                    @foreach($filterable as $fcol)
                                    <option value="{{ $fcol['column'] }}" data-type="{{ $fcol['type'] }}" data-label="{{ $fcol['label'] }}">{{ $fcol['label'] }}</option>
                                    @endforeach
                                </select>
                            </li>
                            {{-- suitable for string columns --}}
                            <li class="filter-condition-dropdown-string">
                                <select class="control filter-condition-select-string">
                                    <option selected disabled>Select Condition</option>
                                    <option value="like">Contains</option>
                                    <option value="nlike">Does not contains</option>
                                    <option value="eq">Is equal to</option>
                                    <option value="neqs">Is not equal to</option>
                                </select>
                            </li>
                            {{-- suitable for numeric columns --}}
                            <li class="filter-condition-dropdown-number">
                                <select class="control filter-condition-select-number">
                                    <option selected disabled>Select Condition</option>
                                    <option value="eq">Is equal to</option>
                                    <option value="neqs">Is not equal to</option>
                                    <option value="gt">Greater than</option>
                                    <option value="lt">Less than</option>
                                    <option value="gte">Greater than equals to</option>
                                    <option value="lte">Less than equals to</option>
                                </select>
                            </li>
                            {{-- suitable for date/time columns --}}
                            <li class="filter-condition-dropdown-datetime">
                                <select class="control filter-condition-select-datetime">
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
                            {{-- Response fields based on the type of columns to be filtered --}}
                            <li class="filter-response-string">
                                <input type="text" class="control response-string" placeholder="Value here" />
                            </li>
                            <li class="filter-response-boolean">
                                <select class="control select-boolean">
                                    <option value="true">Is True</option>
                                    <option value="false">Is False</option>
                                </select>
                            </li>
                            <li class="filter-response-datetime">
                                <input type="datetime-local" class="control response-datetime" placeholder="Value here" />
                            </li>
                            <li class="filter-response-number">
                                <input type="text" class="control response-number" placeholder="Value here" />
                            </li>
                            <li>
                                <button class="btn btn-sm btn-primary apply-filter">Apply</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="filter-row-two">
        {{-- <span class="filter-one">
                        <span class="filter-name">
                            Stock
                        </span>
        <span class="filter-value">
                            Available
                            <span class="icon cross-icon"></span>
        </span>
        </span> --}}
    </div>
</div>


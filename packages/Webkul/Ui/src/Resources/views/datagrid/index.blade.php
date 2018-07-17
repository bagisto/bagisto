<div class="grid-container{{-- $css->datagrid --}}">
    <div class="{{ $css->filter }}filter-wrapper">
        <div class="filter-row-one">
            <div class="search-filter" style="display: inline-flex; align-items: center;">
                <input type="search" class="control filter-field" placeholder="Search Users" value=""/>
                <div class="ic-wrapper">
                    <span class="icon search-icon filter-btn"></span>
                </div>

            </div>
            <div class="dropdown-filters">
                <div class="column-filter">
                    {{-- <div class="dropdown-toggle">
                        <div style="display: inline-block; vertical-align: middle;">
                            <span class="name">Columns</span>
                        </div>
                        <i class="icon arrow-down-icon active"></i>
                    </div> --}}
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
                        <div style="display: inline-block; vertical-align: middle;">
                            <span class="name">Filter</span>
                            {{-- <span class="role">Filter</span> --}}
                        </div>
                        <i class="icon arrow-down-icon active"></i>
                    </div>
                    <div class="dropdown-list bottom-right" style="display: none;">
                        <div class="dropdown-container">
                            <ul>
                                <li class="filter-column-dropdown">
                                    <select class="control filter-column-select">
                                        <option selected disabled>Select Column</option>
                                        @foreach($filterable as $fcol)
                                        <option value="{{ $fcol['column'] }}" data-type="{{ $fcol['type'] }}">{{ $fcol['label'] }}</option>
                                        @endforeach
                                    </select>
                                </li>
                                {{-- suitable for string columns --}}
                                <li class="filter-condition-dropdown-string">
                                    <select class="control filter-condition-select-string">
                                        <option selected disabled>Select Condition</option>
                                        <option value="like">Contains</option>
                                        <option value="not like">Does not contains</option>
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
                                        <option value="btw">Is Between</option>
                                    </select>
                                </li>
                                {{-- Response fields based on the type of columns to be filtered --}}
                                <li class="filter-response-string">
                                    <input type="text" class="control conditional-response" placeholder="Value here" />
                                </li>
                                <li class="filter-response-boolean">
                                    <select class="control select-boolean">
                                        <option value="true">Is True</option>
                                        <option value="false">Is False</option>
                                    </select>
                                </li>
                                <li class="filter-response-datetime">
                                    <input type="datetime-local" class="control conditional-response" placeholder="Value here" />
                                </li>
                                <li class="filter-response-number">
                                    <input type="text" class="control conditional-response" placeholder="Value here" />
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="filter-row-two">
            {{-- {{ $columns }}<br/>
            {{ json_encode($operators) }} --}}
            <span class="filter-one">
                <span class="filter-name">
                    Stock
                </span>
                <span class="filter-value">
                    Available
                    <span class="icon cross-icon"></span>
                </span>

            </span>
            <span class="filter-one">
                <span class="filter-name">
                    Stock
                </span>
                <span class="filter-value">
                    Available
                    <span class="icon cross-icon"></span>
                </span>
            </span>
            <span class="filter-one">
                <span class="filter-name">
                    Stock
                </span>
                <span class="filter-value">
                    Available
                    <span class="icon cross-icon"></span>
                </span>
            </span>
        </div>
    </div>
    <div class="table">
        <table class="{{ $css->table }}">
            <thead class="{{-- $css->thead --}}">
                <tr>
                    <th class="{{-- $css->thead_td --}}">Mass Action</th>
                    @foreach ($columns as $column)
                    <th class="$css->thead_td grid_head">{!! $column->sorting() !!}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="{{ $css->tbody }}">

                @foreach ($results as $result)
                <tr>
                    <td class="{{-- $css->tbody_td --}}">
                        <span class="checkbox">
                            <input type="checkbox" id="{{ $result->id }}" name="checkbox[]">
                            <label class="checkbox-view" for="checkbox1"></label>
                        </span>
                    </td>

                    @foreach ($columns as $column)
                    <td class="{{-- $css->tbody_td --}}">{!! $column->render($result) !!}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
        {{-- @include('ui::partials.pagination') --}}
    </div>
    @section('javascript')
        <script type="text/javascript">
        var filter_value;
        var filter_column;
        var filter_condition;
        var count_filters = 0;
        var url;
        var flag = 0;
        var array_start = '{';
        var array_end = '}';
        var typeValue;
        $(document).ready(function(){
            console.log('ready');
            $('.filter-btn').click(function(){
                filter_value = $(".filter-field").val();
            });

            $('select.filter-column-select').change(function() {
                typeValue = $('select.filter-column-select').find(':selected').data('type');
                console.log(typeValue);
                if(typeValue == 'string'){
                    //default behaviour for strings
                    $('.filter-condition-dropdown-number').css('display','none');
                    $('.filter-condition-dropdown-datetime').css('display','none');
                    $('.filter-response-number').css('display','none');
                    $('.filter-response-datetime').css('display','none');
                    $('.filter-response-boolean').css('display','none');

                    $('.filter-condition-dropdown-string').css('display','inherit');
                    $('.filter-response-string').css('display','inherit');
                }
                else if(typeValue == 'boolean'){
                    //make another list component

                    //hide unwanted
                    $('.filter-response-string').css('display','none');
                    $('.filter-response-number').css('display','none');
                    $('.filter-response-datetime').css('display','none');
                    $('.filter-condition-dropdown').css('display','none');
                    //show what is wanted
                    $('.filter-response-boolean').css('display','inherit');
                }
                else if(typeValue == 'datetime'){
                    //make another list component

                    //hide unwanted
                    $('.filter-response-string').css('display','none');
                    $('.filter-response-number').css('display','none');
                    $('.filter-response-boolean').css('display','none');

                    //show what is wanted
                    $('.filter-response-datetime').css('display','inherit');
                    $('.filter-condition-dropdown').css('display','inherit');
                }
                else if(typeValue == 'number'){
                    //make another list component

                    //hide unwanted
                    $('.filter-response-string').css('display','none');
                    $('.filter-response-datetime').css('display','none');
                    $('.filter-response-boolean').css('display','none');

                    //show what is wanted
                    $('.filter-response-number').css('display','inherit');
                    $('.filter-condition-dropdown').css('display','inherit');
                }
            });
            // $('.filter-column-select').click(function(){
            //     var column = $('.filter-column-select').val();
            //     console.log(column);
            // });


        });

        function formURL(a,b,c){
            //form the array object here and convert it to string and pass it in url

            if(count_filters == 0){
                filt = array_start+count_filters+'=>'+array_start+filter_column+'=>'+array_start+filter_condition+'=>'+filter_value+array_end+array_end+array_end;
            }
            console.log(filt);
            console.log(JSON.parse(filt))
            count_filters++;

        }
        </script>
    @endsection
</div>

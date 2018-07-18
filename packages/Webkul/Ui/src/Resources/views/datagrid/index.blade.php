<div class="grid-container{{-- $css->datagrid --}}">
    <div class="{{ $css->filter }}filter-wrapper">
        <div class="filter-row-one">
            <div class="search-filter" style="display: inline-flex; align-items: center;">
                <input type="search" class="control search-field" placeholder="Search Users" value=""/>
                <div class="ic-wrapper">
                    <span class="icon search-icon search-btn"></span>
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
        var filter_range;
        var filt = '';
        var count_filters = 0;
        var url;
        var array_start = '[';
        var array_end = ']';
        var typeValue;
        var selectedColumn = '';
        var myURL = document.location;
        let params;
        $(document).ready(function(){
            $('.search-btn').click(function(){
                filter_value = $(".search-field").val();
            });
            $('select.filter-column-select').change(function() {
                typeValue = $('select.filter-column-select').find(':selected').data('type');
                selectedColumn = $('select.filter-column-select').find(':selected').val();
                if(typeValue == 'string'){
                    //default behaviour for strings
                    $('.filter-condition-dropdown-number').css('display','none');
                    $('.filter-condition-dropdown-datetime').css('display','none');
                    $('.filter-response-number').css('display','none');
                    $('.filter-response-datetime').css('display','none');
                    $('.filter-response-boolean').css('display','none');

                    //show the two wanted
                    $('.filter-condition-dropdown-string').css('display','inherit');
                    $('.filter-response-string').css('display','inherit');
                }
                else if(typeValue == 'boolean'){
                    //hide unwanted
                    $('.filter-condition-dropdown-string').css('display','none');
                    $('.filter-condition-dropdown-number').css('display','none');
                    $('.filter-condition-dropdown-datetime').css('display','none');
                    $('.filter-response-string').css('display','none');
                    $('.filter-response-number').css('display','none');
                    $('.filter-response-datetime').css('display','none');

                    //only true or false for that column is needed as input
                    $('.filter-response-boolean').css('display','inherit');
                }
                else if(typeValue == 'datetime'){
                    //hide unwanted
                    $('.filter-condition-dropdown-string').css('display','none');
                    $('.filter-condition-dropdown-number').css('display','none');
                    $('.filter-response-string').css('display','none');
                    $('.filter-response-number').css('display','none');
                    $('.filter-response-boolean').css('display','none');

                    //show what is wanted
                    $('.filter-condition-dropdown-datetime').css('display','inherit');
                    $('.filter-response-datetime').css('display','inherit');
                }
                else if(typeValue == 'number'){
                    //hide unwanted
                    $('.filter-condition-dropdown-string').css('display','none');
                    $('.filter-condition-dropdown-datetime').css('display','none');
                    $('.filter-response-string').css('display','none');
                    $('.filter-response-datetime').css('display','none');
                    $('.filter-response-boolean').css('display','none');

                    //show what is wanted
                    $('.filter-condition-dropdown-number').css('display','inherit');
                    $('.filter-response-number').css('display','inherit');

                }
                $('.apply-filter').on('click',function(){
                    params = (new URL(document.location)).search;
                    if(typeValue == 'number'){
                        var conditionUsed = $('.filter-condition-dropdown-number').find(':selected').val();
                        var response = $('.response-number').val();
                        console.log(selectedColumn,conditionUsed,response);
                        formURL(selectedColumn,conditionUsed,response,params);

                    }
                    if(typeValue == 'string'){
                        var conditionUsed = $('.filter-condition-dropdown-string').find(':selected').val();
                        var response = $('.response-string').val();
                        console.log(selectedColumn,conditionUsed,response);
                        formURL(selectedColumn,conditionUsed,response,params);

                    }
                    if(typeValue == 'datetime'){
                        var conditionUsed = $('.filter-condition-dropdown-datetime').find(':selected').val();
                        var response = $('.response-datetime').val();
                        console.log(selectedColumn,conditionUsed,response);
                        formURL(selectedColumn,conditionUsed,response,params);

                    }
                    if(typeValue == 'boolean'){
                        console.log('boolean');
                    }
                });
            });
        });

        function formURL(column, condition, response,urlparams){
            // console.log("url params=",urlparams.trim());
            // console.log(params.indexOf("?"));
            var pos = params.lastIndexOf("&");
            var pos1 = params.lastIndexOf("?");
            if(pos == -1 && pos1!=-1){
                count_filters = parseInt(params.slice(1,2).trim());
                console.log('use count for ?',parseInt(count_filters));
            }
            else if(pos == -1 && pos1 == -1){
                count_filters = parseInt(0);
                console.log('no search params found');
            }
            else if(pos!= -1 && pos1!= -1){
                count_filters = parseInt(params.slice(pos+1,pos+2).trim());
                console.log('both & and ? present so using index=',count_filters);
            }
            else{
                count_filters = parseInt(params.slice(pos+1,pos+2).trim());
                console.log(count_filters);
            }
            if(count_filters==0 && pos == -1 && pos1!=-1)
            {
                filt = filt + '&' + parseInt(count_filters+1) + '=' + selectedColumn + array_start + condition + array_end + '=' + response;
                console.log(filt);
                // count_filters++;
                document.location = myURL + filt;
            }
            else if(count_filters==0 && pos==-1 && pos1==-1)
            {
                filt = '?' + parseInt(count_filters+1) + '=' + selectedColumn + array_start + condition + array_end + '=' + response;
                console.log(filt);
                // count_filters++;
                document.location = myURL + filt;
            }
            else if(count_filters>0 && pos!=-1 && pos1!=-1){
                filt = filt + '&' + parseInt(count_filters+1) + '=' + selectedColumn + array_start + condition + array_end + '=' + response;
                console.log(filt);
                document.location = myURL + filt;
            }
            else{
                filt = '?' + parseInt(count_filters) + '=' + selectedColumn + array_start + condition + array_end + '=' + response;
                console.log(filt);
                // count_filters++;
                document.location = myURL + filt;
            }
            // console.log(filt);


        }
        </script>
    @endsection
</div>

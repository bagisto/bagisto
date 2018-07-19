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
        </div>
    </div>
    <div class="table">
        <table class="{{ $css->table }}">
            <thead class="{{-- $css->thead --}}">
                <tr>
                    <th class="{{-- $css->thead_td --}}">Mass Action</th>
                    @foreach ($columns as $column)
                    {{-- {{ dd($column->sortable) }} --}}
                    @if($column->sortable == "true")
                    <th class="$css->thead_td grid_head" data-column-name ={{ $column->name }} data-column-sort="asc">{!! $column->sorting() !!}<span class="icon sort-down-icon"></span></th>
                    @else
                        <th class="$css->thead_td grid_head">{!! $column->sorting() !!}</th>
                    @endif
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
            var allFilters1 = [];
            var filter_value;
            var filter_column;
            var filter_condition;
            var filter_range;
            var filt = '';
            var count_filters = parseInt(0);
            var url;
            var array_start = '[';
            var array_end = ']';
            var typeValue;
            var selectedColumn = '';
            var myURL = document.location;
            let params;
            $(document).ready(function(){

                params = (new URL(document.location)).search;

                if(params.length>0){
                    if(allFilters1.length == 0){
                        //call reverse url function
                        arrayFromUrl(params.slice(1,params.length));
                    }
                }
                $('.search-btn').click(function(){
                    filter_value = $(".search-field").val();
                });
                $('.grid_head').on('click', function(){

                    var column = $(this).data('column-name');
                    var currentSort = $(this).data('column-sort');

                    if(currentSort == "asc"){
                        formURL(column,'sort','desc',params);
                    }else{
                        formURL(column,'sort','asc',params);
                    }
                });
                $('select.filter-column-select').change(function() {
                    typeValue = $('select.filter-column-select').find(':selected').data('type');
                    selectedColumn = $('select.filter-column-select').find(':selected').val();
                    if(typeValue == 'string') {
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
                    else if(typeValue == 'boolean') {
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
                    else if(typeValue == 'datetime') {
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
                            formURL(selectedColumn,conditionUsed,response,params);
                        }
                        if(typeValue == 'string'){
                            var conditionUsed = $('.filter-condition-dropdown-string').find(':selected').val();
                            var response = $('.response-string').val();
                            formURL(selectedColumn,conditionUsed,response,params);

                        }
                        if(typeValue == 'datetime'){
                            var conditionUsed = $('.filter-condition-dropdown-datetime').find(':selected').val();
                            var response = $('.response-datetime').val();
                            formURL(selectedColumn,conditionUsed,response,params);
                        }
                        if(typeValue == 'boolean'){
                            console.log('boolean');
                        }
                    });

                });
                $('.remove-filter').on('click', function(){
                    console.log('removing');
                    var id = $(this).parents('.filter-one').attr('id');
                    console.log(allFilters1.length);

                    if(allFilters1.length ==  1){
                        allFilters1.pop();
                        var uri = window.location.href.toString();
                        if (uri.indexOf("?") > 0) {
                            var clean_uri = uri.substring(0, uri.indexOf("?"));
                            // window.history.replaceState({}, document.title, clean_uri);
                            document.location = clean_uri;
                        }
                    }
                    else{
                        allFilters1.splice(id,1);
                        makeURL(allFilters1);
                    }
                    console.log(allFilters1);
                });
            });

            //this function is only to barrayFromUrle used when there is search param and the allFilter is empty in order to repopulate
            // and make the filter or sort tags again
            function arrayFromUrl(x){
                // console.log(allFilters1);
                // console.log(x);
                t = x.slice(1,x.length);
                splitted = [];
                moreSplitted = [];
                splitted = t.split('&');
                for(i=0;i<splitted.length;i++){
                    moreSplitted.push(splitted[i].split('='));
                }
                for(i=0;i<moreSplitted.length;i++){
                    temp = moreSplitted[i];
                    // console.log(moreSplitted[i][2]); //use this for response
                    var pos1 = temp[1].indexOf("[");
                    var pos2 = temp[1].indexOf("]");
                    column_name = temp[1].slice(0,pos1);
                    condition_name = temp[1].slice(pos1+1,pos2);
                    var tmp = {};
                    tmp[column_name.trim()] ={
                        [condition_name.trim()]:moreSplitted[i][2]
                    };
                    allFilters1.push(tmp);
                }
                console.log('Array from URL = ',allFilters1);
                makeTagsTestPrior();
            }

            function makeTagsTestPrior() {
                var filterRepeat = 0;
                //make sure the filter or sort param is not duplicate before pushing it into the all filters array
                if(allFilters1.length!=0)
                for(var i = 0;i<allFilters1.length;i++){
                    // console.log(allFilters1[i]);
                    for(j in allFilters1[i]){
                        // console.log(allFilters1[i][j],j);
                        for(k in allFilters1[i][j])
                        {
                            // console.log('column = ',j);
                            // console.log('condition = ',k);
                            // console.log('value = ',allFilters1[i][j][k]);
                            var filter_card = '<span class="filter-one" id="'+ i +'"><span class="filter-name">'+ j +'</span><span class="filter-value">'+allFilters1[i][j][k] +'<span class="icon cross-icon remove-filter"></span></span></span>';
                            $('.filter-row-two').append(filter_card);
                        }
                    }
                }
            }

            function makeTagsTest(column, condition, response, urlparams) {

                var tmp = {};
                tmp[column] ={
                    [condition]:response
                };
                var filterRepeat = 0;
                //make sure the filter or sort param is not duplicate before pushing it into the all filters array
                if(allFilters1.length!=0)
                for(var i = 0;i<allFilters1.length;i++){
                    for(j in allFilters1[i]){
                        for(k in allFilters1[i][j])
                        {
                            if(column == j && condition == k && response == allFilters1[i][j][k]){
                                filterRepeat = 1;
                            }
                        }
                    }
                }
                if(filterRepeat == 0)
                {
                    allFilters1.push(tmp);
                    // console.log(allFilters1);
                    var filter_card = '<span class="filter-one"><span class="filter-name">'+ column +'</span><span class="filter-value">'+ response +'<span class="icon cross-icon"></span></span></span>';
                    $('.filter-row-two').append(filter_card);
                    makeURL(allFilters1);
                    count_filters++;
                }
                else
                    alert("This filter is already applied");
            }

            //prepare URL from the all filters array
            function makeURL(x) {
                var y,k,z,c,d;

                var filt_url = '';

                for(y in x){
                    k = x[y];
                    for(z in k){
                        c = k[z];
                        for(d in c){
                            console.log(y);     //first element of per which will make the url filter or sort
                            console.log(z);     //name of the column which is needed to be filtered or sorted
                            console.log(d);     //any condition or just sort
                            console.log(c[d]);  //filter value or response

                        }
                    }
                    if(y==0)
                        filt_url = filt_url + '?' + y + '=' + z + '[' + d + ']' + '=' + c[d];
                    else
                        filt_url = filt_url + '&' + y + '=' + z + '[' + d + ']' + '=' + c[d];
                }
                console.log(filt_url);
                console.log(count_filters);
                // return false;
                document.location = filt_url;
            }

            //obselete
            function formURL(column, condition, response, urlparams) {
                makeTagsTest(column, condition, response, urlparams);
                // var pos = params.lastIndexOf("&");
                // var pos1 = params.lastIndexOf("?");

                // //detect the url state
                // if(pos == -1 && pos1!=-1){
                //     count_filters = parseInt(params.slice(1,2).trim());
                //     console.log('use count for ?',parseInt(count_filters));
                // }
                // else if(pos == -1 && pos1 == -1){
                //     count_filters = parseInt(0);
                //     console.log('no search params found');
                // }
                // else if(pos!= -1 && pos1!= -1){
                //     count_filters = parseInt(params.slice(pos+1,pos+2).trim());
                //     console.log('both & and ? present so using index=',count_filters);
                // }
                // else{
                //     count_filters = parseInt(params.slice(pos+1,pos+2).trim());
                //     console.log(count_filters);
                // }

                // //act on url state
                // if(count_filters==0 && pos == -1 && pos1!=-1)
                // {
                //     filt = filt + '&' + parseInt(count_filters+1) + '=' + column + array_start + condition + array_end + '=' + response;
                //     makeTagsTest(column, condition, response, urlparams,parseInt(count_filters));
                //     // document.location = myURL + filt;
                // }
                // else if(count_filters==0 && pos==-1 && pos1==-1)
                // {
                //     filt = '?' + parseInt(count_filters+1) + '=' + column + array_start + condition + array_end + '=' + response;
                //     makeTagsTest(column, condition, response, urlparams,parseInt(count_filters));
                //     // document.location = myURL + filt;
                // }
                // else if(count_filters>0 && pos!=-1 && pos1!=-1){
                //     filt = filt + '&' + parseInt(count_filters+1) + '=' + column + array_start + condition + array_end + '=' + response;
                //     makeTagsTest(column, condition, response, urlparams,parseInt(count_filters));
                //     // document.location = myURL + filt;
                // }
                // else{
                //     filt = '?' + parseInt(count_filters) + '=' + column + array_start + condition + array_end + '=' + response;
                //     makeTagsTest(column, condition, response, urlparams,parseInt(count_filters));
                //     // document.location = myURL + filt;
                // }
            }
        </script>
    @endsection
</div>

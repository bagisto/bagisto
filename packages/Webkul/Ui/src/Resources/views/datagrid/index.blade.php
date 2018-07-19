<div class="grid-container{{-- $css->datagrid --}}">

    {{-- For loading the filters from includes directory file --}}
    @include('ui::datagrid.filters.default')

    {{-- for generating the table and its content --}}
    @include('ui::datagrid.table.default')

    {{-- Section for datagrid javascript --}}
    @section('javascript')
        <script type="text/javascript">
            var allFilters1 = [];
            var search_value;
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
                    search_value = $(".search-field").val();

                    formURL('search','all',search_value,params);  //format for search

                    alert(search_value);
                });
                $('.grid_head').on('click', function(){

                    var column = $(this).data('column-name');
                    var currentSort = $(this).data('column-sort');

                    if(currentSort == "asc"){
                        $(this).data('column-name','desc');
                        formURL(column,'sort','desc',params);
                    }else{
                        $(this).data('column-name','asc');
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
                //remove the filter and from clicking on cross icon on tag
                $('.remove-filter').on('click', function(){
                    // console.log('removing');
                    var id = $(this).parents('.filter-one').attr('id');
                    // console.log(allFilters1.length);

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
                });

                //Enable Mass Action Subsequency
                var id=[]; //for getting the id of the selected fields
                var y = parseInt(0);
                $("input[type=checkbox]").change(function() {
                    if(this.checked){
                        y = parseInt($(this).attr('id'));
                        id.push(y);
                        console.log(id);
                    }
                    else {
                        y = parseInt($(this).attr('id'));
                        var index = id.indexOf(y);
                        id.splice(index,1);
                    }
                    if(id.length>0){
                        $('.mass-action').css('display','inherit');
                        $('.table-grid-header').css('display','none');
                    }else if(id.length == 0){
                        $('.mass-action').css('display','none');
                        $('.table-grid-header').css('display','table-header-group');
                    }
                });

                //remove the mass action by clicking on the icon
                $('.mass-action-remove').on('click', function(){
                    $("input[type=checkbox]").prop('checked',false);
                    id = [];
                    $('.mass-action').css('display','none'); $('.table-grid-header').css('display','table-header-group');
                });
                // $('.mass-delete').on('click',function(){
                //     if(id.length>0){
                //         url = 'datagrid/delete';
                //         $.ajax({ type: "POST", url: url, data: id, success: function(result){
                //             console.log(result);
                //         } });
                //     }
                // });
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
                // console.log('Array from URL = ',allFilters1);
                makeTagsTestPrior();
            }

            function makeTagsTestPrior() {
                var filterRepeat = 0;
                //make sure the filter or sort param is not duplicate before pushing it into the all filters array
                if(allFilters1.length!=0)
                for(var i = 0;i<allFilters1.length;i++) {
                    // console.log(allFilters1[i]);
                    for(j in allFilters1[i]) {
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
                tmp[column] = {
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

                if(filterRepeat == 0) {
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

                for(y in x) {
                    k = x[y];
                    for(z in k) {
                        c = k[z];
                        for(d in c){
                            // console.log(y);     //first element of per which will make the url filter or sort
                            // console.log(z);     //name of the column which is needed to be filtered or sorted
                            // console.log(d);     //any condition or just sort
                            // console.log(c[d]);  //filter value or response

                        }
                    }

                    if(y == 0)
                        filt_url = filt_url + '?' + y + '=' + z + '[' + d + ']' + '=' + c[d];
                    else
                        filt_url = filt_url + '&' + y + '=' + z + '[' + d + ']' + '=' + c[d];
                }
                // console.log(filt_url);
                // console.log(count_filters);
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

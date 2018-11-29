<div class="grid-container">
    {{-- For loading the filters from includes directory file --}}
    @include('ui::datagrid.filters')

    {{-- for generating the table and its content --}}
    @include('ui::datagrid.table')

    {{-- Section for datagrid javascript --}}
    @push('scripts')
        <script type="text/javascript">
            var columns = @json($columns); //referential
            var allFilters = [];
            var search_value;
            var filter_column;
            var filter_condition;
            var filter_range;
            var count_filters = parseInt(0);
            var url;
            var array_start = '[';
            var array_end = ']';
            var typeValue;
            var selectedColumn = '';
            var myURL = document.location;
            let params;

            $(document).ready(function() {
                params = (new URL(document.location)).search;
                if(params.length > 0) {
                    if(allFilters.length == 0) {
                        //call reverse url function
                        arrayFromUrl(params.slice(1, params.length));
                    }
                }

                $('.search-btn').click(function() {
                    search_value = $(".search-field").val();
                    formURL('search', 'all', search_value, params);  //format for search
                });

                $(".search-field").on('keyup', function (e) {
                    if (e.keyCode == 13) {
                        search_value = $(".search-field").val();
                        formURL('search', 'all', search_value, params);  //format for search
                    }
                });

                //controls for header when sorting is done
                $('.grid_head').on('click', function() {
                    var column = $(this).data('column-name');
                    var currentSort = $(this).data('column-sort');

                    if(currentSort == "asc") {
                        formURL("sort", column, "desc", params);
                    }
                    else if(currentSort == "desc") {
                        formURL("sort", column, "asc", params);
                    }
                });

                $('select.filter-column-select').change(function() {
                    typeValue = $('select.filter-column-select').find(':selected').data('type');

                    col_label = $('select.filter-column-select').find(':selected').data('label');

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
                    else if(typeValue == 'number') {
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

                    $('.apply-filter').on('click',function() {

                        params = (new URL(document.location)).search;

                        if(typeValue == 'number') {
                            var conditionUsed = $('.filter-condition-dropdown-number').find(':selected').val();
                            var response = $('.response-number').val();

                            formURL(selectedColumn,conditionUsed,response,params,col_label);
                        }
                        if(typeValue == 'string') {
                            var conditionUsed = $('.filter-condition-dropdown-string').find(':selected').val();
                            var response = $('.response-string').val();

                            formURL(selectedColumn,conditionUsed,response,params,col_label);
                        }
                        if(typeValue == 'datetime') {
                            var conditionUsed = $('.filter-condition-dropdown-datetime').find(':selected').val();
                            var response = $('.response-datetime').val();

                            formURL(selectedColumn,conditionUsed,response,params,col_label);
                        }
                        if(typeValue == 'boolean') { //use select dropdown with two values true and false

                            console.log('boolean');
                        }
                    });
                });

                //remove the filter and from clicking on cross icon on tag
                $('.remove-filter').on('click', function() {

                    var id = $(this).parents('.filter-one').attr('id');
                    if(allFilters.length ==  1) {
                        allFilters.pop();
                        var uri = window.location.href.toString();
                        if (uri.indexOf("?") > 0) {
                            var clean_uri = uri.substring(0, uri.indexOf("?"));
                            // window.history.replaceState({}, document.title, clean_uri);
                            document.location = clean_uri;
                        }
                    }
                    else {
                        allFilters.splice(id,1);
                        makeURL();
                    }
                });

                //Enable Mass Action Subsequency
                var id=[]; //for getting the id of the selected fields
                var y = parseInt(0);

                // master checkbox for selecting all entries
                $("input[id=mastercheckbox]").change(function() {
                    if($("input[id=mastercheckbox]").prop('checked') == true) {
                        $('.indexers').each(function() {
                            this.checked = true;
                            if(this.checked){
                                y = parseInt($(this).attr('id')); id.push(y);
                            }
                        });

                        $('.mass-action').css('display','');
                        $('.table-grid-header').css('display','none');
                        // $('.selected-items').html(id.toString());
                        $('#indexes').val(id);
                    }
                    else if($("input[id=mastercheckbox]").prop('checked') == false) {
                        $('.indexers').each(function(){ this.checked = false; });

                        id = [];

                        $('.mass-action').css('display','none');
                        $('.table-grid-header').css('display','');
                        $('#indexes').val('');
                    }
                });

                $('.massaction-remove').on('click', function() {

                    id = [];

                    $('.mass-action').css('display','none');

                    if($('#mastercheckbox').prop('checked')) {
                        $('#mastercheckbox').prop('checked',false);
                    }

                    $("input[class=indexers]").each(function() {
                        if($(this).prop('checked')) {
                            $(this).prop('checked',false);
                        }
                    });

                    $('.table-grid-header').css('display','');
                });

                $("input[class=indexers]").change(function() {

                    if(this.checked) {
                        y = parseInt($(this).attr('id'));
                        id.push(y);
                    }
                    else {
                        y = parseInt($(this).attr('id'));
                        var index = id.indexOf(y);
                        id.splice(index,1);
                    }

                    if(id.length>0) {
                        $('.mass-action').css('display','');
                        $('.table-grid-header').css('display','none');
                        $('#indexes').val(id);

                    }else if(id.length == 0) {

                        $('.mass-action').css('display','none');
                        $('.table-grid-header').css('display','');
                        $('#indexes').val('');

                        if($('#mastercheckbox').prop('checked')) {
                            $('#mastercheckbox').prop('checked',false);
                        }
                    }
                });

            });

            //make the url from the array and redirect
            function makeURL(repetition = false) {
                if(allFilters.length>0 && repetition == false)
                {
                    for(i=0;i<allFilters.length;i++) {
                        if(i==0){
                            url = '?' + allFilters[i].column + '[' + allFilters[i].cond + ']' + '=' + allFilters[i].val;
                        }
                        else
                            url = url + '&' + allFilters[i].column + '[' + allFilters[i].cond + ']' + '=' + allFilters[i].val;
                    }
                    document.location = url;

                } else if(allFilters.length>0 && repetition == true) {
                    //this is the case when the filter is being repeated on a single column with different condition and value
                    for(i=0;i<allFilters.length;i++) {

                        if(i==0) {
                            url = '?' + allFilters[i].column + '[' + allFilters[i].cond + ']' + '=' + allFilters[i].val;
                        }
                        else {
                            url = url + '&' + allFilters[i].column + '[' + allFilters[i].cond + ']' + '=' + allFilters[i].val;
                        }
                    }
                    document.location = url;
                } else {
                    var uri = window.location.href.toString();
                    var clean_uri = uri.substring(0, uri.indexOf("?"));

                    document.location = clean_uri;
                }
            }

            //make the filter array from url after being redirected
            function arrayFromUrl(urlstring) {

                var obj={};
                t = urlstring.slice(0,urlstring.length);
                splitted = [];
                moreSplitted = [];
                splitted = t.split('&');
                for(i=0;i<splitted.length;i++) {
                    moreSplitted.push(splitted[i].split('='));
                }
                for(i=0;i<moreSplitted.length;i++) {
                    col = moreSplitted[i][0].replace(']','').split('[')[0];
                    cond = moreSplitted[i][0].replace(']','').split('[')[1]
                    val = moreSplitted[i][1];
                    obj.column = col;
                    obj.cond = cond;
                    obj.val = val;

                    if(col!=undefined && cond!=undefined && val!=undefined)
                        allFilters.push(obj);
                    obj = {};
                }
                makeTags();
            }

            //Use the label to prevent the display of column name on the body
            function makeTags() {
                var filterRepeat = 0;

                if(allFilters.length!=0)
                for(var i = 0;i<allFilters.length;i++) {

                    if(allFilters[i].column == "sort") {
                        col_label_tag = $('li[data-name="'+allFilters[i].cond+'"]').text();

                        var filter_card = '<span class="filter-one" id="'+ i +'"><span class="filter-name">'+ col_label_tag +'</span><span class="filter-value"><span class="f-value">'+ allFilters[i].val +'</span><span class="icon cross-icon remove-filter"></span></span></span>';

                        sorted_col = allFilters[i].cond;

                        var apply_on_column = $('th[data-column-name="'+sorted_col+'"]').children('.icon');

                        if(allFilters[i].val == "asc") {
                            apply_on_column.addClass('sort-down-icon');
                        } else {
                            apply_on_column.addClass('sort-up-icon');
                        }

                        $('.filter-row-two').append(filter_card);

                    } else if(allFilters[i].column == "search") {
                        col_label_tag = "Search";

                        var filter_card = '<span class="filter-one" id="'+ i +'"><span class="filter-name">'+ col_label_tag +'</span><span class="filter-value"><span class="f-value">'+ allFilters[i].val +'</span><span class="icon cross-icon remove-filter"></span></span></span>';
                        $('.filter-row-two').append(filter_card);

                    } else {
                        col_label_tag = $('li[data-name="'+allFilters[i].column+'"]').text().trim();

                        var filter_card = '<span class="filter-one" id="'+ i +'"><span class="filter-name">'+ col_label_tag +'</span><span class="filter-value"><span class="f-value">'+ allFilters[i].val +'</span><span class="icon cross-icon remove-filter"></span></span></span>';
                        $('.filter-row-two').append(filter_card);
                    }

                }
            }

            //This is being used for validation of url params and making array of filters
            function formURL(column, condition, response, urlparams,clabel) {

                /* validate the conditions here and do the replacements and
                push here in the all filters array */
                var obj1 = {};

                console.log(allFilters.length);

                if(column == "" || condition == "" || response == "") {
                    alert("Some of the required field is null, please check column, condition and value properly.");

                    return false;
                }
                else {
                    if(allFilters.length>0) {
                        //case for repeated filter

                        if(column != "sort" && column != "search") {

                            filter_repeated = 0;

                            for(j=0; j<allFilters.length; j++) {

                                if(allFilters[j].column == column && allFilters[j].cond == condition && allFilters[j].val == response)
                                {
                                    filter_repeated = 1;

                                    return false;
                                } else if(allFilters[j].column == column) {
                                    filter_repeated = 1;

                                    allFilters[j].cond = condition;
                                    allFilters[j].val = response;

                                    makeURL(true);
                                }
                            }
                            if(filter_repeated == 0) {

                                obj1.column = column;
                                obj1.cond = condition;
                                obj1.val = response;
                                obj1.label = clabel;

                                allFilters.push(obj1);
                                obj1 = {};

                                makeURL();
                            }
                        }
                        if(column == "sort") {
                            sort_exists = 0;
                            for(j=0;j<allFilters.length;j++) {
                                if(allFilters[j].column == "sort") {
                                    if(allFilters[j].column==column && allFilters[j].cond==condition && allFilters[j].val==response) {

                                        if(response=="asc") {
                                            allFilters[j].column = column;
                                            allFilters[j].cond = condition;
                                            allFilters[j].val = "desc";
                                            allFilters[j].label = clabel;

                                            makeURL();

                                        }
                                        else {
                                            allFilters[j].column = column;
                                            allFilters[j].cond = condition;
                                            allFilters[j].val = "asc";
                                            allFilters[j].label = clabel;

                                            makeURL();
                                        }
                                    }
                                    else {
                                        allFilters[j].column = column;
                                        allFilters[j].cond = condition;
                                        allFilters[j].val = response;
                                        allFilters[j].label = clabel;

                                        makeURL();
                                    }
                                }
                            }
                        }
                        if(column == "search") {
                            search_found = 0;
                            for(j=0;j<allFilters.length;j++) {
                                if(allFilters[j].column == "search") {
                                    allFilters[j].column = column;
                                    allFilters[j].cond = condition;
                                    allFilters[j].val = response;
                                    allFilters[j].label = clabel;
                                    makeURL();
                                }
                            }
                            for(j=0;j<allFilters.length;j++) {
                                if(allFilters[j].column == "search") {
                                    search_found = 1;
                                }
                            }
                            if(search_found == 0) {
                                obj1.column = column;
                                obj1.cond = condition;
                                obj1.val = response;
                                obj1.label = clabel;
                                allFilters.push(obj1);
                                obj1 = {};
                                makeURL();
                            }
                        }
                    } else {
                        obj1.column = column;
                        obj1.cond = condition;
                        obj1.val = response;
                        obj1.label = clabel;

                        allFilters.push(obj1);
                        obj1 = {};

                        makeURL();
                    }
                }
            }
            function confirm_click(message){
                if (confirm(message)) {
                    //do the action required
                } else {
                    return false;
                }
            }
        </script>
    @endpush

</div>

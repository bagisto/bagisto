<div class="grid-container{{-- $css->datagrid --}}">
    <div class="{{ $css->filter }}filter-wrapper">
        <div class="filter-row-one">
            <div class="search-filter" style="display: inline-flex; align-items: center;">
                <input type="search" class="control filter-value" placeholder="Search Users" style="border-radius: 0px;border-right:0px;" value=""/>
                <span class="btn-filter icon search-icon" style="border:2px solid #c7c7c7; height:36px; width:39px;"></span>
            </div>
            <div class="dropdown-filters">
                <div class="column-filter">
                    <select class="control filter-col">
                        <option selected disabled>Columns</option>
                        @foreach($columns as $column)
                            <option value="{{ $column->name }}">{{ $column->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="more-filters">
                    <select class="control filter-cond">
                        <option selected disabled>Filters</option>
                        @foreach($operators as $key=>$value)
                            <option>{{ $key }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="filter-row-two">
            {{ $columns }}<br/>
            {{ json_encode($operators) }}
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
                    <th class="{{-- $css->thead_td --}}">{!! $column->sorting() !!}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="{{ $css->tbody }}">

                @foreach ($results as $result)
                <tr>
                    <td class="{{-- $css->tbody_td --}}"><input type="checkbox" /></td>
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
        $(document).ready(function(){
            console.log('ready');
            $('.btn-filter').click(function(){
                filter_value = $(".filter-value").val();
                filter_column = $(".filter-col").val();
                filter_condition = $(".filter-cond").val();
                if(filter_value!="" && filter_condition!=null || filter_column!=null)
                    flag=1;
                if(flag==1){
                    formURL(filter_value,filter_column,filter_condition);
                }
                else
                    alert('Please enter filter criteria in all three fields above');
            });
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

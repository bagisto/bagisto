<thead>
    <tr class="mass-action" style="display: none; height:63px;">
        <th colspan="{{ count($columns)+1 }}" style="width: 100%;">
            <div class="mass-action-wrapper">

                <span class="massaction-remove">
                    <span class="icon checkbox-dash-icon"></span>
                </span>

                @foreach($massoperations as $massoperation)
                    @if($massoperation['type'] == "button")

                    <form onsubmit="return confirm('Are You Sure?');"
                        @if(strtoupper($massoperation['method']) == "GET")
                            method="GET"
                        @else
                            method="POST"
                        @endif

                        action="{{ $massoperation['route'] }}">

                        {{ csrf_field() }}

                        @if(strtoupper($massoperation['method'])!= "GET" && strtoupper($massoperation['method'])!= "POST")

                        @method($massoperation['method'])

                        @endif

                        <input type="hidden" value="{{ $table }}" name="table_name">

                        <input type="hidden" id="indexes" name="indexes" value="">

                        <input class="btn btn-primary btn-sm" type="submit" value="Delete">

                    </form>

                    @elseif($massoperation['type'] == "select")

                        <form
                            @if(strtoupper($massoperation[ 'method'])=="GET" || strtoupper($massoperation[ 'method'])=="POST" )

                                method="{{ strtoupper($massoperation['method']) }}"

                            @else
                                method="POST"

                                @endif

                            action="{{ $massoperation['route'] }}">
                                {{ csrf_field() }}
                            @if(strtoupper($massoperation['method'])!= "GET" && strtoupper($massoperation['method'])!= "POST")

                                @method($massoperation['method'])

                            @endif

                            <input type="hidden" id="indexes" name="indexes" value="">

                            <select name="choices">
                                @foreach($massoperation['options'] as $option)
                                    <option>{{ $option }}</option>
                                @endforeach
                            </select>

                            <input class="btn btn-primary btn-sm" type="submit" value="Submit">
                        </form>
                    @endif
                @endforeach
            </div>
        </th>
    </tr>
    <tr class="table-grid-header">
        <th>
            <span class="checkbox">
                <input type="checkbox" id="mastercheckbox">
                <label class="checkbox-view" for="checkbox"></label>
            </span>
        </th>
        @foreach ($columns as $column)
            @if($column->sortable == "true")
                <th class="grid_head sortable"
                    @if(strpos($column->alias, ' as '))
                        <?php $exploded_name = explode(' as ',$column->name); ?>
                        data-column-name="{{ $exploded_name[0] }}"
                    @else
                        data-column-name="{{ $column->alias }}"
                    @endif

                    data-column-label="{{ $column->label }}"
                    data-column-sort="asc">
                    {!! $column->sorting() !!}<span class="icon"></span>
                </th>
                @else
                    <th class="grid_head"
                        data-column-name="{{ $column->alias }}"
                        data-column-label="{{ $column->label }}">
                        {!! $column->sorting() !!}
                    </th>
            @endif
        @endforeach
        @if(count($actions))
            <th style="width: 85px;">
                {{ __('ui::app.datagrid.actions') }}
            </th>
        @endif
    </tr>
</thead>
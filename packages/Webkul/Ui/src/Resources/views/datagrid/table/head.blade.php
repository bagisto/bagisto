<thead>
    @if(count($massoperations))
        <tr class="mass-action" style="display: none; height: 63px;">
            <th colspan="10" style="width: 100%;">
                <div class="mass-action-wrapper">
                    <span class="massaction-remove">
                        <span class="icon checkbox-dash-icon"></span>
                    </span>

                    <form method="POST" style="display: inline-flex;" id="mass-action-form">
                        @csrf()
                        <input type="hidden" id="indexes" name="indexes" value="">

                        <div class="control-group">
                            <select class="control massaction-type" name="massaction-type" id="massaction-type">
                                @foreach($massoperations as $key => $massoperation)
                                    <option @if($key == 0) selected @endif value="{{ $key }}">{{ $massoperation['type'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        @foreach($massoperations as $key => $value)
                            @if($value['type'] == 'update')
                                <div class="control-group" style="display: none; margin-left: 10px;" id="update-options">
                                    <select class="options control" name="update-options" id="option-type">
                                        @foreach($value['options'] as $key => $value)
                                            <option value="{{ $key }}" @if($key == 0) selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>

                                    <input type="hidden" name="selected-option-text" id="selected-option-text" value="">
                                </div>
                            @endif
                        @endforeach

                        <input type="submit" class="btn btn-sm btn-primary" style="margin-left: 10px;">
                    </form>
                </div>
            </th>
        </tr>
    @endif
    <tr class="table-grid-header">
        @if(count($massoperations))
            <th>
                <span class="checkbox">
                    <input type="checkbox" id="mastercheckbox">
                    <label class="checkbox-view" for="checkbox"></label>
                </span>
            </th>
        @endif
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
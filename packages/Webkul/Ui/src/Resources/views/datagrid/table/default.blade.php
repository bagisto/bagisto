<div class="table">
    <table class="{{ $css->table }}">
        <thead class="{{-- $css->thead --}} table-grid-header">
            <tr>
                <th class="{{-- $css->thead_td --}}">Mass Action</th>
                @foreach ($columns as $column) {{-- {{ dd($column->sortable) }} --}} @if($column->sortable == "true")
                <th class="$css->thead_td grid_head" data-column-name={{ $column->name }} data-column-sort="asc">{!! $column->sorting() !!}<span class="icon sort-down-icon"></span></th>
                @else
                <th class="$css->thead_td grid_head">{!! $column->sorting() !!}</th>
                @endif @endforeach
            </tr>
        </thead>
        <div class="mass-action">
            <div class="mass-action-block">
                <span class="icon checkbox-dash-icon mass-action-remove"></span>
                <div class="mass-action-dropdown">
                    {{-- <select class="control">
                        <option value="x">A</option>
                        <option value="y">B</option>
                        <option value="z">C</option>
                    </select> --}}
                    <div class="dropdown-toggle">
                        <div class="dropdown-header">
                            <span class="name">Actions</span> {{-- <span class="role">Filter</span> --}}
                            <i class="icon arrow-down-icon active"></i>
                        </div>
                    </div>
                    <div class="dropdown-list bottom-left" style="display: none;">
                        <div class="dropdown-container">
                            <ul>
                                {{-- <li class="mass-delete">Delete&emsp;&emsp;<span class="btn btn-primary btn-sm">Apply</span></li> --}}
                                <li>
                                    <form action="{{ route('admin.datagrid.delete') }}" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" id="indexes" value="">
                                        <input type="Submit" class="btn btn-primary btn-sm">
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>

            </div>
        </div>
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
    {{--
    @include('ui::partials.pagination') --}}
</div>

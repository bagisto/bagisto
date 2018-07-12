<div class="grid-container{{-- $css->datagrid --}}">
    <div class="{{ $css->filter }} filter-wrapper">
        {{-- <div class="filter-row-one">
            <div class="search-filter">
                <input type="search" class="control" placeholder="Search Users" />
            </div>
            <div class="dropdown-filters">
                <div class="column-filter">
                    <select class="control">
                        <option selected disabled>Columns</option>
                        <option>All Columns</option>
                        <option>Column 1</option>
                        <option>Column 2</option>
                        <option>Column 3</option>

                    </select>
                </div>
                <div class="more-filters">
                    <select class="control">
                        <option selected disabled>Filters</option>
                        <option>Filter 1</option>
                        <option>Filter 2</option>
                        <option>Filter 3</option>
                        <option>Filter 4</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="filter-row-two">
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
        </div> --}}
    </div>
    <div class="table">
        <table class="{{ $css->table }}">
            <thead class="{{-- $css->thead --}}">
                <tr>
                    @foreach ($columns as $column)
                    <th class="{{-- $css->thead_td --}}">{!! $column->sorting() !!}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="{{ $css->tbody }}">

                @foreach ($results as $result)
                <tr>
                    @foreach ($columns as $column)
                    <td class="{{-- $css->tbody_td --}}">{!! $column->render($result) !!}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="{{ $css->pagination }}" style="margin-top:15px;">
            {{-- <div class="pagination">
                <a class="page-item previous">
                    <i class="icon angle-right-icon"></i>
                </a>
                <a class="page-item">1</a>
                <a class="page-item" href="#status/6/page/2">2</a>
                <a class="page-item active" href="#status/6/page/3">3</a>
                <a class="page-item" href="#status/6/page/4">4</a>
                <a class="page-item" href="#status/6/page/5">5</a>
                <a href="#status/6/page/2" class="page-item next">
                    <i class="icon angle-left-icon"></i>
                </a>
            </div> --}}
        </div>
    </div>

</div>

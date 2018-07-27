<template>
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
</template>
<script>
export default {
  mounted: function() {
    console.log("Filter compoenent mounted");
  }
};
</script>

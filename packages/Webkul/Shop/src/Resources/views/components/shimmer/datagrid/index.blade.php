@props(['isMultiRow' => false])

<div>
    <x-shop::shimmer.datagrid.toolbar></x-shop::shimmer.datagrid.toolbar>

    <div class="mt-[16px] flex">
        <div class="w-full">
            <div class="table-responsive box-shadow grid w-full overflow-hidden rounded-[4px] bg-white">
                <x-shop::shimmer.datagrid.table.head :isMultiRow="$isMultiRow"></x-shop::shimmer.datagrid.table.head>

                <x-shop::shimmer.datagrid.table.body :isMultiRow="$isMultiRow"></x-shop::shimmer.datagrid.table.body>
            </div>
        </div>
    </div>
</div>

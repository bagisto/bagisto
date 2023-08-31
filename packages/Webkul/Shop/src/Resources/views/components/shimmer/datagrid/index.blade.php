@props(['isMultiRow' => false])

<div>
    <x-shop::shimmer.datagrid.toolbar/>

    <div class="mt-[30px] flex border rounded-[12px] overflow-x-auto">
        <div class="w-full">
            <div class="table-responsive box-shadow grid w-full overflow-hidden rounded-[4px] bg-white">
                <x-shop::shimmer.datagrid.table.head :isMultiRow="$isMultiRow"></x-shop::shimmer.datagrid.table.head>

                <x-shop::shimmer.datagrid.table.body :isMultiRow="$isMultiRow"></x-shop::shimmer.datagrid.table.body>

                <x-shop::shimmer.datagrid.table.footer/>
            </div>
        </div>
    </div>
</div>

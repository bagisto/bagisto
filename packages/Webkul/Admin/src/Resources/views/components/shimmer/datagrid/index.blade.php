@props(['isMultiRow' => false])

<div>
    <x-admin::shimmer.datagrid.toolbar/>

    <div class="flex mt-[16px]">
        <div class="w-full">
            <div class="table-responsive grid w-full box-shadow rounded-[4px] bg-white dark:bg-gray-900 overflow-hidden">
                <x-admin::shimmer.datagrid.table.head :isMultiRow="$isMultiRow"></x-admin::shimmer.datagrid.table.head>

                <x-admin::shimmer.datagrid.table.body :isMultiRow="$isMultiRow"></x-admin::shimmer.datagrid.table.body>
            </div>
        </div>
    </div>
</div>

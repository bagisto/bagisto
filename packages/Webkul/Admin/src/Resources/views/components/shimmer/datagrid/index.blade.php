@props(['isMultiRow' => false])

<div>
    <x-admin::shimmer.datagrid.toolbar></x-admin::shimmer.datagrid.toolbar>

    <div class="flex mt-[16px]">
        <div class="w-full">
            <div class="table-responsive grid w-full shadow-[0px_8px_10px_0px_rgba(0,_0,_0,_0.20)] border-[1px] border-gray-300 rounded-[4px] bg-white overflow-hidden">
                <x-admin::shimmer.datagrid.table.head :isMultiRow="$isMultiRow"></x-admin::shimmer.datagrid.table.head>

                <x-admin::shimmer.datagrid.table.body :isMultiRow="$isMultiRow"></x-admin::shimmer.datagrid.table.body>
            </div>
        </div>
    </div>
</div>
@props(['isMultiRow' => false])

<div>
    <x-admin::shimmer.datagrid.toolbar />

    <div class="flex mt-4">
        <div class="w-full">
            <div class="table-responsive grid w-full box-shadow rounded bg-white dark:bg-gray-900 overflow-hidden">
                <x-admin::shimmer.datagrid.table.head :isMultiRow="$isMultiRow" />

                <x-admin::shimmer.datagrid.table.body :isMultiRow="$isMultiRow" />
            </div>
        </div>
    </div>
</div>

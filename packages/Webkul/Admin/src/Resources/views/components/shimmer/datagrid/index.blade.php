@props(['isMultiRow' => false])

<div>
    <x-admin::shimmer.datagrid.toolbar />

    <div class="mt-4 flex">
        <div class="w-full">
            <div class="table-responsive box-shadow grid w-full overflow-hidden rounded bg-white dark:bg-gray-900">
                <x-admin::shimmer.datagrid.table.head :isMultiRow="$isMultiRow" />

                <x-admin::shimmer.datagrid.table.body :isMultiRow="$isMultiRow" />
            </div>
        </div>
    </div>
</div>

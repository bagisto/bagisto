@props([
    'isMultiRow' => false,
    'card'       => null,
    'groups'     => null,
    'imageGroup' => null,
    'massAction' => null,
    'template'   => null,
    'columns'    => 6,
])

<div>
    <x-admin::shimmer.datagrid.toolbar />

    <div class="mt-4 flex">
        <div class="w-full">
            <div class="table-responsive box-shadow grid w-full overflow-hidden rounded bg-white dark:bg-gray-900">
                <!-- `card` is forwarded rather than left to default from `isMultiRow` inside each part, so this placeholder and the one the datagrid swaps in for it agree about whether the grid cards on a phone. -->
                <x-admin::shimmer.datagrid.table.head
                    :isMultiRow="$isMultiRow"
                    :card="$card"
                    :groups="$groups"
                    :massAction="$massAction"
                    :template="$template"
                    :columns="$columns"
                />

                <x-admin::shimmer.datagrid.table.body
                    :isMultiRow="$isMultiRow"
                    :card="$card"
                    :groups="$groups"
                    :imageGroup="$imageGroup"
                    :massAction="$massAction"
                    :template="$template"
                    :columns="$columns"
                />
            </div>
        </div>
    </div>
</div>

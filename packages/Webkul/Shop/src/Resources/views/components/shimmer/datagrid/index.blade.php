@props(['isMultiRow' => false])

<div>
    <x-shop::shimmer.datagrid.toolbar />

    <div class="mt-8 flex overflow-x-auto rounded-xl border">
        <div class="w-full">
            <div class="table-responsive box-shadow grid w-full overflow-hidden rounded bg-white">
                <x-shop::shimmer.datagrid.table.head :isMultiRow="$isMultiRow" />

                <x-shop::shimmer.datagrid.table.body :isMultiRow="$isMultiRow" />

                <x-shop::shimmer.datagrid.table.footer />
            </div>
        </div>
    </div>
</div>

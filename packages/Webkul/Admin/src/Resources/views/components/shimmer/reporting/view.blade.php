<!-- Page Header -->
<div class="flex gap-4 justify-between items-center mb-5 max-sm:flex-wrap">
    <!-- Title -->
    <div class="grid gap-1.5">
        <div class="shimmer w-[150px] h-6"></div>
    </div>

    <!-- Actions -->
    <div class="flex gap-1.5 items-center">
        <div class="shimmer w-6 h-6 rounded-md"></div>
        <div class="shimmer w-[88px] h-[39px] rounded-md"></div>
        <div class="shimmer w-[140px] h-[39px] rounded-md"></div>
        <div class="shimmer w-[140px] h-[39px] rounded-md"></div>
    </div>
</div>

<div class="table-responsive grid w-full box-shadow rounded bg-white dark:bg-gray-900 overflow-hidden">
    <x-admin::shimmer.datagrid.table.head />

    <x-admin::shimmer.datagrid.table.body />
</div>
<!-- Page Header -->
<div class="mb-5 flex items-center justify-between gap-4 max-sm:flex-wrap">
    <!-- Title -->
    <div class="grid gap-1.5">
        <div class="shimmer h-6 w-[150px]"></div>
    </div>

    <!-- Actions -->
    <div class="flex items-center gap-1.5">
        <div class="shimmer h-6 w-6 rounded-md"></div>
        <div class="shimmer h-[39px] w-[88px] rounded-md"></div>
        <div class="shimmer h-[39px] w-[140px] rounded-md"></div>
        <div class="shimmer h-[39px] w-[140px] rounded-md"></div>
    </div>
</div>

<div class="table-responsive box-shadow grid w-full overflow-hidden rounded bg-white dark:bg-gray-900">
    <x-admin::shimmer.datagrid.table.head />

    <x-admin::shimmer.datagrid.table.body />
</div>
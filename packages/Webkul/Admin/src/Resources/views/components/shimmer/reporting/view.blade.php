<!-- Page Header -->
<div class="mb-5 flex items-center justify-between gap-4 max-sm:flex-wrap">
    <!-- Title -->
    <div class="grid gap-1.5">
        <div class="shimmer h-6 w-37.5"></div>
    </div>

    <!-- Back Button and Export Button -->
    <div class="flex items-center gap-1.5">
        <div class="shimmer h-9.75 w-16.25 rounded-md"></div>
        <div class="shimmer h-9.75 w-26 rounded-md"></div>
    </div>
</div>

<div class="mb-5 flex items-center justify-between gap-4 max-sm:flex-wrap">
    <!-- Channel and Day Filter -->
    <div class="flex items-center gap-x-1">
        <div class="shimmer h-9.5 w-41 rounded-md"></div>
        <div class="shimmer h-9.75 w-22 rounded-md"></div>
    </div>

    <!-- Date Filters -->
    <div class="flex items-center gap-1.5">
        <div class="shimmer h-9.75 w-35 rounded-md"></div>
        <div class="shimmer h-9.75 w-35 rounded-md"></div>
    </div>
</div>

<div class="table-responsive box-shadow grid w-full overflow-hidden rounded-sm bg-white dark:bg-gray-900">
    <x-admin::shimmer.datagrid.table.head />

    <x-admin::shimmer.datagrid.table.body />
</div>
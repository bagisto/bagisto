<!-- Page Header -->
<div class="flex gap-[16px] justify-between items-center mb-[20px] max-sm:flex-wrap">
    <!-- Title -->
    <div class="grid gap-[6px]">
        <div class="shimmer w-[150px] h-[24px]"></div>
    </div>

    <!-- Actions -->
    <div class="flex gap-[6px]">
        <div class="shimmer w-[88px] h-[39px] rounded-[6px]"></div>
        <div class="shimmer w-[140px] h-[39px] rounded-[6px]"></div>
        <div class="shimmer w-[140px] h-[39px] rounded-[6px]"></div>
    </div>
</div>

<div class="table-responsive grid w-full box-shadow rounded-[4px] bg-white dark:bg-gray-900 overflow-hidden">
    <x-admin::shimmer.datagrid.table.head/>

    <x-admin::shimmer.datagrid.table.body/>
</div>
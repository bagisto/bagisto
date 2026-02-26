<!-- Last Search Terms Shimmer -->
<div class="box-shadow relative flex-1 rounded bg-white p-4 dark:bg-gray-900">
    <!-- Header -->
    <div class="mb-4 flex items-center justify-between">
        <div class="shimmer h-[17px] w-[150px]"></div>

        <div class="shimmer h-[21px] w-[79px]"></div>
    </div>
    
    <!-- Tabel Shimmer -->
    <div class="table-responsive box-shadow grid w-full overflow-hidden rounded bg-white dark:bg-gray-900">
        <x-admin::shimmer.datagrid.table.head />

        <x-admin::shimmer.datagrid.table.body />
    </div>
</div>
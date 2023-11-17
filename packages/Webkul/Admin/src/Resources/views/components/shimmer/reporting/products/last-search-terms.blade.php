<!-- Last Search Terms Shimmer -->
<div class="flex-1 relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
    <!-- Header -->
    <div class="flex items-center justify-between mb-[16px]">
        <div class="shimmer w-[150px] h-[17px]"></div>

        <div class="shimmer w-[79px] h-[21px]"></div>
    </div>
    
    <!-- Tabel Shimmer -->
    <div class="table-responsive grid w-full box-shadow rounded-[4px] bg-white dark:bg-gray-900 overflow-hidden">
        <x-admin::shimmer.datagrid.table.head/>

        <x-admin::shimmer.datagrid.table.body/>
    </div>
</div>
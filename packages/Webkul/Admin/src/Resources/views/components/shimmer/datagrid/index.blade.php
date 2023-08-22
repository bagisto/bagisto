<div>
    <div class="flex gap-[16px] mt-[28px] justify-between items-center max-md:flex-wrap">
        <!-- Left Toolbar -->
        <div class="flex gap-x-[4px]">
            <!-- Mass Actions Panel --><!-- Filters And Search Panel -->
            <div class="flex gap-x-[4px] w-full items-center">
                <!-- Filters Activation Button -->
                <div class="">
                    <div class="shimmer w-[108px] h-[38px] rounded-[6px]"></div>
                </div>

                <!-- Search Panel -->
                <div class="shimmer w-[262px] h-[38px]"></div>

                <!-- Information Panel -->
                <div class="pl-[10px]">
                    <p class="shimmer w-[75px] h-[17px]"></p>
                </div>
            </div>
        </div>

        <!-- Right Toolbar -->
        <div class="flex gap-x-[16px]">
            <span class="shimmer w-[36px] h-[38px]"></span>

            <div class="flex items-center gap-x-[8px]">
                <div class="shimmer w-[72px] h-[38px] rounded-[6px]"></div>

                <p class="shimmer w-[56px] h-[17px]"></p>

                <div class="shimmer w-[35px] h-[38px]"></div>

                <div class="shimmer w-[28px] h-[24px] rounded-[6px]"></div>

                <!-- Pagination -->
                <div class="flex items-center gap-[4px]">
                    <div class="shimmer w-[38px] h-[38px]"></div>

                    <div class="shimmer w-[38px] h-[38px]"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex mt-[16px]">
        <div class="w-full">
            <div class="table-responsive grid w-full shadow-[0px_8px_10px_0px_rgba(0,_0,_0,_0.20)] border-[1px] border-gray-300 rounded-[4px] bg-white overflow-hidden">
                <x-admin::shimmer.datagrid.table.head></x-admin::shimmer.datagrid.table.head>

                <x-admin::shimmer.datagrid.table.body></x-admin::shimmer.datagrid.table.body>
            </div>
        </div>
    </div>
</div>
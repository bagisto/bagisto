<div class="flex flex-col gap-[15px] flex-1 max-xl:flex-auto">
    <!-- Sales Section -->
    <div class="relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
        <!-- Header -->
        <div class="flex items-center justify-between mb-[16px]">
            <div class="shimmer w-[150px] h-[17px]"></div>

            <div class="shimmer w-[79px] h-[21px]"></div>
        </div>

        <x-admin::shimmer.reporting.graph/>
    </div>

    <!-- Purchase Funnel and Abandoned Carts Sections Container -->
    <div class="flex justify-between gap-[15px] flex-1 max-xl:flex-auto">
        <!-- Purchase Funnel Section -->
        <div class="flex-1 relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
            <!-- Header -->
            <div class="shimmer w-[150px] h-[17px] mb-[16px]"></div>

            <x-admin::shimmer.reporting.sales.purchase-funnel/>
        </div>

        <!-- Abandoned Carts Section -->
        <div class="flex-1 relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
            <!-- Header -->
            <div class="flex items-center justify-between mb-[16px]">
                <div class="shimmer w-[150px] h-[17px]"></div>

                <div class="shimmer w-[79px] h-[21px]"></div>
            </div>

            <x-admin::shimmer.reporting.sales.abandoned-carts/>
        </div>
    </div>

    <!-- Total Orders and Average Order Value Sections Container -->
    <div class="flex justify-between gap-[15px] flex-1 max-xl:flex-auto">
        <!-- Total Orders Section -->
        <div class="flex-1 relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
            <!-- Header -->
            <div class="flex items-center justify-between mb-[16px]">
                <div class="shimmer w-[150px] h-[17px]"></div>

                <div class="shimmer w-[79px] h-[21px]"></div>
            </div>
            
            <x-admin::shimmer.reporting.graph/>
        </div>

        <!-- Average Order Value Section -->
        <div class="flex-1 relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
            <!-- Header -->
            <div class="flex items-center justify-between mb-[16px]">
                <div class="shimmer w-[150px] h-[17px]"></div>

                <div class="shimmer w-[79px] h-[21px]"></div>
            </div>
            
            <x-admin::shimmer.reporting.graph/>
        </div>
    </div>

    <!-- Tax Collected and Shipping Collected Sections Container -->
    <div class="flex justify-between gap-[15px] flex-1 max-xl:flex-auto">
        <!-- Tax Collected Section -->
        <div class="flex-1 relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
            <!-- Header -->
            <div class="flex items-center justify-between mb-[16px]">
                <div class="shimmer w-[150px] h-[17px]"></div>

                <div class="shimmer w-[79px] h-[21px]"></div>
            </div>
            
            <div class="grid gap-[16px]">
                <x-admin::shimmer.reporting.graph/>

                <div class="shimmer w-[150px] h-[17px] mb-[16px]"></div>

                <x-admin::shimmer.reporting.progress-bar/>
            </div>
        </div>

        <!-- Shipping Collected Section -->
        <div class="flex-1 relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
            <!-- Header -->
            <div class="flex items-center justify-between mb-[16px]">
                <div class="shimmer w-[150px] h-[17px]"></div>

                <div class="shimmer w-[79px] h-[21px]"></div>
            </div>
            
            <div class="grid gap-[16px]">
                <x-admin::shimmer.reporting.graph/>

                <div class="shimmer w-[150px] h-[17px] mb-[16px]"></div>

                <x-admin::shimmer.reporting.progress-bar/>
            </div>
        </div>
    </div>

    <!-- Refunds and Top Payment Methods Sections Container -->
    <div class="flex justify-between gap-[15px] flex-1 max-xl:flex-auto">
        <!-- Refunds Section -->
        <div class="flex-1 relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
            <!-- Header -->
            <div class="flex items-center justify-between mb-[16px]">
                <div class="shimmer w-[150px] h-[17px]"></div>

                <div class="shimmer w-[79px] h-[21px]"></div>
            </div>
            
            <x-admin::shimmer.reporting.graph/>
        </div>

        <!-- Top Payment Methods Section -->
        <div class="flex-1 relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
            <!-- Header -->
            <div class="flex items-center justify-between mb-[16px]">
                <div class="shimmer w-[150px] h-[17px]"></div>

                <div class="shimmer w-[79px] h-[21px]"></div>
            </div>
            
            <x-admin::shimmer.reporting.progress-bar/>
        </div>
    </div>
</div>
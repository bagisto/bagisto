<!-- Shipping Collected Shimmer -->
<div class="box-shadow relative flex-1 rounded-sm bg-white p-4 dark:bg-gray-900">
    <!-- Header -->
    <div class="mb-4 flex items-center justify-between">
        <div class="shimmer h-4.25 w-37.5"></div>

        <div class="shimmer h-5.25 w-19.75"></div>
    </div>

    <div class="grid gap-4">
        <div class="flex items-center justify-between gap-4">
            <div class="shimmer h-9 w-30"></div>
            <div class="shimmer h-4.25 w-18.75"></div>
        </div>

        <div class="shimmer h-5 w-30"></div>
    
        <x-admin::shimmer.reporting.graph :count=15 />

        <!-- Date Range -->
        <div class="flex justify-center gap-5">
            <div class="flex items-center gap-1">
                <div class="shimmer h-3.5 w-3.5 rounded-md"></div>
                <div class="shimmer h-4.25 w-35.75"></div>
            </div>
            
            <div class="flex items-center gap-1">
                <div class="shimmer h-3.5 w-3.5 rounded-md"></div>
                <div class="shimmer h-4.25 w-35.75"></div>
            </div>
        </div>

        <div class="shimmer mb-4 h-4.25 w-37.5"></div>

        <x-admin::shimmer.reporting.progress-bar />
    </div>
</div>
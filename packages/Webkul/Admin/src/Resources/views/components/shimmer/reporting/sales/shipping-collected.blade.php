{{-- Shipping Collected Shimmer --}}
<div class="flex-1 relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-[16px]">
        <div class="shimmer w-[150px] h-[17px]"></div>

        <div class="shimmer w-[79px] h-[21px]"></div>
    </div>

    <div class="grid gap-[16px]">
        <div class="flex gap-[16px] items-center  justify-between">
            <div class="shimmer w-[120px] h-[36px]"></div>
            <div class="shimmer w-[75px] h-[17px]"></div>
        </div>

        <div class="shimmer w-[120px] h-[20px]"></div>
    
        <x-admin::shimmer.reporting.graph :count=15/>

        {{-- Date Range --}}
        <div class="flex gap-[20px] justify-center">
            <div class="flex gap-[4px] items-center">
                <div class="shimmer w-[14px] h-[14px] rounded-[3px]"></div>
                <div class="shimmer w-[143px] h-[17px]"></div>
            </div>
            
            <div class="flex gap-[4px] items-center">
                <div class="shimmer w-[14px] h-[14px] rounded-[3px]"></div>
                <div class="shimmer w-[143px] h-[17px]"></div>
            </div>
        </div>

        <div class="shimmer w-[150px] h-[17px] mb-[16px]"></div>

        <x-admin::shimmer.reporting.progress-bar/>
    </div>
</div>
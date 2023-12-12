@props(['isMultiRow' => false])

@if (! $isMultiRow)
    <div class="row grid grid-cols-6 gap-2.5 items-center px-[16px] py-2.5 border-b-[1px] dark:border-gray-800">
        <!-- Mass Actions -->
        <div class="shimmer w-[24px] h-[26px]"></div>

        <div class="shimmer w-[100px] h-[17px]"></div>

        <div class="shimmer w-[100px] h-[17px]"></div>

        <div class="shimmer w-[100px] h-[17px]"></div>

        <div class="shimmer w-[100px] h-[17px]"></div>

        <div class="shimmer w-[100px] h-[17px] place-self-end"></div>
    </div>
@else
    <div class="row grid grid-cols-[2fr_1fr_1fr] gap-2.5 items-center px-[16px] py-2.5 border-b-[1px] dark:border-gray-800 tems-center">
        <!-- Mass Actions -->
        <div class="flex gap-2.5 items-center">
            <div class="shimmer w-[24px] h-[24px]"></div>

            <div class="shimmer w-[200px] h-[17px]"></div>
        </div>

        <div class="shimmer w-[200px] h-[17px]"></div>

        <div class="shimmer w-[200px] h-[17px]"></div>
    </div>
@endif
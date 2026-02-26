@props(['isMultiRow' => false])

@if (! $isMultiRow)
    <div class="row grid grid-cols-6 items-center gap-2.5 border-b px-4 py-2.5 dark:border-gray-800">
        <div class="shimmer h-[26px] w-6"></div>

        <div class="shimmer h-[17px] w-[100px]"></div>

        <div class="shimmer h-[17px] w-[100px]"></div>

        <div class="shimmer h-[17px] w-[100px]"></div>

        <div class="shimmer h-[17px] w-[100px]"></div>

        <div class="shimmer h-[17px] w-[100px] place-self-end"></div>
    </div>
@else
    <div class="row tems-center grid grid-cols-[2fr_1fr_1fr] items-center gap-2.5 border-b px-4 py-2.5 dark:border-gray-800">
        <div class="flex items-center gap-2.5">
            <div class="shimmer h-6 w-6"></div>

            <div class="shimmer h-[17px] w-[200px]"></div>
        </div>

        <div class="shimmer h-[17px] w-[200px]"></div>

        <div class="shimmer h-[17px] w-[200px]"></div>
    </div>
@endif

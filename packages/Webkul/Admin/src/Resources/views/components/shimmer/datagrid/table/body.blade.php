@props(['isMultiRow' => false])

@for ($i = 0;  $i < 10; $i++)
    @if (! $isMultiRow)
        <div class="row grid grid-cols-6 gap-2.5 border-b px-4 py-4 text-gray-600 dark:border-gray-800 dark:text-gray-300">
            <div class="shimmer mb-0.5 h-6 w-6"></div>

            <div class="shimmer h-[17px] w-[100px]"></div>

            <div class="shimmer h-[17px] w-[100px]"></div>

            <div class="shimmer h-[17px] w-[100px]"></div>

            <div class="shimmer h-[17px] w-[100px]"></div>

            <div class="flex gap-2.5 place-self-end">
                <div class="shimmer h-6 w-6 p-1.5"></div>

                <div class="shimmer h-6 w-6 p-1.5"></div>
            </div>
        </div>
    @else
        <div class="row grid grid-cols-[2fr_1fr_1fr] gap-2.5 border-b px-4 py-2.5 text-gray-600 dark:border-gray-800 dark:text-gray-300">
            <div class="flex gap-2.5">
                <div class="shimmer h-6 w-6"></div>

                <div class="flex flex-col gap-1.5">
                    <div class="shimmer h-[19px] w-[250px]"></div>

                    <div class="shimmer h-[17px] w-[150px]"></div>

                    <div class="shimmer h-[17px] w-[150px]"></div>
                </div>
            </div>

            <div class="flex flex-col gap-1.5">
                <div class="shimmer h-[19px] w-[250px]"></div>

                <div class="shimmer h-[17px] w-[150px]"></div>

                <div class="shimmer h-[17px] w-[150px]"></div>
            </div>

            <div class="flex flex-col gap-1.5">
                <div class="shimmer h-[19px] w-[250px]"></div>

                <div class="shimmer h-[17px] w-[150px]"></div>

                <div class="shimmer h-[17px] w-[150px]"></div>
            </div>
        </div>
    @endif
@endfor

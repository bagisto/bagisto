@props(['isMultiRow' => false])

@for ($i = 0;  $i < 10; $i++)
    @if (! $isMultiRow)
        <div class="row grid grid-cols-6 gap-2.5 px-4 py-4 border-b-[1px] border-gray-300 text-gray-600">
            <div class="shimmer w-[24px] h-[24px] mb-[2px]"></div>

            <div class="shimmer w-[100px] h-[24px]"></div>

            <div class="shimmer w-[100px] h-[24px]"></div>

            <div class="shimmer w-[100px] h-[24px]"></div>

            <div class="shimmer w-[100px] h-[24px]"></div>

            <div class="flex gap-2.5 place-self-end">
                <div class="shimmer w-[24px] h-[24px] p-[6px]"></div>
                
                <div class="shimmer w-[24px] h-[24px] p-[6px]"></div>
            </div>
        </div>
    @else
        <div class="row grid grid-cols-[2fr_1fr_1fr] gap-2.5 px-4 py-2.5 border-b-[1px] border-gray-300 text-gray-600">
            <div class="flex gap-2.5">
                <div class="shimmer w-[24px] h-[24px]"></div>

                <div class="flex flex-col gap-1.5">
                    <div class="shimmer w-[250px] h-[24px]"></div>

                    <div class="shimmer w-[150px] h-[24px]"></div>

                    <div class="shimmer w-[150px] h-[24px]"></div>
                </div>
            </div>

            <div class="flex gap-1.5 flex-col">
                <div class="shimmer w-[250px] h-[19px]"></div>

                <div class="shimmer w-[150px] h-[24px]"></div>

                <div class="shimmer w-[150px] h-[24px]"></div>
            </div>

            <div class="flex gap-1.5 flex-col">
                <div class="shimmer w-[250px] h-[19px]"></div>

                <div class="shimmer w-[150px] h-[24px]"></div>

                <div class="shimmer w-[150px] h-[24px]"></div>
            </div>
        </div>
    @endif
@endfor

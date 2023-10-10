@props(['isMultiRow' => false])

@for ($i = 0;  $i < 10; $i++)
    @if (! $isMultiRow)
        <div class="row grid grid-cols-6 gap-[10px] px-[16px] py-[16px] border-b-[1px] dark:border-gray-800 text-gray-600 dark:text-gray-300">
            <div class="shimmer w-[24px] h-[24px] mb-[2px]"></div>

            <div class="shimmer w-[100px] h-[17px]"></div>
            
            <div class="shimmer w-[100px] h-[17px]"></div>
            
            <div class="shimmer w-[100px] h-[17px]"></div>
            
            <div class="shimmer w-[100px] h-[17px]"></div>
            
            <div class="flex gap-[10px] col-start-[none]">
                <div class="shimmer w-[24px] h-[24px] p-[6px]"></div>
                <div class="shimmer w-[24px] h-[24px] p-[6px]"></div>
            </div>
        </div>
    @else
        <div class="row grid grid-cols-[2fr_1fr_1fr] gap-[10px] px-[16px] py-[10px] border-b-[1px] dark:border-gray-800 text-gray-600 dark:text-gray-300">
            <div class="flex gap-[10px]">
                <div class="shimmer w-[24px] h-[24px]"></div>

                <div class="flex flex-col gap-[6px]">
                    <div class="shimmer w-[250px] h-[19px]"></div>
                    
                    <div class="shimmer w-[150px] h-[17px]"></div>
                    
                    <div class="shimmer w-[150px] h-[17px]"></div>
                </div>
            </div>

            <div class="flex gap-[6px] flex-col">
                <div class="shimmer w-[250px] h-[19px]"></div>
                
                <div class="shimmer w-[150px] h-[17px]"></div>
                
                <div class="shimmer w-[150px] h-[17px]"></div>
            </div>

            <div class="flex gap-[6px] flex-col">
                <div class="shimmer w-[250px] h-[19px]"></div>
                
                <div class="shimmer w-[150px] h-[17px]"></div>
                
                <div class="shimmer w-[150px] h-[17px]"></div>
            </div>
        </div>
    @endif
@endfor
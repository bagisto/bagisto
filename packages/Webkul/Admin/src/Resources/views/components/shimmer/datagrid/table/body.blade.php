@for ($i = 0;  $i < 10; $i++)
    <div class="row grid grid-cols-6 gap-[10px] px-[16px] py-[16px] border-b-[1px] border-gray-300 text-gray-600 transition-all">
        <div class="shimmer w-[24px] h-[24px]"></div>

        <div class="shimmer w-[100px] h-[17px]"></div>
        
        <div class="shimmer w-[100px] h-[17px]"></div>
        
        <div class="shimmer w-[100px] h-[17px]"></div>
        
        <div class="shimmer w-[100px] h-[17px]"></div>
        
        <div class="flex gap-[10px] col-start-[none]">
            <div class="shimmer w-[24px] h-[24px] p-[6px]"></div>
            <div class="shimmer w-[24px] h-[24px] p-[6px]"></div>
        </div>
    </div>
@endfor
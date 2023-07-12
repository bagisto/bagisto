@props(['count' => 0])

@for ($i = 0;  $i < $count; $i++)
    <div class="flex gap-[20px] p-[25px] border border-[#e5e5e5] rounded-[12px] max-sm:flex-wrap">
        <div class="min-h-[100px] min-w-[100px] max-sm:hidden">
            <div class="shimmer w-[100px] h-[100px] rounded-[12px]"></div>
        </div>

        <div class="">
            <div class="flex justify-between">
                <p class="shimmer w-[90px] h-[30px]"></p>
                
                <div class="flex items-center gap-[6px]">
                    <span class="shimmer w-[24px] h-[24px]"></span>
                    <span class="shimmer w-[24px] h-[24px]"></span>
                    <span class="shimmer w-[24px] h-[24px]"></span>
                    <span class="shimmer w-[24px] h-[24px]"></span>
                    <span class="shimmer w-[24px] h-[24px]"></span>
                </div>
            </div>

            <p class="shimmer mt-[10px] w-[130px] h-[21px]"></p>

            <div class="grid gap-[6px] mt-[20px] ">
                <p class="shimmer w-[130px] h-[21px]"></p>
                <p class="shimmer w-[130px] h-[21px]"></p>
            </div>

            <div class="flex gap-2 flex-wrap mt-[10px]">
                <span class="shimmer rounded-[12px] w-[48px] h-[48px]"></span>
                <span class="shimmer rounded-[12px] w-[48px] h-[48px]"></span>
                <span class="shimmer rounded-[12px] w-[48px] h-[48px]"></span>
                <span class="shimmer rounded-[12px] w-[48px] h-[48px]"></span>
                <span class="shimmer rounded-[12px] w-[48px] h-[48px]"></span>
                <span class="shimmer rounded-[12px] w-[48px] h-[48px]"></span>
            </div>
        </div>
    </div>
@endfor
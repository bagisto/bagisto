@props(['count' => 0])

@for ($i = 0;  $i < $count; $i++)
    <div class="flex gap-[20px] border border-[#e5e5e5] rounded-[12px] p-[25px] max-sm:flex-wrap">
        <div class="min-h-[100px] min-w-[100px] max-sm:hidden">
            <div class="w-[100px] h-[100px] rounded-[12px] shimmer"></div>
        </div>

        <div class="">
            <div class="flex justify-between">
                <p class="w-[90px] h-[30px] shimmer"></p>
                
                <div class="flex items-center gap-[6px]">
                    <span class="w-[24px] h-[24px] shimmer"></span>
                    <span class="w-[24px] h-[24px] shimmer"></span>
                    <span class="w-[24px] h-[24px] shimmer"></span>
                    <span class="w-[24px] h-[24px] shimmer"></span>
                    <span class="w-[24px] h-[24px] shimmer"></span>
                </div>
            </div>

            <p class="mt-[10px] w-[130px] h-[21px] shimmer"></p>

            <div class="grid gap-[6px] mt-[20px] ">
                <p class="w-[130px] h-[21px] shimmer"></p>
                <p class="w-[130px] h-[21px] shimmer"></p>
            </div>

            <div class="flex gap-2 flex-wrap mt-[10px]">
                <span class="rounded-[12px] w-[48px] h-[48px] shimmer"></span>
                <span class="rounded-[12px] w-[48px] h-[48px] shimmer"></span>
                <span class="rounded-[12px] w-[48px] h-[48px] shimmer"></span>
                <span class="rounded-[12px] w-[48px] h-[48px] shimmer"></span>
                <span class="rounded-[12px] w-[48px] h-[48px] shimmer"></span>
                <span class="rounded-[12px] w-[48px] h-[48px] shimmer"></span>
            </div>
        </div>
    </div>
@endfor
@props(['count' => 0])

@for ($i = 0;  $i < $count; $i++)
    <div class="flex gap-5 p-6 border border-[#e5e5e5] rounded-xl max-sm:flex-wrap">
        <div class="min-h-[100px] min-w-[100px] max-sm:hidden">
            <div class="shimmer w-[100px] h-[100px] rounded-xl"></div>
        </div>

        <div class="">
            <div class="flex justify-between">
                <p class="shimmer w-[90px] h-[30px]"></p>
                
                <div class="flex items-center gap-1.5">
                    <span class="shimmer w-6 h-6"></span>
                    <span class="shimmer w-6 h-6"></span>
                    <span class="shimmer w-6 h-6"></span>
                    <span class="shimmer w-6 h-6"></span>
                    <span class="shimmer w-6 h-6"></span>
                </div>
            </div>

            <p class="shimmer mt-2.5 w-[130px] h-[21px]"></p>

            <div class="grid gap-1.5 mt-5 ">
                <p class="shimmer w-[130px] h-[21px]"></p>
                <p class="shimmer w-[130px] h-[21px]"></p>
            </div>

            <div class="flex gap-2 flex-wrap mt-2.5">
                <span class="shimmer rounded-xl w-12 h-12"></span>
                <span class="shimmer rounded-xl w-12 h-12"></span>
                <span class="shimmer rounded-xl w-12 h-12"></span>
                <span class="shimmer rounded-xl w-12 h-12"></span>
                <span class="shimmer rounded-xl w-12 h-12"></span>
                <span class="shimmer rounded-xl w-12 h-12"></span>
            </div>
        </div>
    </div>
@endfor
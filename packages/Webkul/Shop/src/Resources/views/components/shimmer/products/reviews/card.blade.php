@props(['count' => 0])

@for ($i = 0;  $i < $count; $i++)
    <div class="flex gap-5 rounded-xl border border-[#e5e5e5] p-6 max-sm:flex-wrap">
        <div class="min-h-[100px] min-w-[100px] max-sm:hidden">
            <div class="shimmer h-[100px] w-[100px] rounded-xl"></div>
        </div>

        <div class="">
            <div class="flex justify-between">
                <p class="shimmer h-[30px] w-[90px]"></p>
                
                <div class="flex items-center gap-1.5">
                    <span class="shimmer h-6 w-6"></span>
                    <span class="shimmer h-6 w-6"></span>
                    <span class="shimmer h-6 w-6"></span>
                    <span class="shimmer h-6 w-6"></span>
                    <span class="shimmer h-6 w-6"></span>
                </div>
            </div>

            <p class="shimmer mt-2.5 h-[21px] w-[130px]"></p>

            <div class="mt-5 grid gap-1.5">
                <p class="shimmer h-[21px] w-[130px]"></p>
                <p class="shimmer h-[21px] w-[130px]"></p>
            </div>

            <div class="mt-2.5 flex flex-wrap gap-2">
                <span class="shimmer h-12 w-12 rounded-xl"></span>
                <span class="shimmer h-12 w-12 rounded-xl"></span>
                <span class="shimmer h-12 w-12 rounded-xl"></span>
                <span class="shimmer h-12 w-12 rounded-xl"></span>
                <span class="shimmer h-12 w-12 rounded-xl"></span>
                <span class="shimmer h-12 w-12 rounded-xl"></span>
            </div>
        </div>
    </div>
@endfor
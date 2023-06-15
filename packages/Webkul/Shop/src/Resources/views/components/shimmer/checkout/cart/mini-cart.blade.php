@props(['count' => 0])

@for ($i = 0;  $i < $count; $i++)
    <div class="grid gap-[50px] mt-[35px]">
        <div class="flex gap-x-[20px]">
            <div class="">
                <div class="relative overflow-hidden rounded-[12px] w-[110px] h-[110px] bg-[#E9E9E9] shimmer">
                    <img 
                        class="rounded-sm bg-[#F5F5F5]" 
                        src=""
                    >
                </div>
            </div>

            <div class="grid gap-y-[10px] flex-1">
                <div class="flex flex-wrap justify-between">
                    <p class="w-[140px] h-[45px] shimmer bg-[#E9E9E9]"></p>
                    <p class="w-[110px] h-[21px] shimmer bg-[#E9E9E9]"></p>
                </div>

                <div class="flex gap-[20px] items-center flex-wrap">
                    <div class="gap-[20px] flex-wrap">
                        <div class="w-[90px] h-[36px] rounded-[54px] shimmer bg-[#E9E9E9]"></div>
                    </div>

                    <div class="w-[100px] h-[24px] shimmer bg-[#E9E9E9]"></div>
                </div>
            </div>
        </div>
    </div>
@endfor
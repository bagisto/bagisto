@props(['count' => 0])

@for ($i = 0;  $i < $count; $i++)
    <div class="flex gap-[45px] p-[25px] items-center border-b-[1px] border-[#E9E9E9]">
        <div class="flex gap-x-[20px]">
            <div class="relative overflow-hidden rounded-[12px] w-[110px] h-[110px] bg-[#E9E9E9] shimmer">
                <img class="">
            </div>

            <div class="grid gap-y-[10px]">
                <p class="w-[72px] h-[24px] bg-[#E9E9E9] shimmer "></p>

                <a class="w-[72px] h-[24px] bg-[#E9E9E9] shimmer"></a>

                <p class="w-[72px] h-[24px] bg-[#E9E9E9] shimmer"></p>
            </div>
        </div>
        <p class="w-[30%] h-[27px] bg-[#E9E9E9] shimmer"></p>
        
        <div class="flex gap-x-[25px] rounded-[54px] py-[10px] px-[20px] items-center w-[55%] h-[46px] bg-[#E9E9E9] shimmer">
        </div>

        <div
            class="flex gap-x-[25px] rounded-[54px] py-[10px] px-[20px] items-center w-[80%] h-[46px] bg-[#E9E9E9] shimmer"
        >
        </div>
    </div>
@endfor

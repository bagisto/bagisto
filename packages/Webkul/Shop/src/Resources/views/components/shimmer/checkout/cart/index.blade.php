@props(['count' => 0])

<div class="grid grid-cols-[1fr_auto] gap-[30px] mt-[30px]">
    <div class="grid gap-y-[25px]">
        <div class="grid gap-x-[10px] grid-cols-[380px_auto_auto_auto] border-b-[1px] border-[#E9E9E9] pb-[18px]">
            <div class="w-[200px] h-[21px] shimmer bg-[#E9E9E9]"></div>
            <div class="w-[100px] h-[21px] shimmer bg-[#E9E9E9]"></div>
            <div class="w-[100px] h-[21px] shimmer bg-[#E9E9E9]"></div>
            <div class="w-[100px] h-[21px] shimmer bg-[#E9E9E9]"></div>
        </div>

        @for ($i = 0;  $i < $count; $i++)
            <div class="grid gap-x-[10px] grid-cols-[380px_auto_auto_auto] border-b-[1px] border-[#E9E9E9] pb-[18px]">
                <div class="flex gap-x-[20px]">
                    <div class="">
                        <div class="relative overflow-hidden rounded-[12px] w-[110px] h-[110px] bg-[#E9E9E9] shimmer">
                            <img 
                                class="rounded-sm bg-[#F5F5F5]" 
                                src=""
                            >
                        </div>
                    </div>

                    <div class="grid gap-y-[10px]">
                        <div class="w-[200px] h-[21px] shimmer bg-[#E9E9E9]"></div>
                        <div class="w-[100px] h-[21px] shimmer bg-[#E9E9E9]"></div>
                    </div>
                </div>

                <div class="w-[100px] h-[21px] shimmer bg-[#E9E9E9]"></div>

                <div class="flex gap-[20px] flex-wrap">
                    <div class="w-[110px] h-[36px] rounded-[54px] shimmer bg-[#E9E9E9]"></div>
                </div>

                <div class="w-[100px] h-[21px] shimmer bg-[#E9E9E9]"></div>
            </div>
        @endfor

        <div class="flex flex-wrap gap-[30px] justify-end">
            <div class="rounded-[18px]  w-[217px] h-[56px] shimmer bg-[#E9E9E9]"></div>
            <div class="rounded-[18px]  w-[161px] h-[56px] shimmer bg-[#E9E9E9]"></div>
        </div>
    </div>

    <div class="w-[418px] max-w-full">
        <p class="w-[40%] h-[39px] shimmer bg-[#E9E9E9]"></p>

        <div class="grid gap-[15px] mt-[25px]">
            <div class="flex text-right justify-between">
                <p class="w-[30%] h-[24px] shimmer bg-[#E9E9E9]"></p>
                <p class="w-[30%] h-[24px] shimmer bg-[#E9E9E9]"></p>
            </div>

            <div class="flex text-right justify-between">
                <p class="w-[40%] h-[24px] shimmer bg-[#E9E9E9]"></p>
                <p class="w-[36%] h-[24px] shimmer bg-[#E9E9E9]"></p>
            </div>

            <div class="flex text-right justify-between">
                <p class="w-[30%] h-[24px] shimmer bg-[#E9E9E9]"></p>
                <p class="w-[31%] h-[24px] shimmer bg-[#E9E9E9]"></p>
            </div>

            <div class="flex text-right justify-between">
                <p class="w-[33%] h-[24px] shimmer bg-[#E9E9E9]"></p>
                <p class="w-[38%] h-[24px] shimmer bg-[#E9E9E9]"></p>
            </div>

            <div class="block place-self-end mt-[15px] rounded-[18px]  w-[60%] h-[46px] shimmer bg-[#E9E9E9]"></div>
        </div>
    </div>
</div>
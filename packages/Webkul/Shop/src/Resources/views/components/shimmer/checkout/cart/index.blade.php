@props(['count' => 0])

<div class="flex flex-wrap gap-[75px] mt-[30px] max-1060:flex-col">
    <div class="grid gap-y-[25px] flex-1">
        <!-- Cart Action -->
        <div class="flex justify-between items-center pb-[10px] border-b-[1px] border-[#E9E9E9] max-sm:block">
            <div class="flex select-none items-center">
                <div class="shimmer w-[24px] h-[25px] rounded-[4px]"></div>

                <div class="shimmer ml-[10px] w-[165px] h-[30px]"></div>
            </div>

            <div class="shimmer w-[222px] h-[23px] max-sm:ml-[35px] max-sm:mt-[10px]"></div>
        </div>

        <!-- Cart Items -->
        @for ($i = 0; $i < $count; $i++)
            <div class="flex gap-x-[10px] justify-between pb-[18px] border-b-[1px] border-[#E9E9E9]">
                <div class="flex gap-x-[20px]">
                    <div class="select-none mt-[43px]">
                        <div class="shimmer w-[24px] h-[25px] rounded-[4px]"></div>
                    </div>

                    <div>
                        <div class="shimmer w-[110px] h-[110px] rounded-[12px]"></div>
                    </div>

                    <div class="grid gap-y-[10px]">
                        <div class="shimmer w-[200px] h-[24px]"></div>

                        <div class="shimmer w-[100px] h-[24px]"></div>

                        <div class="hidden gap-[10px] place-content-start max-sm:grid">
                            <div class="shimmer w-[100px] h-[27px]"></div>

                            <div class="shimmer w-[100px] h-[23px]"></div>
                        </div>

                        <div class="shimmer w-[108px] h-[36px] rounded-[54px]"></div>
                    </div>
                </div>

                <div class="grid gap-[10px] place-content-start max-sm:hidden">
                    <div class="shimmer w-[100px] h-[21px]"></div>

                    <div class="shimmer w-[100px] h-[21px]"></div>
                </div>
            </div>
        @endfor

        <div class="flex flex-wrap gap-[30px] justify-end">
            <div class="shimmer w-[217px] h-[56px] rounded-[18px]"></div>

            <div class="shimmer w-[161px] h-[56px] rounded-[18px]"></div>
        </div>
    </div>

    <div class="w-[418px] max-w-full">

        <p class="shimmer w-[40%] h-[39px]"></p>

        <div class="grid gap-[15px] mt-[25px]">
            <div class="flex justify-between text-right">
                <p class="shimmer w-[30%] h-[24px]"></p>

                <p class="shimmer w-[30%] h-[24px]"></p>
            </div>

            <div class="flex justify-between text-right">
                <p class="shimmer w-[40%] h-[24px]"></p>

                <p class="shimmer w-[36%] h-[24px]"></p>
            </div>

            <div class="flex justify-between text-right">
                <p class="shimmer w-[30%] h-[24px]"></p>

                <p class="shimmer w-[31%] h-[24px]"></p>
            </div>

            <div class="flex justify-between text-right">
                <p class="shimmer w-[33%] h-[24px]"></p>
                
                <p class="shimmer w-[38%] h-[24px]"></p>
            </div>
            <div class="shimmer block place-self-end w-[60%] h-[46px] mt-[15px] rounded-[18px]"></div>
        </div>
    </div>
</div>
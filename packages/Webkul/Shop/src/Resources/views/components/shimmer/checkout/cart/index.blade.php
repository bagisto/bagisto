@props(['count' => 0])

<div class="flex flex-wrap gap-[75px] mt-[30px] max-1060:flex-col">
    <div class="grid gap-y-[25px] flex-1">
        <!-- Cart Action -->
        <div class="max-lg:hidden flex justify-between items-center border-b-[1px] border-[#E9E9E9] pb-[10px]">
            <div class="flex">
                <div class="w-[24px] h-[25px] rounded-[4px] mt-[5px] shimmer"></div>

                <div class="ml-[10px] w-[215px] h-[36px] shimmer"></div>
            </div>

            <div class="w-[222px] h-[23px] shimmer"></div>
        </div>

        <!-- Cart Items -->
        @for ($i = 0;  $i < $count; $i++)
            <div class="flex justify-between gap-x-[10px] border-b-[1px] border-[#E9E9E9] pb-[18px]">
                <div class="flex gap-x-[20px]">
                    <div class="select-none mt-[43px]">
                        <div class="w-[24px] h-[25px] rounded-[4px] mt-[5px] shimmer"></div>
                    </div>

                    <div>
                        <div class="w-[110px] h-[110px] shimmer bg-[#E9E9E9] rounded-[12px]"></div>
                    </div>

                    <div class="grid gap-y-[10px]">
                        <div class="w-[200px] h-[21px] shimmer bg-[#E9E9E9]"></div>

                        <div class="flex gap-x-[10px] gap-y-[6px] flex-wrap">
                            <div class="grid gap-[8px]">
                                <div class="grid gap-1">
                                    <div class="w-[160px] h-[21px] shimmer bg-[#E9E9E9]"></div>
                        
                                    <div class="w-[160px] h-[21px] shimmer bg-[#E9E9E9]"></div>
                                </div>
                            </div>
                        </div>

                        <div class="w-[100px] h-[21px] shimmer bg-[#E9E9E9]"></div>
                    
                        <div class="w-[110px] h-[36px] rounded-[54px] shimmer bg-[#E9E9E9]"></div>
                    
                        <div class="hidden gap-[10px] place-content-start max-sm:grid">
                            <div class="w-[100px] h-[21px] shimmer bg-[#E9E9E9]"></div>
                            
                            <div class="w-[100px] h-[21px] shimmer bg-[#E9E9E9]"></div>
                        </div>
                    </div>
                </div>
                <div class="grid gap-[20px] place-content-start max-sm:hidden">
                    <div class="w-[100px] h-[21px] shimmer bg-[#E9E9E9]"></div>

                    <div class="w-[100px] h-[21px] shimmer bg-[#E9E9E9]"></div>
                </div>
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
<div class="border-b dark:border-gray-800">
    <div class="flex items-center justify-between p-[16px]">
        <div class="shimmer w-[157px] h-[17px]"></div>

        <div class="shimmer w-[83px] h-[17px]"></div>
    </div>

    <div class="flex flex-col">
        @for ($i = 1; $i <= 3; $i++)
            <div class="flex gap-[32px] p-[16px] border-b-[1px] dark:border-gray-800 last:border-b-0">
                <div class="flex justify-between gap-[6px] w-full h-[38px]">
                    <div class="flex flex-col gap-y-1">
                        {{-- Customer Name --}}
                        <div class="shimmer w-[137px] h-[19px]"></div>

                        {{-- Customer Email --}}
                        <div class="shimmer w-[137px] h-[19px]"></div>
                    </div>

                    <div class="flex flex-col gap-y-1">
                        {{-- Grand Total --}}
                        <div class="shimmer w-[72px] h-[19px]"></div>

                        {{-- TOtal Orders count --}}
                        <div class="shimmer w-[72px] h-[19px]"></div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>
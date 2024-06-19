@props(['count' => 0])

<div class="mt-8 flex flex-wrap gap-20 max-1060:flex-col max-sm:mt-0 max-sm:gap-[30px]">
    <div class="grid flex-1 gap-y-6">
        <!-- Cart Action -->
        <div class="flex items-center justify-between border-b border-zinc-200 pb-2.5 max-sm:justify-normal max-sm:gap-5 max-sm:py-2.5">
            <div class="flex select-none items-center">
                <div class="shimmer h-[25px] w-6 rounded max-sm:h-5"></div>

                <div class="shimmer h-[30px] w-[165px] max-sm:h-5 max-sm:w-[100px] ltr:ml-2.5 rtl:mr-2.5"></div>
            </div>

            <div class="shimmer h-[23px] w-56 max-sm:h-5 max-sm:w-44"></div>
        </div>

        <!-- Cart Items -->
        @for ($i = 0; $i < $count; $i++)
            <div class="flex justify-between gap-x-2.5 border-b border-zinc-200 pb-5">
                <div class="flex gap-x-5 max-sm:gap-x-3.5">
                    <div class="mt-11 select-none max-sm:mt-0 max-sm:grid max-sm:h-20 max-sm:items-center max-sm:leading-[80px]">
                        <div class="shimmer h-[25px] w-6 rounded"></div>
                    </div>

                    <div>
                        <div class="shimmer h-28 w-28 rounded-xl max-sm:h-20 max-sm:max-w-20"></div>
                    </div>

                    <div class="grid gap-y-2.5">
                        <div class="shimmer h-6 w-[200px]"></div>

                        <div class="shimmer h-6 w-[100px]"></div>

                        <div class="hidden place-content-start gap-2.5 max-sm:grid">
                            <div class="shimmer h-[27px] w-[100px] max-sm:h-4"></div>

                            <div class="shimmer h-[23px] w-[100px] max-sm:hidden"></div>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <div class="shimmer h-9 w-[108px] rounded-[54px] max-sm:h-[30px] max-sm:w-20"></div>

                            <div class="shimmer hidden h-6 w-12 max-sm:block"></div>
                        </div>
                    </div>
                </div>

                <div class="grid place-content-start gap-2.5 max-sm:hidden">
                    <div class="shimmer h-[21px] w-[100px]"></div>

                    <div class="shimmer h-[21px] w-[100px]"></div>
                </div>
            </div>
        @endfor

        <!-- Continue And Update Button -->
        <div class="flex flex-wrap justify-end gap-8 max-sm:justify-between max-sm:gap-5">
            <div class="shimmer h-14 w-[217px] rounded-2xl max-sm:h-[46px]"></div>

            <div class="shimmer h-14 w-[161px] rounded-2xl max-sm:h-[46px]"></div>
        </div>
    </div>

    <div class="w-[418px] max-w-full">

        <div class="shimmer h-9 w-2/5 max-sm:w-3/6"></div>

        @if (core()->getConfigData('sales.checkout.shopping_cart.estimate_shipping'))
            <div class="shimmer mt-5 grid h-14 w-full gap-4 rounded-xl max-sm:gap-2.5"></div>
        @endif

        <div class="mt-6 grid gap-4">
            <div class="flex justify-between text-right">
                <p class="shimmer h-6 w-[30%]"></p>

                <p class="shimmer h-6 w-[30%]"></p>
            </div>

            <div class="flex justify-between text-right">
                <p class="shimmer h-6 w-2/5"></p>

                <p class="shimmer h-6 w-[36%]"></p>
            </div>

            <div class="flex justify-between text-right">
                <p class="shimmer h-6 w-[30%]"></p>

                <p class="shimmer h-6 w-[31%]"></p>
            </div>

            <div class="flex justify-between text-right">
                <p class="shimmer h-6 w-[33%]"></p>
                
                <p class="shimmer h-6 w-[38%]"></p>
            </div>
            <div class="shimmer mt-4 block h-[46px] w-3/5 place-self-end rounded-2xl"></div>
        </div>
    </div>
</div>
@props(['count' => 0])

<div class="mt-8 flex flex-wrap gap-20 max-1060:flex-col">
    <div class="grid flex-1 gap-y-6">
        <!-- Cart Action -->
        <div class="flex items-center justify-between border-b border-[#E9E9E9] pb-2.5 max-sm:block">
            <div class="flex select-none items-center">
                <div class="shimmer h-[25px] w-6 rounded"></div>

                <div class="shimmer h-[30px] w-[165px] ltr:ml-2.5 rtl:mr-2.5"></div>
            </div>

            <div class="shimmer h-[23px] w-[222px] max-sm:mt-2.5 max-sm:ltr:ml-9 max-sm:rtl:mr-9"></div>
        </div>

        <!-- Cart Items -->
        @for ($i = 0; $i < $count; $i++)
            <div class="flex justify-between gap-x-2.5 border-b border-[#E9E9E9] pb-5">
                <div class="flex gap-x-5">
                    <div class="mt-11 select-none">
                        <div class="shimmer h-[25px] w-6 rounded"></div>
                    </div>

                    <div>
                        <div class="shimmer h-[110px] w-[110px] rounded-xl"></div>
                    </div>

                    <div class="grid gap-y-2.5">
                        <div class="shimmer h-6 w-[200px]"></div>

                        <div class="shimmer h-6 w-[100px]"></div>

                        <div class="hidden place-content-start gap-2.5 max-sm:grid">
                            <div class="shimmer h-[27px] w-[100px]"></div>

                            <div class="shimmer h-[23px] w-[100px]"></div>
                        </div>

                        <div class="shimmer h-9 w-[108px] rounded-[54px]"></div>
                    </div>
                </div>

                <div class="grid place-content-start gap-2.5 max-sm:hidden">
                    <div class="shimmer h-[21px] w-[100px]"></div>

                    <div class="shimmer h-[21px] w-[100px]"></div>
                </div>
            </div>
        @endfor

        <div class="flex flex-wrap justify-end gap-8">
            <div class="shimmer h-14 w-[217px] rounded-2xl"></div>

            <div class="shimmer h-14 w-[161px] rounded-2xl"></div>
        </div>
    </div>

    <div class="w-[418px] max-w-full">

        <p class="shimmer h-[39px] w-2/5"></p>

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
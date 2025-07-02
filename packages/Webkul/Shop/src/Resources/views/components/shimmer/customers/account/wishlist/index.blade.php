@props(['count' => 0])

<div class="journal-scroll flex items-center justify-between overflow-auto">
    <h2 class="shimmer h-[39px] w-[110px]"></h2>

    <div class="shimmer flex items-center gap-x-2.5 rounded-xl px-5 py-3 max-md:px-1 max-md:py-2 max-sm:py-1.5">
        <span class="h-6 w-[108px]"></span>
    </div>
</div>

<div class="journal-scroll overflow-auto">
    @for ($i = 0;  $i < $count; $i++)
        <div class="mt-8 flex flex-wrap gap-20 max-1060:flex-col max-md:my-5 max-md:last:mb-0">
            <div class="grid flex-1 gap-y-6">
                <!-- Single card -->
                <div class="flex justify-between gap-x-2.5 border-b border-zinc-200 pb-5">
                    <div class="flex gap-x-5 max-md:w-full">
                        <div class="">
                            <div class="shimmer h-[110px] w-[110px] rounded-xl max-md:h-20 max-md:w-20"></div>
                        </div>

                        <div class="grid w-full gap-y-2.5 max-md:gap-y-0">
                            <div class="flex justify-between">
                                <div class="shimmer h-6 w-[200px] max-md:h-5"></div>

                                <div class="shimmer hidden h-6 w-6 max-md:block"></div>
                            </div>


                            <div class="shimmer h-[21px] w-[100px] max-md:hidden"></div>

                            <!-- For Mobile View -->
                            <div class="hidden place-content-start justify-between gap-2.5 max-md:flex">
                                <div class="shimmer h-6 w-24"></div>

                                <div class="shimmer h-6 w-20 max-md:hidden"></div>
                            </div>

                            <div class="flex flex-wrap gap-5 max-md:mt-2.5 max-md:flex-nowrap">
                                <div class="shimmer h-10 w-[110px] rounded-[54px] max-md:h-[34px] max-md:w-20"></div>

                                <div class="shimmer h-10 w-[158px] rounded-2xl max-md:h-[34px] max-md:w-28"></div>
                            </div>
                        </div>
                    </div>

                    <div class="grid place-content-start gap-2.5 max-md:hidden">
                        <div class="shimmer h-[27px] w-[100px]"></div>
                        
                        <div class="shimmer h-6 w-[100px]"></div>
                    </div>
                </div>
            </div>
        </div>
    @endfor
</div>


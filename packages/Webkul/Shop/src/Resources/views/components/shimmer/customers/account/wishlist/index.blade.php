@props(['count' => 0])

<div class="journal-scroll flex items-center justify-between overflow-auto">
    <h2 class="shimmer h-[39px] w-[110px]"></h2>

    <div class="shimmer flex items-center gap-x-2.5 rounded-xl px-5 py-3">
        <span class="h-6 w-[108px]"></span>
    </div>
</div>

<div class="journal-scroll overflow-auto">
    @for ($i = 0;  $i < $count; $i++)
        <div class="mt-8 flex flex-wrap gap-20 max-1060:flex-col max-sm:my-5 max-sm:last:mb-0">
            <div class="grid flex-1 gap-y-6">
                <!-- Single card -->
                <div class="flex justify-between gap-x-2.5 border-b border-zinc-200 pb-5">
                    <div class="flex gap-x-5 max-sm:w-full">
                        <div class="">
                            <div class="shimmer h-[110px] w-[110px] rounded-xl max-sm:h-20 max-sm:w-20"></div>
                        </div>

                        <div class="grid w-full gap-y-2.5">
                            <div class="flex justify-between">
                                <div class="shimmer h-6 w-[200px] max-sm:h-5"></div>

                                <div class="shimmer hidden h-6 w-6 max-sm:block"></div>
                            </div>


                            <div class="shimmer h-[21px] w-[100px] max-sm:hidden"></div>

                            <!-- For Mobile View -->
                            <div class="hidden place-content-start justify-between gap-2.5 max-sm:flex">
                                <div class="shimmer h-6 w-24"></div>

                                <div class="shimmer h-6 w-20 max-sm:hidden"></div>
                            </div>

                            <div class="flex flex-wrap gap-5 max-sm:flex-nowrap">
                                <div class="shimmer h-10 w-[110px] rounded-[54px] max-sm:h-[34px] max-sm:w-[80px]"></div>

                                <div class="shimmer h-10 w-[158px] rounded-2xl max-sm:h-[34px] max-sm:w-28"></div>
                            </div>
                        </div>
                    </div>

                    <div class="grid place-content-start gap-2.5 max-sm:hidden">
                        <div class="shimmer h-[27px] w-[100px]"></div>
                        
                        <div class="shimmer h-6 w-[100px]"></div>
                    </div>
                </div>
            </div>
        </div>
    @endfor
</div>


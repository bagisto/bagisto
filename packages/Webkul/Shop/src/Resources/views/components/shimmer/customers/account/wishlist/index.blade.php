@props(['count' => 0])

<div class="flex justify-between items-center overflow-auto journal-scroll">
    <h2 class="shimmer w-[110px] h-[39px]"></h2>

    <div class="shimmer flex items-center gap-x-2.5 py-3 px-5 rounded-xl">
        <span class="w-[108px] h-6"></span>
    </div>
</div>

<div class="overflow-auto journal-scroll">
    @for ($i = 0;  $i < $count; $i++)
        <div class="flex flex-wrap gap-20 mt-8 max-1060:flex-col">
            <div class="grid gap-y-6 flex-1">
                <!-- Single card -->
                <div class="flex justify-between gap-x-2.5 pb-5 border-b border-[#E9E9E9]">
                    <div class="flex gap-x-5">
                        <div class="">
                            <div class="shimmer w-[110px] h-[110px] rounded-xl"></div>
                        </div>

                        <div class="grid gap-y-2.5">
                            <div class="shimmer w-[200px] h-[21px]"></div>
                            <div class="flex gap-x-2.5 gap-y-1.5 flex-wrap">
                                <div class="grid gap-2">
                                </div>
                            </div>

                            <div class="shimmer w-[100px] h-[21px]"></div>
                            
                            <div class="hidden gap-2.5 place-content-start max-sm:grid">
                                <div class="shimmer w-[100px] h-[27px]"></div>

                                <div class="shimmer w-[100px] h-6"></div>
                            </div>

                            <div class="flex gap-5 flex-wrap">
                                <div class="shimmer w-[110px] h-10 rounded-[54px]"></div>
                                <div class="shimmer w-[158px] h-10 rounded-2xl"></div>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-2.5 place-content-start max-sm:hidden">
                        <div class="shimmer w-[100px] h-[27px]"></div>
                        
                        <div class="shimmer w-[100px] h-6"></div>
                    </div>
                </div>
            </div>
        </div>
    @endfor
</div>


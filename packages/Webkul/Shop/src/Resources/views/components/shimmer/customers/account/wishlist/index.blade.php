@props(['count' => 0])

<div class="journal-scroll flex items-center justify-between overflow-auto">
    <h2 class="shimmer h-[39px] w-[110px]"></h2>

    <div class="shimmer flex items-center gap-x-2.5 rounded-xl px-5 py-3">
        <span class="h-6 w-[108px]"></span>
    </div>
</div>

<div class="journal-scroll overflow-auto">
    @for ($i = 0;  $i < $count; $i++)
        <div class="mt-8 flex flex-wrap gap-20 max-1060:flex-col">
            <div class="grid flex-1 gap-y-6">
                <!-- Single card -->
                <div class="flex justify-between gap-x-2.5 border-b border-[#E9E9E9] pb-5">
                    <div class="flex gap-x-5">
                        <div class="">
                            <div class="shimmer h-[110px] w-[110px] rounded-xl"></div>
                        </div>

                        <div class="grid gap-y-2.5">
                            <div class="shimmer h-[21px] w-[200px]"></div>
                            <div class="flex flex-wrap gap-x-2.5 gap-y-1.5">
                                <div class="grid gap-2">
                                </div>
                            </div>

                            <div class="shimmer h-[21px] w-[100px]"></div>
                            
                            <div class="hidden place-content-start gap-2.5 max-sm:grid">
                                <div class="shimmer h-[27px] w-[100px]"></div>

                                <div class="shimmer h-6 w-[100px]"></div>
                            </div>

                            <div class="flex flex-wrap gap-5">
                                <div class="shimmer h-10 w-[110px] rounded-[54px]"></div>
                                <div class="shimmer h-10 w-[158px] rounded-2xl"></div>
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


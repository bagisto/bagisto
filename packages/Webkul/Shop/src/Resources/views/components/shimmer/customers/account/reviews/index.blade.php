@props(['count' => 0])

<div class="journal-scroll flex items-center justify-between overflow-auto max-md:hidden">
    <div class="shimmer h-8 w-24"></div>
</div>

<div class="mt-14 grid gap-5 max-1060:grid-cols-[1fr] max-md:mt-5">
    @for ($i = 0;  $i < $count; $i++)
        <!-- Single card -->
        <div class="rounded-xl border p-6 max-md:p-4">
            <div class="flex gap-5">
                <x-shop::media.images.lazy
                    class="h-[146px] max-h-[146px] w-32 min-w-32 max-w-32 rounded-xl max-md:h-20 max-md:w-20 max-md:min-w-20"
                    alt="Review Image"
                />
    
                <div class="w-full">
                    <div class="flex justify-between">
                        <p class="shimmer h-6 w-28 max-md:h-5 max-md:w-24"></p>
    
                        <div class="flex items-center gap-0.5 max-md:hidden">
                            <span class="shimmer h-3.5 w-3.5"></span>
                            <span class="shimmer h-3.5 w-3.5"></span>
                            <span class="shimmer h-3.5 w-3.5"></span>
                            <span class="shimmer h-3.5 w-3.5"></span>
                            <span class="shimmer h-3.5 w-3.5"></span>
                        </div>
                    </div>
    
                    
                    <p class="shimmer mt-2.5 h-5 w-28 max-md:mt-1 max-md:h-4 max-md:w-32"></p>
    
                    <!-- For Mobile screen -->
                    <div class="mt-1.5 flex items-center gap-0.5 md:hidden">
                        <span class="shimmer h-6 w-6"></span>
                        <span class="shimmer h-6 w-6"></span>
                        <span class="shimmer h-6 w-6"></span>
                        <span class="shimmer h-6 w-6"></span>
                        <span class="shimmer h-6 w-6"></span>
                    </div>
    
                    <p class="shimmer mt-5 h-5 w-full max-md:hidden"></p>

                    <p class="shimmer mt-2.5 h-5 w-full max-md:hidden"></p>
                </div>
            </div>

            <!-- For Mobile screen -->
            <p class="shimmer mt-2.5 h-5 w-full md:hidden"></p>

            <p class="shimmer mt-2.5 h-5 w-full md:hidden"></p>
        </div>
    @endfor
</div>
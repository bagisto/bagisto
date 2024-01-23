@props(['count' => 0])

<div class="flex justify-between items-center overflow-auto journal-scroll">
    <h2 class="shimmer w-[110px] h-[32px]"></h2>
</div>

<div class="grid gap-5 mt-14 max-1060:grid-cols-[1fr]">
    @for ($i = 0;  $i < $count; $i++)
        <!-- Single card -->
        <div class="flex gap-5 p-6 border rounded-xl max-sm:flex-wrap">
            <x-shop::media.images.lazy
                class="max-w-[128px] max-h-[146px] min-w-[128px] w-[128px] h-[146px] rounded-xl" 
                alt="Review Image"                   
            />

            <div class="w-full">
                <div class="flex justify-between">
                    <p class="shimmer h-[30px] w-[110px]"></p>

                    <div class="flex gap-0.5 items-center">
                        <span class="shimmer h-6 w-6"></span>
                        <span class="shimmer h-6 w-6"></span>
                        <span class="shimmer h-6 w-6"></span>
                        <span class="shimmer h-6 w-6"></span>
                        <span class="shimmer h-6 w-6"></span>
                    </div>
                </div>

                <p class="shimmer h-5 w-[110px] mt-2.5"></p>

                <p class="shimmer h-5 w-full mt-5"></p>

                <p class="shimmer h-5 w-full mt-2.5"></p>
            </div>
        </div>
    @endfor
</div>
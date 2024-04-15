@props(['count' => 0])

<div class="journal-scroll flex items-center justify-between overflow-auto">
    <h2 class="shimmer h-[32px] w-[110px]"></h2>
</div>

<div class="mt-14 grid gap-5 max-1060:grid-cols-[1fr]">
    @for ($i = 0;  $i < $count; $i++)
        <!-- Single card -->
        <div class="flex gap-5 rounded-xl border p-6 max-sm:flex-wrap">
            <x-shop::media.images.lazy
                class="h-[146px] max-h-[146px] w-[128px] min-w-[128px] max-w-[128px] rounded-xl" 
                alt="Review Image"                   
            />

            <div class="w-full">
                <div class="flex justify-between">
                    <p class="shimmer h-[30px] w-[110px]"></p>

                    <div class="flex items-center gap-0.5">
                        <span class="shimmer h-6 w-6"></span>
                        <span class="shimmer h-6 w-6"></span>
                        <span class="shimmer h-6 w-6"></span>
                        <span class="shimmer h-6 w-6"></span>
                        <span class="shimmer h-6 w-6"></span>
                    </div>
                </div>

                <p class="shimmer mt-2.5 h-5 w-[110px]"></p>

                <p class="shimmer mt-5 h-5 w-full"></p>

                <p class="shimmer mt-2.5 h-5 w-full"></p>
            </div>
        </div>
    @endfor
</div>
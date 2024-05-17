@props(['count' => 0])

<div class="journal-scroll flex items-center justify-between overflow-auto max-sm:hidden">
    <div class="shimmer h-8 w-24"></div>
</div>

<div class="mt-14 grid gap-5 max-1060:grid-cols-[1fr] max-sm:mt-[20px]">
    @for ($i = 0;  $i < $count; $i++)
        <!-- Single card -->
        <div class="flex gap-5 rounded-xl border p-6 max-sm:p-4">
            <x-shop::media.images.lazy
                class="h-[146px] max-h-[146px] w-32 min-w-32 max-w-32 rounded-xl max-sm:h-[80px] max-sm:w-[80px] max-sm:min-w-[80px]"
                alt="Review Image"
            />

            <div class="w-full">
                <div class="flex justify-between">
                    <p class="shimmer h-6 w-28 max-sm:w-24"></p>

                    <div class="flex items-center gap-0.5">
                        <span class="shimmer h-[14px] w-[14px]"></span>
                        <span class="shimmer h-[14px] w-[14px]"></span>
                        <span class="shimmer h-[14px] w-[14px]"></span>
                        <span class="shimmer h-[14px] w-[14px]"></span>
                        <span class="shimmer h-[14px] w-[14px]"></span>
                    </div>
                </div>

                <p class="shimmer mt-2.5 h-5 w-28 max-sm:w-32"></p>

                <p class="shimmer mt-5 h-5 w-full"></p>

                <p class="shimmer mt-2.5 h-5 w-full"></p>
            </div>
        </div>
    @endfor
</div>
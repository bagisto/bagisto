@props(['count' => 0])

<div class="container mt-14 max-lg:px-8 max-md:mt-7 max-md:px-0! max-sm:mt-5">
    <div class="relative">
        <div class="scrollbar-hide flex gap-10 overflow-auto max-lg:gap-4">
            @for ($i = 0;  $i < $count; $i++)
                <div class="grid min-w-30 grid-cols-1 justify-items-center gap-4 max-md:min-w-20 max-md:gap-2.5 max-md:first:ml-4 max-sm:min-w-15 max-sm:max-w-15 max-sm:gap-1.5">
                    <div class="shimmer relative h-27.5 w-27.5 overflow-hidden rounded-full max-md:h-20 max-md:w-20 max-sm:h-15 max-sm:w-15">
                        <img class="rounded-xs bg-zinc-100">
                    </div>

                    <p class="shimmer h-6.75 w-22.5 rounded-2xl max-sm:h-5 max-sm:w-17.5"></p>
                </div>
            @endfor
        </div>

        <span
            class="shimmer absolute -left-10 top-9 flex h-12.5 w-12.5 rounded-full max-sm:hidden"
            role="presentation"
        ></span>

        <span
            class="shimmer absolute -right-6 top-9 flex h-12.5 w-12.5 rounded-full max-sm:hidden"
            role="presentation"
        ></span>
    </div>
</div>

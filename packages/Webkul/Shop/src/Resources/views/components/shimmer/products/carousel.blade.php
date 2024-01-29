<div class="container mt-20 max-lg:px-8 max-sm:mt-8">
    <div class="flex justify-between">
        <h3 class="shimmer w-[200px] h-[45px]"></h3>

        <div class="flex gap-8 justify-between items-center">
            <span
                class="shimmer inline-block w-6 h-6"
                role="presentation"
            ></span>

            <span
                class="shimmer inline-block w-6 h-6"
                role="presentation"
            ></span>
        </div>
    </div>

    <div class="flex gap-8 mt-10 overflow-auto scrollbar-hide max-sm:mt-5">
        <x-shop::shimmer.products.cards.grid
            class="min-w-[291px]"
            :count="4"
        />
    </div>

    @if ($navigationLink)
        <a
            class="shimmer block w-[150.172px] h-12 mt-14 mx-auto rounded-2xl"
            role="button"
            aria-label="Show more products"
        ></a>
    @endif
</div>

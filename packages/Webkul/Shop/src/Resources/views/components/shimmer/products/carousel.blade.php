<div class="container mt-20 max-lg:px-8 max-sm:mt-7 max-sm:!px-4">
    <div class="flex items-center justify-between">
        <h3 class="shimmer h-8 w-[200px] max-sm:h-7"></h3>

        <div class="flex items-center justify-between gap-8 max-sm:hidden">
            <span
                class="shimmer inline-block h-6 w-6"
                role="presentation"
            ></span>

            <span
                class="shimmer inline-block h-6 w-6 max-sm:hidden"
                role="presentation"
            ></span>
        </div>

        <div class="shimmer h-5 w-[68px] sm:hidden"></div>
    </div>

    <div class="scrollbar-hide mt-10 flex gap-8 overflow-auto pb-2.5 max-sm:mt-5 max-sm:gap-4">
        <x-shop::shimmer.products.cards.grid
            class="min-w-[291px] max-sm:min-w-[192px]"
            :count="4"
        />
    </div>

    @if ($navigationLink)
        <a
            class="shimmer mx-auto mt-16 block h-12 w-[150.172px] rounded-2xl max-sm:hidden"
            role="button"
            aria-label="Show more products"
        ></a>
    @endif
</div>

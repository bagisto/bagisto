<div class="container mt-20 max-lg:px-8 max-sm:mt-8">
    <div class="flex justify-between">
        <h3 class="shimmer h-[45px] w-[200px]"></h3>

        <div class="flex items-center justify-between gap-8">
            <span
                class="shimmer inline-block h-6 w-6"
                role="presentation"
            ></span>

            <span
                class="shimmer inline-block h-6 w-6"
                role="presentation"
            ></span>
        </div>
    </div>

    <div class="scrollbar-hide mt-10 flex gap-8 overflow-auto max-sm:mt-5">
        <x-shop::shimmer.products.cards.grid
            class="min-w-[291px]"
            :count="4"
        />
    </div>

    @if ($navigationLink)
        <a
            class="shimmer mx-auto mt-14 block h-12 w-[150.172px] rounded-2xl"
            role="button"
            aria-label="Show more products"
        ></a>
    @endif
</div>

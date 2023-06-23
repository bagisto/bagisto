<div class="container mt-20 max-lg:px-[30px] max-sm:mt-[30px]">
    <div class="flex justify-between">
        <h3 class="w-[200px] h-[45px] font-dmserif shimmer"></h3>

        <div class="flex justify-between items-center gap-8">
            <span
                class="w-[24px] h-[24px] inline-block shimmer"
            >
            </span>

            <span
                class="w-[24px] h-[24px] inline-block shimmer"
            >
            </span>
        </div>
    </div>

    <div class="flex gap-8 mt-[60px] overflow-auto scrollbar-hide max-sm:mt-[20px]">
        <x-shop::shimmer.products.cards.grid
            class="min-w-[291px]"
            :count="4"
        >
        </x-shop::shimmer.products.cards.grid>
    </div>

    @if ($navigationLink)
        <a class="block mx-auto w-[150.172px] h-[48px] rounded-[18px] mt-[60px] shimmer">
        </a>
    @endif
</div>

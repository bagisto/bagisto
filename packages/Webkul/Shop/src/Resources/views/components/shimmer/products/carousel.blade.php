<div class="container mt-20 max-lg:px-[30px] max-sm:mt-[30px]">
    <div class="flex justify-between">
        <h3 class="shimmer w-[200px] h-[45px]"></h3>

        <div class="flex gap-8 justify-between items-center">
            <span class="shimmer inline-block w-[24px] h-[24px]"></span>

            <span class="shimmer inline-block w-[24px] h-[24px]"></span>
        </div>
    </div>

    <div class="flex gap-8 mt-[40px] overflow-auto scrollbar-hide max-sm:mt-[20px]">
        <x-shop::shimmer.products.cards.grid
            class="min-w-[291px]"
            :count="4"
        >
        </x-shop::shimmer.products.cards.grid>
    </div>

    @if ($navigationLink)
        <a class="shimmer block w-[150.172px] h-[48px] mt-[60px] mx-auto rounded-[18px]"></a>
    @endif
</div>

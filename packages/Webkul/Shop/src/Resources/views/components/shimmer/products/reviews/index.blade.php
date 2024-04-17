<!-- Review Section Header -->
<div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
    <div class="shimmer h-[45px] w-[245px]"></div>

    <div class="shimmer h-[46px] w-[187px] rounded-xl"></div>
</div>

<!-- Rating Shimmer -->
<x-shop::shimmer.products.reviews.ratings />

<!-- Ratings By Individual Stars Shimmer -->
<div class="flex items-center gap-x-5">
    <div class="mt-2.5 flex max-w-[365px] flex-wrap gap-y-5">
        @for ($i = 5; $i >= 1; $i--)
            <div class="flex items-center gap-x-6 max-sm:flex-wrap">
                <div class="shimmer h-6 w-[55px]"></div>

                <div class="shimmer h-4 w-[275px] rounded-sm"></div>
            </div>
        @endfor
    </div>
</div>

<div class="mt-14 grid grid-cols-[1fr_1fr] gap-5 max-1060:grid-cols-[1fr]">
    <!-- Review Card Shimmer -->
    <x-shop::shimmer.products.reviews.card count="12" />
</div>
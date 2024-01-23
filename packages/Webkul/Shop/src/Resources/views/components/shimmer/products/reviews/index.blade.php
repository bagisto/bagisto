<!-- Review Section Header -->
<div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
    <div class="shimmer w-[245px] h-[45px]"></div>

    <div class="shimmer w-[187px] h-[46px] rounded-xl"></div>
</div>

<!-- Rating Shimmer -->
<x-shop::shimmer.products.reviews.ratings />

<!-- Ratings By Individual Stars Shimmer -->
<div class="flex gap-x-5 items-center">
    <div class="flex gap-y-5 max-w-[365px] mt-2.5 flex-wrap">
        @for ($i = 5; $i >= 1; $i--)
            <div class="flex gap-x-6 items-center max-sm:flex-wrap">
                <div class="shimmer w-[55px] h-6"></div>

                <div class="shimmer w-[275px] h-4 rounded-sm"></div>
            </div>
        @endfor
    </div>
</div>

<div class="grid grid-cols-[1fr_1fr] mt-14 gap-5 max-1060:grid-cols-[1fr]">
    <!-- Review Card Shimmer -->
    <x-shop::shimmer.products.reviews.card count="12" />
</div>
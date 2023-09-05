{{-- Review Section Header --}}
<div class="flex items-center justify-between gap-[15px] max-sm:flex-wrap">
    <div class="shimmer w-[245px] h-[45px]"></div>

    <div class="shimmer w-[187px] h-[46px] rounded-[12px]"></div>
</div>

{{-- Rating Shimmer --}}
<x-shop::shimmer.products.reviews.ratings/>

{{-- Ratings By Individual Stars Shimmer --}}
<div class="flex gap-x-[20px] items-center">
    <div class="flex gap-y-[18px] max-w-[365px] mt-[10px] flex-wrap">
        @for ($i = 5; $i >= 1; $i--)
            <div class="flex gap-x-[25px] items-center max-sm:flex-wrap">
                <div class="shimmer w-[55px] h-[24px]"></div>

                <div class="shimmer w-[275px] h-[16px] rounded-[2px]"></div>
            </div>
        @endfor
    </div>
</div>

<div class="grid grid-cols-[1fr_1fr] mt-[60px] gap-[20px] max-1060:grid-cols-[1fr]">
    {{-- Review Card Shimmer --}}
    <x-shop::shimmer.products.reviews.card count="12"></x-shop::shimmer.products.reviews.card>
</div>
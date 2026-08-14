<!-- Review Section Header -->
<div class="mb-8 flex items-center justify-between gap-4 max-sm:flex-wrap">
    <div class="shimmer h-9 w-61.25"></div>
</div>

<div class="flex gap-16 max-lg:flex-wrap">
    <!-- Left Section -->
    <div class="flex flex-col gap-6">
        <div class="flex flex-col items-center gap-2">
            <div class="shimmer h-12 w-16"></div>
            
            <div class="flex items-center gap-0.5">
                <span class="shimmer h-7.5 w-7.5"></span>
                <span class="shimmer h-7.5 w-7.5"></span>
                <span class="shimmer h-7.5 w-7.5"></span>
                <span class="shimmer h-7.5 w-7.5"></span>
                <span class="shimmer h-7.5 w-7.5"></span>
            </div>

            <div class="shimmer h-6 w-20"></div>
        </div>

        <!-- Ratings By Individual Stars -->
        <div class="grid max-w-91.25 flex-wrap gap-y-3">
            @for ($i = 5; $i >= 1; $i--)
                <div class="row grid grid-cols-[1fr_2fr] items-center gap-4 max-sm:flex-wrap">
                    <div class="shimmer h-6 w-14"></div>

                    <div class="shimmer h-4 w-68.75 rounded-xs"></div>
                </div>
            @endfor
        </div>
    </div>

    <!-- Right Section -->
    <div class="flex w-full flex-col gap-5">
        <x-shop::shimmer.products.reviews.card count="12" />
    </div>
</div>
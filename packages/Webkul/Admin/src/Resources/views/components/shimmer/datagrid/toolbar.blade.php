{{--
    Mirrors the datagrid toolbar's mobile layout so the loading placeholder matches what renders.

    Below md that is two rows, not three: search + results, then pagination. Filter is
    absent because the real toolbar lifts it into the bar fixed to the foot of the
    screen — and that bar is part of the toolbar this placeholder stands in for, so
    during loading there is nothing there to stand in for.

    Desktop is unchanged — `me-auto` keeps the search left and the rest right.
--}}
<div class="mt-7 flex items-center gap-4 max-md:flex-wrap max-md:gap-y-3">
    <!-- Search + Results (first row on mobile, centered) -->
    <div class="flex gap-x-1 md:me-auto max-md:w-full">
        <div class="flex w-full items-center gap-x-1 max-md:justify-center">
            <div class="shimmer h-[38px] w-[190px] rounded-lg md:w-[302px]"></div>

            <div class="ltr:pl-2.5 rtl:pr-2.5">
                <p class="shimmer h-[17px] w-[75px]"></p>
            </div>
        </div>
    </div>

    <!-- Filter (desktop only — on mobile it lives in the fixed bottom bar) -->
    <div class="flex items-center gap-x-4 max-md:hidden">
        <div class="shimmer h-[38px] w-[94px] rounded-md"></div>
    </div>

    <!-- Pagination (second row on mobile, centered) -->
    <div class="flex items-center gap-x-2 max-md:w-full max-md:justify-center">
        <div class="shimmer h-[38px] w-[72px] rounded-md"></div>

        <p class="shimmer h-6 w-14"></p>

        <div class="shimmer h-[38px] w-10 rounded-md"></div>

        <div class="shimmer h-6 w-[37px]"></div>

        <div class="flex items-center gap-1">
            <div class="shimmer h-[38px] w-[38px] rounded-md"></div>

            <div class="shimmer h-[38px] w-[38px] rounded-md"></div>
        </div>
    </div>
</div>

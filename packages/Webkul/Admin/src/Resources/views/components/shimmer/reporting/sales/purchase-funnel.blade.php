<!-- Purchase Funnel Section -->
<div class="flex-1 relative p-4 bg-white dark:bg-gray-900 rounded box-shadow">
    <!-- Header -->
    <div class="shimmer w-[150px] h-[17px] mb-4"></div>

    <div class="grid grid-cols-4 gap-6">
        @foreach (range(1, 4) as $i)
            <div class="grid gap-4">
                <div class="grid gap-0.5">
                    <div class="shimmer w-[75px] h-[17px]"></div>
                    <div class="shimmer w-[120px] h-[17px]"></div>
                </div>

                <div class="shimmer w-full relative aspect-[0.5/1]"></div>

                <div class="shimmer w-full h-[17px]"></div>
            </div>
        @endforeach
    </div>

    <div class="flex gap-5 justify-end mt-6">
        <div class="flex gap-1 items-center">
            <div class="shimmer w-3.5 h-3.5 rounded-md"></div>
            <div class="shimmer w-[143px] h-[17px]"></div>
        </div>
    </div>
</div>
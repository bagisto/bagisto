<!-- Purchase Funnel Section -->
<div class="box-shadow relative flex-1 rounded bg-white p-4 dark:bg-gray-900">
    <!-- Header -->
    <div class="shimmer mb-4 h-[17px] w-[150px]"></div>

    <div class="grid grid-cols-4 gap-6">
        @foreach (range(1, 4) as $i)
            <div class="grid gap-4">
                <div class="grid gap-0.5">
                    <div class="shimmer h-[17px] w-[75px]"></div>
                    <div class="shimmer h-[17px] w-[120px]"></div>
                </div>

                <div class="shimmer relative aspect-[0.5/1] w-full"></div>

                <div class="shimmer h-[17px] w-full"></div>
            </div>
        @endforeach
    </div>

    <div class="mt-6 flex justify-end gap-5">
        <div class="flex items-center gap-1">
            <div class="shimmer h-3.5 w-3.5 rounded-md"></div>
            <div class="shimmer h-[17px] w-[143px]"></div>
        </div>
    </div>
</div>
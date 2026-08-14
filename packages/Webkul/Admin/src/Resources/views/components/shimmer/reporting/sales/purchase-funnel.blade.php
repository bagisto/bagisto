<!-- Purchase Funnel Section -->
<div class="box-shadow relative flex-1 rounded-sm bg-white p-4 dark:bg-gray-900">
    <!-- Header -->
    <div class="shimmer mb-4 h-4.25 w-37.5"></div>

    <div class="grid grid-cols-4 gap-6">
        @foreach (range(1, 4) as $i)
            <div class="grid gap-4">
                <div class="grid gap-0.5">
                    <div class="shimmer h-4.25 w-18.75"></div>
                    <div class="shimmer h-4.25 w-30"></div>
                </div>

                <div class="shimmer relative aspect-[0.5/1] w-full"></div>

                <div class="shimmer h-4.25 w-full"></div>
            </div>
        @endforeach
    </div>

    <div class="mt-6 flex justify-end gap-5">
        <div class="flex items-center gap-1">
            <div class="shimmer h-3.5 w-3.5 rounded-md"></div>
            <div class="shimmer h-4.25 w-35.75"></div>
        </div>
    </div>
</div>
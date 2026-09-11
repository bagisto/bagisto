@props(['count' => 10])

@for ($i = 0;  $i < $count; $i++)
    <!-- Single Card -->
    <div class="mb-4 w-full rounded-lg border p-4 last:mb-0">
        <div class="block space-y-3">
            <!-- Row 1 -->
            <div class="flex items-start justify-between">
                <div class="flex flex-col gap-1">
                    <div class="shimmer h-4 w-16"></div>

                    <div class="shimmer h-5 w-20"></div>
                </div>

                <div class="flex flex-col items-end gap-1">
                    <div class="shimmer h-4 w-20"></div>

                    <div class="shimmer h-5 w-24"></div>
                </div>
            </div>

            <!-- Row 2 -->
            <div class="flex items-start justify-between">
                <div class="flex flex-col gap-1">
                    <div class="shimmer h-4 w-20"></div>

                    <div class="shimmer h-5 w-24"></div>
                </div>

                <div class="flex flex-col items-end gap-1">
                    <div class="shimmer h-4 w-16"></div>

                    <div class="shimmer h-5 w-12"></div>
                </div>
            </div>

            <!-- Row 3 -->
            <div class="flex items-center justify-between border-t pt-2">
                <div class="mt-1 flex flex-col gap-2">
                    <div class="shimmer h-4 w-20"></div>

                    <div class="shimmer h-5 w-28"></div>
                </div>

                <div class="mt-1 flex items-center gap-1.5">
                    <div class="shimmer h-9 w-9 rounded-md"></div>

                    <div class="shimmer h-9 w-9 rounded-md"></div>
                </div>
            </div>
        </div>
    </div>
@endfor

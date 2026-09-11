@props(['count' => 10])

@for ($i = 0;  $i < $count; $i++)
    <!-- Single Card -->
    <div class="mb-4 w-full rounded-md border p-4 last:mb-0">
        <div class="flex justify-between">
            <div class="grid">
                <div class="shimmer h-5 w-32"></div>

                <div class="shimmer h-4 w-24"></div>
            </div>

            <div class="shimmer h-9 w-20 rounded-full"></div>
        </div>

        <div class="mt-2.5 grid">
            <div class="shimmer h-4 w-16"></div>

            <div class="shimmer h-7 w-28"></div>
        </div>
    </div>
@endfor

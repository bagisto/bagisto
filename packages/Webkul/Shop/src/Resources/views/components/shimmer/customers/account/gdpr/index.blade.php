@props(['count' => 10])

@for ($i = 0;  $i < $count; $i++)
    <!-- Single Card -->
    <div class="mb-4 w-full rounded-md border p-4 last:mb-0">
        <div class="flex items-center justify-between">
            <div class="flex flex-col gap-1">
                @for ($j = 0;  $j < 3; $j++)
                    <div class="flex gap-2">
                        <div class="shimmer h-5 w-16"></div>

                        <div class="shimmer h-5 w-24"></div>
                    </div>
                @endfor

                <div class="flex gap-2">
                    <div class="shimmer h-5 w-16"></div>

                    <div class="grid">
                        <div class="shimmer h-5 w-28"></div>

                        <div class="shimmer h-5 w-20"></div>
                    </div>
                </div>

                <div class="flex gap-2">
                    <div class="shimmer h-5 w-16"></div>

                    <div class="shimmer h-5 w-20 rounded-full"></div>
                </div>
            </div>

            <div class="shimmer h-9 w-24 rounded-full"></div>
        </div>
    </div>
@endfor

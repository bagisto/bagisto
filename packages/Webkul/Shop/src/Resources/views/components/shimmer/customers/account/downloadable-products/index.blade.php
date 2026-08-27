@props(['count' => 10])

<div class="grid gap-4">
    @for ($i = 0;  $i < $count; $i++)
        <!-- Single Card -->
        <div class="grid w-full gap-2.5 rounded-md border p-4">
            <div class="flex justify-between">
                <div class="grid">
                    <div class="shimmer h-5 w-32"></div>

                    <div class="shimmer h-4 w-24"></div>
                </div>

                <div class="shimmer h-9 w-20 rounded-full"></div>
            </div>

            <div class="grid">
                <div class="shimmer h-5 w-40"></div>

                <div class="shimmer h-4 w-28"></div>
            </div>
        </div>
    @endfor
</div>

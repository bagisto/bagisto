@props(['isMultiRow' => false])

@if (! $isMultiRow)
    <div class="row grid grid-cols-6 items-center gap-2.5 border-b border-zinc-200 bg-gray-50 px-6 py-4">
        <!-- Mass Actions -->
        <div class="shimmer h-5.25 w-6"></div>

        <div class="shimmer h-5.25 w-25"></div>

        <div class="shimmer h-5.25 w-25"></div>

        <div class="shimmer h-5.25 w-25"></div>

        <div class="shimmer h-5.25 w-25"></div>

        <div class="shimmer h-5.25 w-25 place-self-end"></div>
    </div>
@else
    <div class="row tems-center grid grid-cols-[2fr_1fr_1fr] items-center gap-2.5 border-b border-zinc-200 px-6 py-4">
        <!-- Mass Actions -->
        <div class="flex items-center gap-2.5">
            <div class="shimmer h-6 w-6"></div>

            <div class="shimmer h-5.25 w-50"></div>
        </div>

        <div class="shimmer h-5.25 w-50"></div>

        <div class="shimmer h-5.25 w-50"></div>
    </div>
@endif
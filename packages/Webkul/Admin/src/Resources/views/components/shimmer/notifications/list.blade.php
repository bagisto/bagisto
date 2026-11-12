<div>
    <div class="px-4 pb-1 pt-4">
        <div class="shimmer h-3.5 w-16"></div>
    </div>

    @for ($i = 0; $i < 6; $i++)
        <div class="flex items-start gap-3 px-4 py-3">
            <div class="shimmer h-9 w-9 shrink-0 rounded-full"></div>

            <div class="grid flex-1 gap-1.5">
                <div class="shimmer h-4 w-56 max-w-full"></div>

                <div class="shimmer h-3.5 w-40 max-w-full"></div>

                <div class="shimmer h-3 w-16"></div>
            </div>
        </div>
    @endfor
</div>

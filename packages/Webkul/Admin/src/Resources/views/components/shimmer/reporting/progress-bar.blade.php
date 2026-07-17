<div class="grid gap-7">
    @foreach (range(1, 5) as $i)
        <div class="grid">
            <div class="shimmer h-4.25 w-37.5"></div>

            <div class="flex items-center gap-5">
                <div class="shimmer h-2 w-full"></div>

                <div class="shimmer h-4.25 w-8.75"></div>
            </div>
        </div>
    @endforeach

    <div class="flex justify-end gap-5">
        <div class="flex items-center gap-1">
            <div class="shimmer h-3.5 w-3.5 rounded-md"></div>
            <div class="shimmer h-4.25 w-35.75"></div>
        </div>
    </div>
</div>
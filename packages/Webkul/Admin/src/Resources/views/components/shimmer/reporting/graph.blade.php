@props(['count' => 30])

<div class="flex gap-1.5">
    <div class="grid">
        @foreach (range(1, 10) as $i)
            <div class="shimmer w-[39px] h-[15px]"></div>
        @endforeach
    </div>

    <div class="w-full grid gap-1.5">
        <div class="flex items-end w-full pl-2.5 border-l border-b dark:border-gray-800 aspect-[3.23/1]">
            <div class="flex gap-5 justify-between items-end w-full aspect-[3.23/1] max-lg:gap-4 max-sm:gap-2.5">
                @foreach (range(1, $count) as $i)
                    <div
                        class="flex shimmer w-full"
                        style="height: {{ rand(10, 100) }}%"
                    ></div>
                @endforeach
            </div>
        </div>

        <div class="flex gap-5 justify-between pl-2.5 max-lg:gap-4 max-sm:gap-2.5">
            @foreach (range(1, $count) as $i)
                <div class="shimmer flex w-full h-[15px]"></div>
            @endforeach
        </div>
    </div>
</div>
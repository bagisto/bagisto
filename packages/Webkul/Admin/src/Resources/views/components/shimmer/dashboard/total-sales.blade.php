@props(['count' => 30])

<div class="grid w-full gap-4 border-b px-4 py-2 dark:border-gray-800">
    <!-- Total Sales -->
    <div class="flex h-9.5 w-full justify-between gap-2">
        <div class="flex flex-col justify-between gap-1">
            <div class="shimmer h-4.25 w-21.25"></div>

            <!-- Total Sales Amount -->
            <div class="shimmer h-4.25 w-21.25"></div>
        </div>

        <div class="flex flex-col justify-between gap-1">
            <!-- Date -->
            <div class="shimmer h-4.25 w-20.75"></div>

            <!-- Total Orders -->
            <div class="shimmer h-4.25 w-14 self-end"></div>
        </div>
    </div>

    <!-- Graph Chart -->

    <div class="flex gap-1.5">
        <div class="grid">
            @foreach (range(1, 10) as $i)
                <div class="shimmer h-2.5 w-8.5">
                </div>
            @endforeach
        </div>

        <div class="grid w-full gap-1.5">
            <div class="flex aspect-[2] h-45 w-71.25 items-end border-b border-l pl-2.5 dark:border-gray-800">
                <div class="flex aspect-[2] w-full items-end justify-between gap-2.5">
                    @foreach (range(1, 14) as $i)
                        <div class="shimmer flex w-full" style="height: {{ rand(10, 100) }}%"></div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-between gap-5 pl-2.5 max-lg:gap-4 max-sm:gap-2.5">
                @foreach (range(1, 10) as $i)
                    <div class="shimmer mt-1 flex h-10.5 w-full rotate-45"></div>
                @endforeach
            </div>
        </div>
    </div>
</div>
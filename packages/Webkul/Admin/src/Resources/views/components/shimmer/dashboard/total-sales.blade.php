@props(['count' => 30])

<div class="grid w-full gap-4 border-b px-4 py-2 dark:border-gray-800">
    <!-- Total Sales -->
    <div class="flex h-[38px] w-full justify-between gap-2">
        <div class="flex flex-col justify-between gap-1">
            <div class="shimmer h-[17px] w-[85px]"></div>

            <!-- Total Sales Amount -->
            <div class="shimmer h-[17px] w-[85px]"></div>
        </div>

        <div class="flex flex-col justify-between gap-1">
            <!-- Date -->
            <div class="shimmer h-[17px] w-[83px]"></div>

            <!-- Total Orders -->
            <div class="shimmer h-[17px] w-14 self-end"></div>
        </div>
    </div>

    <!-- Graph Chart -->

    <div class="flex gap-1.5">
        <div class="grid">
            @foreach (range(1, 10) as $i)
                <div class="shimmer h-2.5 w-[34px]">
                </div>
            @endforeach
        </div>

        <div class="grid w-full gap-1.5">
            <div class="flex aspect-[2] h-[180px] w-[285px] items-end border-b border-l pl-2.5 dark:border-gray-800">
                <div class="flex aspect-[2] w-full items-end justify-between gap-2.5">
                    @foreach (range(1, 14) as $i)
                        <div class="shimmer flex w-full" style="height: {{ rand(10, 100) }}%"></div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-between gap-5 pl-2.5 max-lg:gap-4 max-sm:gap-2.5">
                @foreach (range(1, 10) as $i)
                    <div class="shimmer mt-1 flex h-[42px] w-full rotate-45"></div>
                @endforeach
            </div>
        </div>
    </div>
</div>
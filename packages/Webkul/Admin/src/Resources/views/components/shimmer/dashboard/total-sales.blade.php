@props(['count' => 30])

<div class="w-full grid gap-4 px-4 py-2 border-b dark:border-gray-800">
    <!-- Total Sales -->
    <div class="w-full h-[38px] flex gap-2 justify-between">
        <div class="flex flex-col gap-1 justify-between">
            <div class="shimmer w-[85px] h-[17px]"></div>

            <!-- Total Sales Amount -->
            <div class="shimmer w-[85px] h-[17px]"></div>
        </div>

        <div class="flex flex-col gap-1 justify-between">
            <!-- Date -->
            <div class="shimmer w-[83px] h-[17px]"></div>

            <!-- Total Orders -->
            <div class="shimmer w-14 h-[17px] self-end"></div>
        </div>
    </div>

    <!-- Graph Chart -->

    <div class="flex gap-1.5">
        <div class="grid">
            @foreach (range(1, 10) as $i)
                <div class="shimmer w-[34px] h-2.5">
                </div>
            @endforeach
        </div>

        <div class="w-full grid gap-1.5">
            <div class="flex items-end w-[285px] h-[180px] pl-2.5 border-l border-b dark:border-gray-800 aspect-[2]">
                <div class="w-full flex gap-2.5 justify-between items-end aspect-[2]">
                    @foreach (range(1, 14) as $i)
                        <div class="flex shimmer w-full" style="height: {{ rand(10, 100) }}%"></div>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-5 justify-between pl-2.5 max-lg:gap-4 max-sm:gap-2.5">
                @foreach (range(1, 10) as $i)
                    <div class="shimmer rotate-45 flex w-full mt-1 h-[42px]"></div>
                @endforeach
            </div>
        </div>
    </div>
</div>
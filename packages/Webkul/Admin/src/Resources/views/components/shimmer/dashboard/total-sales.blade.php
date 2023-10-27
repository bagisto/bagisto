@props(['count' => 30])

<div class="w-full grid gap-[16px] px-[16px] py-[8px] border-b dark:border-gray-800">
    {{-- Total Sales --}}
    <div class="w-full h-[38px] flex gap-[8px] justify-between">
        <div class="flex flex-col gap-[4px] justify-between">
            <div class="shimmer w-[85px] h-[17px]"></div>

            {{-- Total Sales Amount --}}
            <div class="shimmer w-[85px] h-[17px]"></div>
        </div>

        <div class="flex flex-col gap-[4px] justify-between">
            {{-- Date --}}
            <div class="shimmer w-[83px] h-[17px]"></div>

            {{-- Total Orders --}}
            <div class="shimmer w-[56px] h-[17px] self-end"></div>
        </div>
    </div>

    {{-- Graph Chart --}}

    <div class="flex gap-[5px]">
        <div class="grid">
            @foreach (range(1, 10) as $i)
                <div class="shimmer w-[34px] h-[10px]">
                </div>
            @endforeach
        </div>

        <div class="w-full grid gap-[5px]">
            <div class="flex items-end w-[285px] h-[180px] pl-[10px] border-l-[1px] border-b-[1px] dark:border-gray-800 aspect-[2]">
                <div class="w-full flex gap-[10px] justify-between items-end aspect-[2]">
                    @foreach (range(1, 14) as $i)
                        <div class="flex shimmer w-full" style="height: {{ rand(10, 100) }}%"></div>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-[20px] justify-between pl-[10px] max-lg:gap-[15px] max-sm:gap-[10px]">
                @foreach (range(1, 10) as $i)
                    <div class="shimmer rotate-45 flex w-full mt-[3px] h-[42px]"></div>
                @endforeach
            </div>
        </div>
    </div>
</div>
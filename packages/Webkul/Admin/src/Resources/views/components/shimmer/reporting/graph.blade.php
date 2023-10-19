<div class="flex gap-[5px]">
    <div class="grid">
        @foreach (range(1, 10) as $i)
            <div class="shimmer w-[39px] h-[15px]"></div>
        @endforeach
    </div>

    <div class="w-full grid gap-[5px]">
        <div class="flex items-end w-full pl-[10px] border-l-[1px]  border-b-[1px] aspect-[3.23/1]">
            <div class="w-full flex gap-[20px] justify-between items-end aspect-[3.23/1] max-lg:gap-[15px] max-sm:gap-[10px]">
                @foreach (range(1, 30) as $i)
                    <div
                        class="flex shimmer w-full"
                        style="height: {{ rand(10, 100) }}%"
                    ></div>
                @endforeach
            </div>
        </div>

        <div class="flex gap-[20px] justify-between pl-[10px] max-lg:gap-[15px] max-sm:gap-[10px]">
            @foreach (range(1, 30) as $i)
                <div class="shimmer flex w-full h-[15px]"></div>
            @endforeach
        </div>
    </div>
</div>

<div class="flex gap-[20px] justify-center">
    <div class="flex gap-[4px] items-center">
        <div class="shimmer w-[14px] h-[14px] rounded-[3px]"></div>
        <div class="shimmer w-[143px] h-[17px]"></div>
    </div>
    
    <div class="flex gap-[4px] items-center">
        <div class="shimmer w-[14px] h-[14px] rounded-[3px]"></div>
        <div class="shimmer w-[143px] h-[17px]"></div>
    </div>
</div>
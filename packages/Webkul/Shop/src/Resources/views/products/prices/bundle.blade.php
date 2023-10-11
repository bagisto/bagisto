<div class="grid gap-[5px]">
    @if ($prices['from']['regular']['price'] != $prices['from']['final']['price'])
        <p class="flex gap-[15px] items-center max-sm:text-[18px]">
            <span class="text-[#6E6E6E] line-through max-sm:text-[15px]">
                {{ $prices['from']['regular']['formatted_price'] }}
            </span>
            
            {{ $prices['from']['final']['formatted_price'] }}
        </p>
    @else
        <p class="flex gap-[15px] items-center max-sm:text-[18px]">
            {{ $prices['from']['regular']['formatted_price'] }}
        </p>
    @endif

    @if (
        $prices['from']['regular']['price'] != $prices['to']['regular']['price']
        || $prices['from']['final']['price'] != $prices['to']['final']['price']
    )
        <p class="text-[18px] max-sm:text-[15px]">To</p>
        
        @if ($prices['to']['regular']['price'] != $prices['to']['final']['price'])
            <p class="flex gap-[15px] items-center max-sm:text-[18px]">
                <span class="text-[#6E6E6E] line-through max-sm:text-[15px]">
                    {{ $prices['to']['regular']['formatted_price'] }}
                </span>
                
                {{ $prices['to']['final']['formatted_price'] }}
            </p>
        @else
            <p class="flex gap-[15px] items-center max-sm:text-[18px]">
                {{ $prices['to']['regular']['formatted_price'] }}
            </p>
        @endif
    @endif
</div>
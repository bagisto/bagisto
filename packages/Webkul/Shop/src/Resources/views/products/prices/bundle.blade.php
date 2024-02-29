<div class="grid gap-1.5">
    @if ($prices['from']['regular']['price'] != $prices['from']['final']['price'])
        <p class="flex gap-4 items-center max-sm:text-lg">
            <span
                class="text-[#6E6E6E] line-through max-sm:text-base"
                aria-label="{{ $prices['from']['regular']['formatted_price'] }}"
            >
                {{ $prices['from']['regular']['formatted_price'] }}
            </span>
            
            {{ $prices['from']['final']['formatted_price'] }}
        </p>
    @else
        <p class="flex gap-4 items-center max-sm:text-lg">
            {{ $prices['from']['regular']['formatted_price'] }}
        </p>
    @endif

    @if (
        $prices['from']['regular']['price'] != $prices['to']['regular']['price']
        || $prices['from']['final']['price'] != $prices['to']['final']['price']
    )
        <p class="text-base font-normal">To</p>
        
        @if ($prices['to']['regular']['price'] != $prices['to']['final']['price'])
            <p class="flex gap-4 items-center max-sm:text-lg">
                <span
                    class="text-[#6E6E6E] line-through max-sm:text-base"
                    aria-label="{{ $prices['to']['regular']['formatted_price'] }}"
                >
                    {{ $prices['to']['regular']['formatted_price'] }}
                </span>
                
                {{ $prices['to']['final']['formatted_price'] }}
            </p>
        @else
            <p class="flex gap-4 items-center max-sm:text-lg">
                {{ $prices['to']['regular']['formatted_price'] }}
            </p>
        @endif
    @endif
</div>
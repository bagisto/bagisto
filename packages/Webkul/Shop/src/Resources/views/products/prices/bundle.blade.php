<div class="max-md:[&>*]:leading-6 max-sm:[&>*]:leading-4 grid gap-1.5 max-md:flex">
    @if ($prices['from']['regular']['price'] != $prices['from']['final']['price'])
        <p class="flex items-center gap-4 max-sm:text-sm">
            <span
                class="final-price text-zinc-500 line-through max-sm:text-sm"
                aria-label="{{ $prices['from']['regular']['formatted_price'] }}"
            >
                {{ $prices['from']['regular']['formatted_price'] }}
            </span>
            
            {{ $prices['from']['final']['formatted_price'] }}
        </p>
    @else
        <p class="final-price flex items-center gap-4 max-sm:text-sm">
            {{ $prices['from']['regular']['formatted_price'] }}
        </p>
    @endif

    @if (
        $prices['from']['regular']['price'] != $prices['to']['regular']['price']
        || $prices['from']['final']['price'] != $prices['to']['final']['price']
    )
        <p class="text-base font-normal max-sm:text-sm">To</p>
        
        @if ($prices['to']['regular']['price'] != $prices['to']['final']['price'])
            <p class="flex items-center gap-4 max-sm:text-sm">
                <span
                    class="final-price text-zinc-500 line-through max-sm:text-sm"
                    aria-label="{{ $prices['to']['regular']['formatted_price'] }}"
                >
                    {{ $prices['to']['regular']['formatted_price'] }}
                </span>
                
                {{ $prices['to']['final']['formatted_price'] }}
            </p>
        @else
            <p class="final-price flex items-center gap-4 max-sm:text-sm">
                {{ $prices['to']['regular']['formatted_price'] }}
            </p>
        @endif
    @endif
</div>
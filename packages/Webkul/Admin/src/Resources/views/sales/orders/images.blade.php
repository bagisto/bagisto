<div>
    @php
        $iteration = 0;
        $maxVisibleItems = 3;
        $restCount = $order->items->count() - $maxVisibleItems
    @endphp
    
    <div class="flex gap-[6px]">
        @foreach ($order->items as $item)
            @if ($iteration >= $maxVisibleItems)
                @break
            @endif

            <div class="relative">
                <img class="min-h-[65px] min-w-[65px] max-h-[65px] max-w-[65px] rounded-[4px]"
                    src="{{ $item->product->base_image_url }}">
                <span
                    class="absolute bottom-[1px] left-[1px] text-[12px] font-bold text-white bg-darkPink rounded-full px-[6px]">
                    {{ $item->product->images->count() }}
                </span>
            </div> 

            @php($iteration++)
        @endforeach

            
        @if ($restCount >= 1)
            <a href="{{ route('admin.sales.orders.view', $order->id) }}">
                <div class="flex items-center w-[65px] h-[65px] bg-gray-50 rounded-[4px]">
                    <p class="text-[12px] text-gray-600 text-center font-bold px-[6px] py-[6px]">{{ $restCount }}+ More Products</p>
                </div>
            </a>
        @endif
    </div>
</div>

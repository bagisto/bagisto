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
                @if($item->product?->base_image_url)
                    <img
                        class="w-full h-[60px] max-w-[60px] max-h-[60px] relative rounded-[4px]"
                        src="{{ $item->product?->base_image_url }}"
                    >
                @else
                    <div class="w-full h-[60px] max-w-[60px] max-h-[60px] relative border border-dashed border-gray-300 rounded-[4px]">
                        <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}">
                        
                        <p class="absolute w-full bottom-[5px] text-[6px] text-gray-400 text-center font-semibold"> 
                            @lang('admin::app.sales.invoices.view.product-image') 
                        </p>
                    </div>
                @endif

                <span
                    class="absolute bottom-[1px] ltr:left-[1px] rtl:right-[1px] text-[12px] font-bold text-white bg-darkPink rounded-full px-[6px]">
                    {{ $item->product?->images->count() }}
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
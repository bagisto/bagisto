{!! view_render_event('bagisto.shop.products.view.stock.before', ['product' => $product]) !!}

<div class="inline-block price-label px-2 py-1 mr-2 text-xs font-semibold tracking-wide text-white  bg-{{$product->haveSufficientQuantity(1) ? 'navyBlue' : 'red-600' }} rounded-full">
    @if ($product->haveSufficientQuantity(1) === true )
        {{ __('shop::app.products.in-stock') }}
    @elseif ($product->haveSufficientQuantity(1) > 0 )
        {{ __('shop::app.products.available-for-order') }}
    @else
        {{ __('shop::app.products.out-of-stock') }}
    @endif
</div>

{!! view_render_event('bagisto.shop.products.view.stock.after', ['product' => $product]) !!}
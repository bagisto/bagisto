{!! view_render_event('bagisto.shop.products.price.before', ['product' => $product]) !!}

@php
    $productPrice = $product->getTypeInstance()->getProductPrices();
@endphp

<span class="card-current-price fw6 mr10">
    {{ $productPrice['final_price']['formated_price'] }}
</span>

@if ((!isset($showDiscount) || $showDiscount) && $productPrice['regular_price']['price'] > $productPrice['final_price']['price'])
    <span class="card-actual-price mr10">
        {{ $productPrice['regular_price']['formated_price'] }}
    </span>

    <span class="card-discount">
        {{ (($productPrice['regular_price']['price'] - $productPrice['final_price']['price']) * 100) / $productPrice['regular_price']['price'] }}%
    </span>
@endif

{!! view_render_event('bagisto.shop.products.price.after', ['product' => $product]) !!}
{!! view_render_event('bagisto.shop.products.add_to_cart.before', ['product' => $product]) !!}

@php
    $width = (core()->getConfigData('catalog.products.storefront.buy_now_button_display') == 1) ? '49' : '95';
@endphp

<form action="{{ route('cart.add', $product->product_id) }}" method="POST">
    @csrf

    <input type="hidden" name="product_id" value="{{ $product->product_id }}">

    <input type="hidden" name="quantity" value="1">

    <button type="submit" class="btn btn-lg btn-primary addtocart" {{ ! $product->isSaleable() ? 'disabled' : '' }}
    style="width: {{ $width . '%' }};">
        {{ ($product->type == 'booking') ?  __('shop::app.products.book-now') :  __('shop::app.products.add-to-cart') }}
    </button>
</form>

{!! view_render_event('bagisto.shop.products.add_to_cart.after', ['product' => $product]) !!}
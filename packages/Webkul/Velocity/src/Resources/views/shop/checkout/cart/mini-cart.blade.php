@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

@php
    $cart = cart()->getCart();
@endphp

<div class="mini-cart-container pull-right">
    @if ($cart)
        @php
            Cart::collectTotals();
            $items = $cart->items;
        @endphp

        <div class="dropdown">
            <cart-btn item-count="{{ $cart->items->count() }}"></cart-btn>

            <div class="modal-content sensitive-modal cart-modal-content hide" id="cart-modal-content">
                <!--Body-->
                <div class="mini-cart-container">
                    @foreach ($items as $item)
                        @php
                            $images = $item->product->getTypeInstance()->getBaseImage($item);
                        @endphp

                        <div class="row small-card-container">
                            <div class="col-3 product-image-container mr15">
                                <a href="{{ route('shop.checkout.cart.remove', ['id' => $item->id]) }}">
                                    <span class="rango-close"></span>
                                </a>

                                <a
                                    href="{{ route('shop.productOrCategory.index', $item->product->url_key) }}"
                                    class="unset">

                                    <div class="product-image"
                                        style="background-image: url('{{ $images['small_image_url']  }}');">
                                    </div>
                                </a>
                            </div>
                            <div class="col-9 no-padding card-body align-vertical-top">
                                <div class="no-padding">
                                    {!! view_render_event('bagisto.shop.checkout.cart-mini.item.name.before', ['item' => $item]) !!}

                                    <div class="fs16 text-nowrap fw6">{{ $item->name }}</div>

                                    {!! view_render_event('bagisto.shop.checkout.cart-mini.item.name.after', ['item' => $item]) !!}

                                    {!! view_render_event('bagisto.shop.checkout.cart-mini.item.price.before', ['item' => $item]) !!}

                                    <div class="fs18 card-current-price fw6">
                                        <div class="display-inbl">
                                            <label class="fw5">{{ __('velocity::app.checkout.qty') }}</label>

                                            {!! view_render_event('bagisto.shop.checkout.cart-mini.item.quantity.before', ['item' => $item]) !!}

                                            <input type="text" disabled value="{{ $item->quantity }}" class="ml5" />

                                            {!! view_render_event('bagisto.shop.checkout.cart-mini.item.quantity.after', ['item' => $item]) !!}
                                        </div>
                                        <span class="card-total-price fw6">
                                            {{ core()->currency($item->base_total) }}
                                        </span>
                                    </div>

                                    {!! view_render_event('bagisto.shop.checkout.cart-mini.item.price.after', ['item' => $item]) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!--Footer-->
                <div class="modal-footer">
                    <h2 class="col-6 text-left fw6">
                        {{ __('velocity::app.checkout.cart.cart-subtotal') }}
                    </h2>

                    {!! view_render_event('bagisto.shop.checkout.cart-mini.subtotal.before', ['cart' => $cart]) !!}

                    <h2 class="col-6 text-right fw6 no-padding">{{ core()->currency($cart->base_sub_total) }}</h2>

                    {!! view_render_event('bagisto.shop.checkout.cart-mini.subtotal.after', ['cart' => $cart]) !!}
                </div>

                <div class="modal-footer">
                    <a class="col-4 text-left fs16 link-color remove-decoration" href="{{ route('shop.checkout.cart.index') }}">
                        {{ __('velocity::app.checkout.cart.view-cart') }}
                    </a>

                    <div class="col-8 text-right no-padding">
                        <a href="{{ route('shop.checkout.onepage.index') }}">
                            <button
                                type="button"
                                class="theme-btn fs16 fw6">
                                {{ __('velocity::app.checkout.checkout') }}
                            </button>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    @else
        <div class="dropdown disable-active">
            <cart-btn item-count="{{ __('shop::app.minicart.zero') }}"></cart-btn>
        </div>
    @endif
</div>
{!! view_render_event('bagisto.shop.products.add_to_cart.before', ['product' => $product]) !!}

<div class="row mx-0 col-12 no-padding">
    <div class="add-to-cart-btn pl0">
        @if (isset($form) && !$form)
            <button
                type="submit"
                {{ ! $product->isSaleable() ? 'disabled' : '' }}
                class="btn btn-add-to-cart {{ $addToCartBtnClass ?? '' }}">

                @if (! (isset($showCartIcon) && !$showCartIcon))
                    <i class="material-icons text-down-3">shopping_cart</i>
                @endif

                <span class="fs14 fw6 text-uppercase text-up-4">
                    {{ __('shop::app.products.add-to-cart') }}
                </span>
            </button>
        @else
            <form
                method="POST"
                action="{{ route('cart.add', $product->product_id) }}">

                @csrf

                <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                <input type="hidden" name="quantity" value="1">
                <button
                    type="submit"
                    {{ ! $product->isSaleable() ? 'disabled' : '' }}
                    class="btn btn-add-to-cart {{ $addToCartBtnClass ?? '' }}">

                    @if (! (isset($showCartIcon) && !$showCartIcon))
                        <i class="material-icons text-down-3">shopping_cart</i>
                    @endif

                    <span class="fs14 fw6 text-uppercase text-up-4">
                        {{ $btnText ?? __('shop::app.products.add-to-cart') }}
                    </span>
                </button>
            </form>

            {{-- <add-to-cart
                form="true"
                csrf-token='{{ csrf_token() }}'
                product-id="{{ $product->product_id }}"
                add-class-to-btn="{{ $addToCartBtnClass ?? '' }}"
                is-enable={{ ! $product->isSaleable() ? 'false' : 'true' }}
                show-cart-icon={{ !(isset($showCartIcon) && !$showCartIcon) }}
                btn-text="{{ $btnText ?? __('shop::app.products.add-to-cart') }}">
            </add-to-cart> --}}
        @endif
    </div>

    @if (! (isset($showWishlist) && !$showWishlist))
        @include('shop::products.wishlist', [
            'addClass' => $addWishlistClass ?? ''
        ])
    @endif
</div>

{!! view_render_event('bagisto.shop.products.add_to_cart.after', ['product' => $product]) !!}
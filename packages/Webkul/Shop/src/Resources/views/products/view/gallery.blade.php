<div class="product-gallery-group" id="getbound">
    <div class="product-image-group">

        <div class="side-group">
            <img src="{{ bagisto_asset('images/jeans.jpg') }}" />
            <img src="{{ bagisto_asset('images/jeans.jpg') }}" />
            <img src="{{ bagisto_asset('images/jeans.jpg') }}" />
            <img src="{{ bagisto_asset('images/jeans.jpg') }}" />
        </div>

        <div class="product-hero-image">
            <img src="{{ bagisto_asset('images/jeans_big.jpg') }}" />
            <img class="wishlist" src="{{ bagisto_asset('images/wish.svg') }}" />
            <img class="share" src="{{ bagisto_asset('images/icon-share.svg') }}" />
        </div>

    </div>

    <div class="product-button-group">
        <form method="POST" action="{{ route('cart.add', $product->id) }}">
            {{ csrf_field() }}

            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <input type="hidden" name="qty" value="1">

            <input type="submit" class="btn btn-lg add-to-cart" value="Add to Cart">
        </form>
        {{-- <form>
            <input type="hidden" name="product_id" value="">
            <input type="hidden" name="qty" value="1">
            <button type="submit" class="btn btn-lg btn-primary buy-now">Buy Now</button>
        </form> --}}
    </div>
</div>

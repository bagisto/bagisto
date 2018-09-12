<div class="product-gallery-group">
    <div class="product-image-group">

        <div class="side-group">
            <img src="{{ bagisto_asset('images/jeans.jpg') }}" />
            <img src="{{ bagisto_asset('images/jeans.jpg') }}" />
            <img src="{{ bagisto_asset('images/jeans.jpg') }}" />
            <img src="{{ bagisto_asset('images/jeans.jpg') }}" />
        </div>

        <div class="product-hero-image" id="product-hero-image">
            <img class="hero-image" src="{{ bagisto_asset('images/jeans_big.jpg') }}" />
            <img class="wishlist" src="{{ bagisto_asset('images/wish.svg') }}" />
            <img class="share" src="{{ bagisto_asset('images/icon-share.svg') }}" />
        </div>
    </div>
    <div class="product-button-group">

        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <input type="hidden" name="qty" value="1">

        <input type="submit" class="btn btn-lg add-to-cart" value="Add to Cart">
        {{-- <form>
            <input type="hidden" name="product_id" value="">
            <input type="hidden" name="qty" value="1">
            <button type="submit" class="btn btn-lg btn-primary buy-now">Buy Now</button>
        </form> --}}
        {{-- {{ dd(unserialize(Cookie::get('session_c'))) }} --}}
    </div>
</div>
@push('scripts')

    <script>

        Vue.component('product-gallery', {
            props: ['images']
        });

    </script>

@endpush

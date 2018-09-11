<div class="product-image-group">

    <div class="side-group">
        <img src="{{ bagisto_asset('images/jeans.jpg') }}" />
        <img src="{{ bagisto_asset('images/jeans.jpg') }}" />
        <img src="{{ bagisto_asset('images/jeans.jpg') }}" />
        <img src="{{ bagisto_asset('images/jeans.jpg') }}" />
    </div>

    <div class="product-hero-image" id="product-hero-image">
        <img src="{{ bagisto_asset('images/jeans_big.jpg') }}" />
        <img class="wishlist" src="{{ bagisto_asset('images/wish.svg') }}" />
        <img class="share" src="{{ bagisto_asset('images/icon-share.svg') }}" />
    </div>

</div>

@push('scripts')

    <script>
        
        Vue.component('product-gallery', {
            props: ['images']
        });

    </script>

@endpush
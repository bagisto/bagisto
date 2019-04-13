{!! view_render_event('bagisto.shop.products.list.card.before', ['product' => $productFlat->product]) !!}

<div class="product-card">

    @inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

    <?php $productBaseImage = $productImageHelper->getProductBaseImage($productFlat->product); ?>

    @if ($productFlat->new)
        <div class="sticker new">
            {{ __('shop::app.products.new') }}
        </div>
    @endif

    <div class="product-image">
        <a href="{{ route('shop.products.index', $productFlat->url_key) }}" title="{{ $productFlat->name }}">
            <img src="{{ $productBaseImage['medium_image_url'] }}" />
        </a>
    </div>

    <div class="product-information">

        <div class="product-name">
            <a href="{{ url()->to('/').'/products/' . $productFlat->url_key }}" title="{{ $productFlat->name }}">
                <span>
                    {{ $productFlat->name }}
                </span>
            </a>
        </div>

        @include ('shop::products.price', ['product' => $productFlat->product])

        @include('shop::products.add-buttons', ['product' => $productFlat->product])
    </div>

</div>

{!! view_render_event('bagisto.shop.products.list.card.after', ['product' => $productFlat->product]) !!}
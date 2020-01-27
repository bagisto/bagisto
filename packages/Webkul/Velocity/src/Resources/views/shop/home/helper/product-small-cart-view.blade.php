
@foreach ($products as $product)
    <div class="small-card-container">

        <div class="col-4 product-image-container mr15">
            <div class="product-image" style="background-image: url({{ $product['product-image'] }})"></div>
        </div>

        <div class="col-8 no-padding card-body">
            <div class="no-padding">
                <span class="fs16">{{ $product['productName'] }}</span>

                <span class="fs18 card-current-price fw6">
                    {{ $product['currency-icon'] }}{{ $product['selling-price'] }}
                </span>

                <div class="star-rating mt10">
                    <span class="rango-star-fill"></span>
                    <span class="rango-star-fill"></span>
                    <span class="rango-star-fill"></span>
                    <span class="rango-star"></span>
                    <span class="rango-star"></span>
                </div>
            </div>

        </div>
    </div>
@endforeach

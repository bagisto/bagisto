@foreach ($products as $product)
    <div class="col-2 lg-card-container">
        <div class="card padding-10">

            {{-- sale-button --}}
            @if ($product['actual-price'] > $product['selling-price'])
                <button type="button" class="sale-btn card-sale-btn fw6">Sale</button>
            @endif

            {{-- product image --}}
            <div class="card-product-image-container">
                <div
                    class="background-image-group"
                    style="background-image: url({{ $product['product-image'] }} );">
                </div>
            </div>

            <div class="card-bottom-container">

                <div class="card-body no-padding">
                    <span class="fs16 hide-text">{{ $product['productName'] }}</span>
                    <div class="fs18 card-price-container">

                        <span class="card-current-price fw6 mr10">
                            {{ $product['currency-icon'] }}{{ $product['selling-price'] }}
                        </span>

                        @if ($product['actual-price'] > $product['selling-price'])
                            <span class="card-actual-price mr10">
                                {{ $product['currency-icon'] }}{{ $product['actual-price'] }}
                            </span>

                            <span class="card-discount">
                                {{ (($product['actual-price'] - $product['selling-price']) * 100) / $product['actual-price'] }}%
                            </span>
                        @endif
                    </div>
                </div>

                @if ($product['review-count'])
                    <div class="star-rating mt10">
                        <span class="rango-star-fill"></span>
                        <span class="rango-star-fill"></span>
                        <span class="rango-star-fill"></span>
                        <span class="rango-star"></span>
                        <span class="rango-star"></span>
                        <span>{{ $product['review-count'] }} reviews</span>
                    </div>
                @else
                    <div class="mt10">
                        <span class="fs14">Be the first to write a review</span>
                    </div>
                @endif

                <div class="button-row mt10 card-bottom-container">
                    <button class="btn btn-add-to-cart">
                        <span class="rango-cart-1 fs20"></span>
                        <span class="fs14 align-vertical-top fw6">ADD TO CART</span>
                    </button>
                    {{-- <span class="rango-exchange fs24"></span> --}}
                    <span class="rango-heart"></span>
                </div>

            </div>
        </div>
    </div>
@endforeach
@inject ('reviewHelper', 'Webkul\Product\Product\Review')
@if ($total = $reviewHelper->getTotalReviews($product))

    <div class="product-ratings">

        <span class="stars">
            @for ($i = 1; $i <= round($reviewHelper->getAverageRating($product)); $i++)

                <span class="icon star-icon"></span>

            @endfor
        </span>

        <div class="total-reviews">
            {{ __('shop::app.products.total-reviews', ['total' => $total]) }}
        </div>

    </div>
@endif
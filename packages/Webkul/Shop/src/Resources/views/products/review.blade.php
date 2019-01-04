@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')

{!! view_render_event('bagisto.shop.products.review.before', ['product' => $product]) !!}

@if ($total = $reviewHelper->getTotalReviews($product))
    <div class="product-ratings mb-10">
        <span class="stars">
            @for ($i = 1; $i <= round($reviewHelper->getAverageRating($product)); $i++)
                <span class="icon star-icon"></span>
            @endfor
        </span>

        <div class="total-reviews">
            {{ __('shop::app.products.total-reviews', ['total' => $total]) }}

            {{ 
                __('shop::app.products.total-rating', [
                        'total_rating' => $reviewHelper->getTotalRating($product),
                        'total_reviews' => $total,
                    ]) 
            }}
        </div>
    </div>
@endif

{!! view_render_event('bagisto.shop.products.review.after', ['product' => $product]) !!}
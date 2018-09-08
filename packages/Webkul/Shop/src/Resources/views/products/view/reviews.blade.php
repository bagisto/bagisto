@inject ('reviewHelper', 'Webkul\Product\Product\Review')

@if ($total = $reviewHelper->getTotalReviews($product))
<div class="rating-reviews">
    <div class="title">
        Ratings & Reviews
    </div>

    <div class="overall">
        <div class="review-info">

            <span class="number">
                {{ $reviewHelper->getAverageRating($product) }}
            </span>

            <span class="stars">
                @for ($i = 1; $i <= $reviewHelper->getAverageRating($product); $i++)

                    <span class="icon star-icon"></span>
                
                @endfor
            </span>

            <div class="total-reviews">
                {{ __('shop::app.products.total-reviews', ['total' => $total]) }}
            </div>

        </div>

        <a href="{{ route('shop.reviews.create', $product->url_key) }}" class="btn btn-lg btn-primary">Write Review</a>

    </div>

    <div class="reviews">

        @foreach ($product->reviews()->paginate(5) as $review)
            <div class="review">
                <div class="title">
                    {{ $review->title }}
                </div>

                <span class="stars">
                    @for ($i = 1; $i <= $review->rating; $i++)

                        <span class="icon star-icon"></span>
                    
                    @endfor
                </span>

                <div class="message">
                    {{ $review->comment }}
                </div>

                <div class="reviewer-details">
                    <span class="by">
                        {{ __('shop::app.products.by', ['name' => $review->customer->name]) }}, 
                    </span>

                    <span class="when">
                        {{ $reviewHelper->formatDate($review->created_at) }}
                    </span>
                </div>
            </div>
        @endforeach

        <a href="{{ route('shop.reviews.index', $product->url_key) }}" class="view-all">View All</a>

        <hr/>
    </div>
</div>
@endif
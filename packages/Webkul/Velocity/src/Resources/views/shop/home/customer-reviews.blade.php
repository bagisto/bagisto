@php
    $reviews = app('Webkul\Velocity\Helpers\Helper')->getShopRecentReviews(4);
    $reviewCount = count($reviews);
@endphp

<div class="container-fluid reviews-container">
    @if ($reviewCount)
        <card-list-header
            heading="Customer Reviews"
        ></card-list-header>

        <div class="row">
            @foreach ($reviews as $key => $review)
                <div class="col-lg-3 col-md-12 review-wrapper">
                    <div class="card no-padding">
                        <div class="review-info">
                            <div class="customer-info">
                                <div class="align-vertical-top">
                                    <span class="customer-name fs20 display-inbl">
                                        {{ strtoupper(substr( $review['name'], 0, 1 )) }}
                                    </span>
                                </div>

                                <div>
                                    <h4 class="fs20 fw6 no-margin display-block">
                                        {{ $review['name'] }}
                                    </h4>

                                    <div class="product-info fs16">
                                        <span>Reviewed- <a class="remove-decoration link-color" href="{{ route('shop.productOrCategory.index', $review->product->url_key) }}">{{$review->product->name}}</a></span>
                                    </div>
                                </div>
                            </div>

                            <div class="star-ratings fs16">
                                <star-ratings :ratings="{{ $review['rating'] }}"></star-ratings>
                            </div>

                            <div class="review-description">
                                <p class="review-content fs16">{{ $review['comment'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@push('scripts')
    <script type="text/javascript">
        (() => {
            $('.carousel-showmanymoveone .item').each(() => {
                var itemToClone = $(this);
                var i;
                for (i = 1; i < 4; i++ ) {
                    itemToClone = itemToClone.next();

                    // wrap around if at end of item collection
                    if (!itemToClone.length) {
                        itemToClone = $(this).siblings(':first');
                    }

                    // grab item, clone, add marker class, add to collection
                    itemToClone.children(':first-child').clone()
                        .addClass("cloneditem-"+(i))
                        .appendTo($(this));
                }
            });

            $(document).on('click', '.rango-arrow-left', () => {
                $('.prev-slider').click();
            });

            $(document).on('click', '.rango-arrow-right', () => {
                $('.next-slider').click();
            });

        })();
    </script>

@endpush
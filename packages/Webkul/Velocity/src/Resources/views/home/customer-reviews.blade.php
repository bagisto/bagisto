@php
    $reviews = [
        [
            'name' => 'Shubham Mehrotra',
            'title' => 'Shubham Mehrotra',
            'rating' => 3,
            'comment' => 'I upgraded to the X from an iPhone 7, and this is a very big step forward for Apple.
                    I was sceptical of the removal of the home button, and the notch on top of the screen but that scepticism lasted just a number of days before I was convinced this is a setup.',
        ], [
            'name' => 'Shubham Mehrotra',
            'title' => 'Shubham Mehrotra',
            'rating' => 2,
            'comment' => 'Yes colgate total has been designed to be an every day toothpaste that you can use with braces, crowns and all other dental restorations.',
        ], [
            'name' => 'Shubham Mehrotra',
            'title' => 'Shubham Mehrotra',
            'rating' => 5,
            'comment' => 'There are the ultimate suckers. These giys are fake. They don’t ship your product they manage the shipping details. They don’t have a proper customer service numbers. ',
        ], [
            'name' => 'Shubham Mehrotra',
            'title' => 'Shubham Mehrotra',
            'rating' => 1,
            'comment' => 'I upgraded to the X from an iPhone 7, and this is a very big step forward for Apple.
                I was sceptical of the removal of the home button, and the notch on top of the screen but that scepticism lasted just a number of days before I was convinced this is a setup.',
        ], [
            'name' => 'Shubham Mehrotra',
            'title' => 'Shubham Mehrotra',
            'rating' => 1,
            'comment' => 'I upgraded to the X from an iPhone 7, and this is a very big step forward for Apple.
                I was sceptical of the removal of the home button, and the notch on top of the screen but that scepticism lasted just a number of days before I was convinced this is a setup.',
        ],
    ];

    $reviewCount = count($reviews);
@endphp


@push('css')
    <style>
        .example-slide {
            align-items: center;
            background-color: #666;
            color: #999;
            display: flex;
            font-size: 1.5rem;
            justify-content: center;
            min-height: 10rem;
        }
    </style>
@endpush

<div class="container-fluid reviews-container">

    <card-list-header
        heading="Customer Reviews"
        view-all="{{
            (isset($cardCount) && (sizeof($reviews) > $cardCount))
            ? 'http://localhost/PHP/laravel/Bagisto/bagisto-clone/public/categories/category1'
            : false
        }}"
        {{-- scrollable="true" --}}
    ></card-list-header>

    <div class="row">
        <carousel-component :slides-count="{{ sizeof($reviews) }}" slides-per-page="4">
            @foreach ($reviews as $key => $review)
                <slide slot="slide-{{ $key }}">
                    <div class="card">
                        <div class="review-info">
                            <div class="customer-info">
                                <div class="align-vertical-top">
                                    <span
                                        class="customer-name fs20 display-inbl"
                                    >
                                        {{ strtoupper(substr( $review['name'], 0, 1 )) }}
                                    </span>
                                </div>

                                <div>
                                    <label class="fs20 no-margin display-block">
                                        {{ $review['name'] }}
                                    </label>

                                    <div class="product-info fs16 text-up-4.text-up {
                                        top: -6px;
                                        position: relative;
                                    }">
                                        <span>Reviewed- {{$review['title']}}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="star-ratings fs16">

                                @php
                                    $rating = 5 - $review['rating'];
                                @endphp

                                @for ($i = 0; $i < $review['rating'] ; $i++)
                                    <span>
                                        <i class="rango-star-fill"></i>
                                    </span>
                                @endfor

                                @for ($i = 0; $i < $rating; $i++)
                                    <span>
                                        <i class="rango-star"></i>
                                    </span>
                                @endfor

                            </div>

                            <div class="review-description">
                                <p class="review-content fs16">{{ $review['comment'] }}</p>
                            </div>
                        </div>
                    </div>
                </slide>
            @endforeach

        </carousel-component>
    </div>

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
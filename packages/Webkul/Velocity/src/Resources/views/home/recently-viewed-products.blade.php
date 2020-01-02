{{-- <div class="container-fluid no-padding recently-reviewed-items-container">

    <div class="row">

        <div class="col-lg-6">
            <h2 class="fs20">Plants</h2>
        </div>

        <div class="col-6">
            <div class="row">

                <div class="col-4 no-padding switch-buttons">
                    <div class="row justify-content-end">
                        <h2 class="col-lg-1 no-padding mr20 fw6 cursor-pointer"><</h2>
                        <h2 class="col-lg-1 no-padding fw6 cursor-pointer">></h2>
                    </div>
                </div>

                <div class="col-8 text-right">
                    <h2 class="fs20">Recently Viewed Product</h2>
                </div>

            </div>
        </div>


    </div>

    @php
        $plants = [
            [
                'actual-price' => 25,
                'star-rating' => 3,
                'review-count' => 25,
                'currency-icon' => '$',
                'selling-price' => 25,
                'product-image' => asset('themes/velocity/assets/images/product1.png'),
                'productName' => 'Flower Pot Potted 1291324',
            ], [
                'actual-price' => 50,
                'star-rating' => 3,
                'review-count' => 25,
                'currency-icon' => '$',
                'selling-price' => 1,
                'product-image' => asset('themes/velocity/assets/images/product2.png'),
                'productName' => 'Flower Pot Potted 1291324',
            ], [
                'actual-price' => 50,
                'star-rating' => 3,
                'review-count' => 25,
                'currency-icon' => '$',
                'selling-price' => 23,
                'product-image' => asset('themes/velocity/assets/images/product3.png'),
                'productName' => 'Flower Pot Potted 1291324',
            ], [
                'actual-price' => 23,
                'star-rating' => 3,
                'review-count' => 0,
                'currency-icon' => '$',
                'selling-price' => 23,
                'product-image' => asset('themes/velocity/assets/images/product4.png'),
                'productName' => 'Flower Pot Potted 1291324',
            ], [
                'actual-price' => 50,
                'star-rating' => 3,
                'review-count' => 25,
                'currency-icon' => '$',
                'selling-price' => 23,
                'product-image' => asset('themes/velocity/assets/images/product1.png'),
                'productName' => 'Flower Pot Potted 1291324',
            ],
        ];

        $recentlyReviewed = [
            [
                'star-rating' => 3,
                'currency-icon' => '$',
                'selling-price' => 25,
                'product-image' => asset('themes/velocity/assets/images/product1.png'),
                'productName' => 'Flower Pot Potted 1291324',
            ], [
                'star-rating' => 3,
                'currency-icon' => '$',
                'selling-price' => 25,
                'product-image' => asset('themes/velocity/assets/images/product1.png'),
                'productName' => 'Flower Pot Potted 1291324',
            ], [
                'star-rating' => 3,
                'currency-icon' => '$',
                'selling-price' => 25,
                'product-image' => asset('themes/velocity/assets/images/product1.png'),
                'productName' => 'Flower Pot Potted 1291324',
            ], [
                'star-rating' => 3,
                'currency-icon' => '$',
                'selling-price' => 25,
                'product-image' => asset('themes/velocity/assets/images/product1.png'),
                'productName' => 'Flower Pot Potted 1291324',
            ],
        ]

    @endphp

    <div class="row">

        <!--- plants ---->
        @include('shop::home.helper.product-large-cart-view', [
            'products' => $plants
        ])

        <!---recently viewed product ---->
        <div class="col-lg-2 recetly-viewed-items">
            @include('shop::home.helper.product-small-cart-view', [
                'products' => $recentlyReviewed
            ])
        </div>

    </div>
</div> --}}

@php
    $cardCount = 5;
@endphp

<div class="container-fluid popular-products">

    <card-list-header
        heading="Plants"
    ></card-list-header>

    {!! view_render_event('bagisto.shop.new-products.before') !!}

    <div class="row flex-nowrap">
        @foreach ($products as $index => $product)
            @if ($index <= ($cardCount - 1))
                @include ('shop::products.list.card', ['product' => $product])
            @endif
        @endforeach

        @include ('shop::products.list.recently-viewed', ['products' => $products])

    </div>

    {!! view_render_event('bagisto.shop.new-products.after') !!}

</div>
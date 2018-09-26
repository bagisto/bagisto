@inject ('productImageHelper', 'Webkul\Product\Product\ProductImage')

@extends('shop::layouts.master')
@section('content-wrapper')
    <div class="account-content">
        @include('shop::customers.account.partials.sidemenu')

        <div class="cusomer-section">

            <h1> Reviews</h1>

            @foreach($reviews as $review)
            <div class="customer-section-info">

                <?php $images = $productImageHelper->getGalleryImages($review->product); ?>

                <div class="pro-img">
                    <img src="{{ $images[0]['small_image_url'] }}" />
                </div>

                <div class="pro-discription">

                    <div class="title">
                        {{ $review->product->name }}
                    </div>

                    <div class="rating">
                        @for($i=0 ; $i < $review->rating ; $i++)
                            <span class="icon star-icon"></span>
                        @endfor
                    </div>

                    <div class="discription">
                        {{ $review->comment }}
                    </div>
                </div>

            </div>
            @endforeach

        </div>

    </div>
@endsection
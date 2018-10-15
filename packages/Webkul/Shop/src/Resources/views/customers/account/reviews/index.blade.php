@inject ('productImageHelper', 'Webkul\Product\Product\ProductImage')

@extends('shop::layouts.master')
@section('content-wrapper')
    <div class="account-content">
        @include('shop::customers.account.partials.sidemenu')

        <div class="profile">

            <h1> Reviews</h1>

            @foreach($reviews as $review)
            <div class="profile-content">

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


    <div class="account-content">
        @inject ('productImageHelper', 'Webkul\Product\Product\ProductImage')

        @include('shop::customers.account.partials.sidemenu')

        <div class="profile">

            <div class="section-head">
                <span class="profile-heading">{{ __('shop::app.wishlist.title') }}</span>

                @if(count($items))
                <div class="profile-edit">
                    <a href="" style="margin-right: 15px;">{{ __('shop::app.wishlist.deleteall') }}</a>
                    <a href="">{{ __('shop::app.wishlist.moveall') }}</a>
                </div>
                @endif
                <div class="horizontal-rule"></div>
            </div>

            <div class="profile-content">

                @if(count($items))
                @foreach($items as $item)
                    <div class="wishlist-item mb-10">
                        <div class="media-info">
                            @php
                                $image = $productImageHelper->getProductBaseImage($item);
                            @endphp
                            <img class="media" src="{{ $image['small_image_url'] }}" />

                            <div class="info mt-20">
                                <div class="product-name">{{$item->name}}</div>
                            </div>
                        </div>

                        <div class="operations">
                            <a class="mb-50" href="{{ route('customer.wishlist.remove', $item->id) }}"><span class="icon trash-icon"></span></a>

                            <button class="btn btn-primary btn-md">Move To Cart</button>
                        </div>
                    </div>
                    <div class="horizontal-rule mb-10 mt-10"></div>
                @endforeach
                @else
                    <div class="empty">
                        {{ __('customer::app.wishlist.empty') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
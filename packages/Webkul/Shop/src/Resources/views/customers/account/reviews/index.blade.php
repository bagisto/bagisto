@inject ('productImageHelper', 'Webkul\Product\Product\ProductImage')

@extends('shop::layouts.master')
@section('content-wrapper')
    <div class="account-content">
        @include('shop::customers.account.partials.sidemenu')

        <div class="account-layout">

            <div class="account-head">
                <span class="back-icon"><a href="{{ route('customer.account.index') }}"><i class="icon icon-menu-back"></i></a></span>
                <span class="account-heading">{{ __('shop::app.customer.account.review.index.title') }}</span>
                <span></span>
                <div class="horizontal-rule"></div>
            </div>

            <div class="account-items-list">

                @if(is_null($reviews))
                    @foreach($reviews as $review)
                    <div class="account-item-card mt-15 mb-15">
                        <div class="media-info">
                            <?php $image = $productImageHelper->getGalleryImages($review->product); ?>
                            <img class="media" src="{{ $image[0]['small_image_url'] }}" />

                            <div class="info mt-20">
                                <div class="product-name">{{$review->product->name}}</div>

                                <div>
                                    @for($i=0 ; $i < $review->rating ; $i++)
                                        <span class="icon star-icon"></span>
                                    @endfor
                                </div>

                                <div>
                                    {{ $review->comment }}
                                </div>
                            </div>
                        </div>

                        <div class="operations">

                        </div>

                    </div>
                    <div class="horizontal-rule mb-10 mt-10"></div>
                    @endforeach
                @else
                    <div class="empty">
                        {{--  {{ __('customer::app.wishlist.empty') }}  --}}
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
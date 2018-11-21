@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.account.review.view.page-title') }}
@endsection

@section('content-wrapper')
    <div class="account-content">
        @include('shop::customers.account.partials.sidemenu')

        <div class="account-layout">
            <div class="account-head">
                <span class="account-heading">Reviews</span>
                <div class="horizontal-rule"></div>
            </div>

            <div class="account-items-list">
                @if(count($reviews))
                    @foreach($reviews as $review)
                    <div class="account-item-card mt-15 mb-15">
                        <div class="media-info">
                            <?php $image = $productImageHelper->getGalleryImages($review->product); ?>
                            <img class="media" src="{{ $image[0]['small_image_url'] }}" />

                            <div class="info mt-20">
                                <div class="product-name">{{$review->product->name}}</div>

                                <div>
                                    @for($i=0;$i<$review->rating;$i++)
                                        <span class="icon star-icon"></span>
                                    @endfor
                                </div>

                                <div>
                                    {{ $review->comment }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="horizontal-rule mb-10 mt-10"></div>
                    @endforeach
                @else
                    <div class="empty">
                        {{ __('customer::app.reviews.empty') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
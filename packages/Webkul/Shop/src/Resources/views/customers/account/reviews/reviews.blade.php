@inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

@extends('shop::layouts.master')
@section('content-wrapper')
    <div class="account-content">
        @include('shop::customers.account.partials.sidemenu')

        <div class="account-layout">

            <div class="account-head">
                <span class="account-heading">Reviews</span>
                <div class="horizontal-rule"></div>
            </div>

            <div class="account-items-list">
                {{-- <div class="account-item-card mt-15 mb-15">
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
                </div> --}}
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
            </div>
        </div>
    </div>
@endsection
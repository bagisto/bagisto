@extends('shop::layouts.master')

@section('content-wrapper')

<div class="account-content">
    @inject ('productImageHelper', 'Webkul\Product\Product\ProductImage')

    @include('shop::customers.account.partials.sidemenu')

    <div class="account-layout">

        <div class="account-head">

            <span class="account-heading">{{ __('shop::app.wishlist.title') }}</span>
            @if(count($items))
            <div class="account-edit">
                <a href="" style="margin-right: 15px;">{{ __('shop::app.wishlist.deleteall') }}</a>
                <a href="">{{ __('shop::app.wishlist.moveall') }}</a>
            </div>
            @endif
            <div class="horizontal-rule"></div>
        </div>

        <div class="account-items-list">

            @if(count($items))
            @foreach($items as $item)
                <div class="account-item-card mt-15 mb-15">
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

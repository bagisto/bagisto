@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.account.wishlist.customer-name', ['name' => $customer->name]) }}
@endsection

@section('content-wrapper')
    @inject ('reviewHelper', 'Webkul\Product\Helpers\Review')

    <div class="account-layout">
        <div class="account-head mb-15">
            <span class="account-heading">{{ __('shop::app.customer.account.wishlist.customer-name', ['name' => $customer->name]) }}</span>

            <div class="horizontal-rule"></div>
        </div>

        <div class="account-items-list">
            @foreach ($wishlistItems as $wishlistItem)
                @include('shop::customers.account.wishlist.wishlist-product', [
                    'wishlistItem' => $wishlistItem
                ])
            @endforeach
        </div>
    </div>
@endsection

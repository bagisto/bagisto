@inject ('toolbarHelper', 'Webkul\Product\Helpers\Toolbar')

@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.wishlist.page-title') }}
@endsection

@section('page-detail-wrapper')
    <div class="account-head">
        <span class="account-heading">{{ __('shop::app.customer.account.wishlist.title') }}</span>

        @if (count($items))
            <div class="account-action pull-right">
                <a
                    class="remove-decoration theme-btn light"
                    href="{{ route('customer.wishlist.removeall') }}">
                    {{ __('shop::app.customer.account.wishlist.deleteall') }}
                </a>
            </div>
        @endif
    </div>

    {!! view_render_event('bagisto.shop.customers.account.wishlist.list.before', ['wishlist' => $items]) !!}

    <div class="account-items-list row wishlist-container">

        @if ($items->count())
            @foreach ($items as $item)
                @php
                    $currentMode = $toolbarHelper->getCurrentMode();
                    $moveToCartText = __('shop::app.customer.account.wishlist.move-to-cart');
                @endphp

                @include ('shop::products.list.card', [
                    'checkmode'         => true,
                    'moveToCart'        => true,
                    'addToCartForm'     => true,
                    'removeWishlist'    => true,
                    'reloadPage'        => true,
                    'itemId'            => $item->id,
                    'product'           => $item->product,
                    'btnText'           => $moveToCartText,
                    'addToCartBtnClass' => 'small-padding',
                ])
            @endforeach

            <div class="bottom-toolbar">
                {{ $items->links()  }}
            </div>
        @else
            <div class="empty">
                {{ __('customer::app.wishlist.empty') }}
            </div>
        @endif
    </div>

    {!! view_render_event('bagisto.shop.customers.account.wishlist.list.after', ['wishlist' => $items]) !!}
@endsection

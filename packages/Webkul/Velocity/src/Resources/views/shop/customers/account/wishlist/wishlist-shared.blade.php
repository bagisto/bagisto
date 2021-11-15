@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.account.wishlist.page-title') }}
@endsection

@section('content-wrapper')
    <div class="container p-5">
        <div class="row">
            <div class="col-12">
                <div class="wishlist-container">

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="wishlist-container">
                    @if ($wishlistItems->count())
                        <h2 class="text-center">
                            {{ $customer->name }}'s Wishlist
                        </h2>

                        @foreach ($wishlistItems as $wishlistItem)
                            @include ('shop::customers.account.wishlist.wishlist-products', ['wishlistItem' => $wishlistItem])
                        @endforeach
                    @else
                        <div class="empty">
                            {{ __('customer::app.wishlist.empty') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
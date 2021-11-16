@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.account.wishlist.customer-name', ['name' => $customer->name]) }}
@endsection

@section('content-wrapper')
    <div class="container p-5">
        <div class="row">
            <div class="col-12">
                <div class="wishlist-container">
                    <h2 class="text-center">
                        {{ __('shop::app.customer.account.wishlist.customer-name', ['name' => $customer->name]) }}
                    </h2>

                    @foreach ($items as $item)
                        @include('shop::customers.account.wishlist.wishlist-product', ['item' => $item])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
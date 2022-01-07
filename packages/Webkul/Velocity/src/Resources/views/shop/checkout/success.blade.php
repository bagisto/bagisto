@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.checkout.success.title') }}
@stop

@section('content-wrapper')
    <div class="container">
        <div class="order-success-content row col-12 offset-1">
            <h1 class="row col-12">{{ __('shop::app.checkout.success.thanks') }}</h1>

            <p class="row col-12">
                {{ __('shop::app.checkout.success.order-id-info', ['order_id' => $order->increment_id]) }}
            </p>

            <p class="row col-12">
                {{ __('shop::app.checkout.success.info') }}
            </p>

            {{ view_render_event('bagisto.shop.checkout.continue-shopping.before', ['order' => $order]) }}

            <div class="mt15 row-col-12">
                <a href="{{ route('shop.home.index') }}" class="theme-btn remove-decoration">
                    {{ __('shop::app.checkout.cart.continue-shopping') }}
                </a>
                @guest('customer')
                    <a href="{{ route('customer.register.index') }}" class="theme-btn registration-btn remove-decoration">
                        {{ __('shop::app.checkout.cart.continue-registration') }}
                    </a>
                @endguest
            </div>

            {{ view_render_event('bagisto.shop.checkout.continue-shopping.after', ['order' => $order]) }}

        </div>
    </div>
@endsection

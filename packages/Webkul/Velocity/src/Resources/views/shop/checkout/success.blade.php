@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.checkout.success.title') }}
@stop

@section('content-wrapper')
    <div class="container">
        <div class="order-success-content row col-12 offset-1">
            <h1 class="row col-12">{{ __('shop::app.checkout.success.thanks') }}</h1>

            <p class="row col-12">
                @if (auth()->guard('customer')->user())
                    {!! 
                        __('shop::app.checkout.success.order-id-info', [
                            'order_id' => '<a href="' . route('shop.customer.orders.view', $order->id) . '">' . $order->increment_id . '</a>'
                        ])
                    !!}
                @else
                    {{ __('shop::app.checkout.success.order-id-info', ['order_id' => $order->increment_id]) }}
                @endif
            </p>

            <p class="row col-12">
                {{ __('shop::app.checkout.success.info') }}
            </p>

            {{ view_render_event('bagisto.shop.checkout.continue-shopping.before', ['order' => $order]) }}
                <div class="row col-12 mt15">
                    <span class="mb30 mr10">
                        <a href="{{ route('shop.home.index') }}" class="theme-btn remove-decoration">
                            {{ __('shop::app.checkout.cart.continue-shopping') }}
                        </a>
                    </span>

                    @guest('customer')
                        <span class="">
                            <a href="{{ route('shop.customer.register.index') }}" class="theme-btn remove-decoration">
                                {{ __('shop::app.checkout.cart.continue-registration') }}
                            </a>
                        </span>
                    @endguest
                </div>
            {{ view_render_event('bagisto.shop.checkout.continue-shopping.after', ['order' => $order]) }}
        </div>
    </div>
@endsection

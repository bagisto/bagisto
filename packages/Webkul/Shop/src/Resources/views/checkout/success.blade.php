@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.checkout.success.title') }}
@stop

@section('content-wrapper')

    <div class="order-success-content" style="min-height: 300px;">
        <h1>{{ __('shop::app.checkout.success.thanks') }}</h1>

        <p>
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

        <p>{{ __('shop::app.checkout.success.info') }}</p>

        {{ view_render_event('bagisto.shop.checkout.continue-shopping.before', ['order' => $order]) }}

        <div class="misc-controls">
            <a style="display: inline-block" href="{{ route('shop.home.index') }}" class="btn btn-lg btn-primary">
                {{ __('shop::app.checkout.cart.continue-shopping') }}
            </a>
        </div>
        
        {{ view_render_event('bagisto.shop.checkout.continue-shopping.after', ['order' => $order]) }}
        
    </div>
@endsection

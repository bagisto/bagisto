<div class="order-summary">
    <h3>{{ __('shop::app.checkout.total.order-summary') }}</h3>

    <div class="item-detail">
        <label>
            <span id="total_qty">{{ intval($cart->items_qty) }}</span>
            {{ __('shop::app.checkout.total.sub-total') }}
            {{ __('shop::app.checkout.total.price') }}
        </label>
        <label class="right">{{ core()->currency($cart->base_sub_total) }}</label>
    </div>

    @if ($cart->selected_shipping_rate)
        <div class="item-detail">
            <label>{{ __('shop::app.checkout.total.delivery-charges') }}</label>
            <label class="right">{{ core()->currency($cart->selected_shipping_rate->base_price) }}</label>
        </div>
    @endif

    @if ($cart->base_tax_total)
        <div class="item-detail">
            <label>{{ __('shop::app.checkout.total.tax') }}</label>
            <label class="right">{{ core()->currency($cart->base_tax_total) }}</label>
        </div>
    @endif

    <div class="payble-amount">
        <label>{{ __('shop::app.checkout.total.grand-total') }}</label>
        <label class="right">{{ core()->currency($cart->base_grand_total) }}</label>
    </div>

    <div class="discount">
        <div class="discount-group">
            @inject('cart_rule', 'Webkul\Discount\Helpers\Discount')

            @if (! request()->is('checkout/cart'))
                <form class="coupon-form" method="post" @submit.prevent="onSubmit" v-if="!discounted">
                    <div class="control-group mt-20" :class="[errors.has('code') ? 'has-error' : '']">
                        <input v-model="code" type="text" class="control" value="" name="code" placeholder="Enter Coupon Code" v-on:change="codeChange" style="width: 100%">
                        <span class="control-error" v-if="errors.has('code')">
                            @{{ errors.first('code') }}
                        </span>
                        <span class="coupon-message mt-5" style="display: block; color: #ff5656; margin-bottom: 5px;" v-if="message != 'Success' && message != 'success'">@{{ message }}</span>

                        <span class="coupon-message mt-5" style="display: block; margin-bottom: 5px;" v-if="message == 'Success' || message == 'success'">@{{ message }}</span>

                        <button class="btn btn-lg btn-black">{{ __('shop::app.checkout.onepage.apply-coupon') }}</button>
                    </div>
                </form>

                @if(isset($rule))
                    {{-- <div class="discounted">
                        <div class="mt-15 mb-10">
                            {{ $rule['rule']->name }} {{ __('shop::app.checkout.onepage.applied') }}
                        </div>

                        <span class="payble-amount row mt-10">
                            @if ($rule['impact']['amount_given'])
                                <label style="float: left;">{{ __('shop::app.checkout.onepage.amt-payable') }}</label>

                                <label style="float: right;">{{ core()->currency($cart->tax_total + $rule['impact']['amount']) }}</label>
                            @else
                                <label style="float: left;">{{ __('shop::app.checkout.onepage.got') }}</label>
                                <label style="float: right;">{{ $rule['impact']['amount'] }} {{ __('shop::app.checkout.onepage.got') }}</label>
                            @endif
                        </span>
                    </div> --}}
                @else
                    {{-- {{dd('2')}} --}}
                    {{-- <div class="discounted" v-if="discounted">
                        <div class="mt-15 mb-10">
                            @{{ code }} {{ __('shop::app.checkout.onepage.applied') }}
                        </div>

                        <span class="payble-amount row mt-10">
                            <label style="float: left;">{{ __('shop::app.checkout.onepage.amt-payable') }}</label>

                            <label style="float: right;">@{{ discount.amount }}</label>
                        </span>
                    </div>

                    <div class="discounted" v-if="!discounted">
                        <div class="mt-15 mb-10">
                            <b>{{ __('shop::app.checkout.onepage.coupon-used') }}</b>
                        </div>

                        <span class="row mb-10">
                            <label style="float: left;">@{{ code }}</label>
                            <label style="float: right;">@{{ discount.amount_given }}</label>
                        </span>

                        <span class="payble-amount row mt-10">
                            <label style="float: left;">{{ __('shop::app.checkout.onepage.amt-payable') }}</label>
                            <label style="float: right;">@{{ discount.amount }}</label>
                        </span>
                    </div> --}}
                @endif
            @endif
        </div>
    </div>
</div>
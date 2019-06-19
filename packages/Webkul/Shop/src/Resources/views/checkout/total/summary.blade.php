<div class="order-summary">
    <h3>{{ __('shop::app.checkout.total.order-summary') }}</h3>

    <div class="item-detail">
        <label>
            {{ intval($cart->items_qty) }}
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


    <div class="item-detail" id="discount-detail" @if ($cart->discount_amount && $cart->discount_amount > 0) style="display: block;" @else style="display: none;" @endif>
        <label>
            <b>{{ __('shop::app.checkout.total.disc-amount') }}</b>
        </label>
        <label class="right">
            <b id="discount-detail-discount-amount">
                {{ core()->currency($cart->discount_amount) }}
            </b>
        </label>
    </div>


    <div class="payable-amount" id="grand-total-detail">
        <label>{{ __('shop::app.checkout.total.grand-total') }}</label>
        <label class="right" id="grand-total-amount-detail">
            {{ core()->currency($cart->base_grand_total) }}
        </label>
    </div>

    <div @if (! request()->is('checkout/cart')) v-if="parseInt(discount)" @endif>
        @if (! request()->is('checkout/cart'))
            @if (! $cart->coupon_code)
                <div class="discount">
                    <div class="discount-group">
                        <form class="coupon-form" method="post" @submit.prevent="onSubmit">
                            <div class="control-group mt-20" :class="[errors.has('code') ? 'has-error' : '']">
                                <input type="text" class="control" value="" v-model="coupon_code" name="code" placeholder="Enter Coupon Code" v-validate="'required'" style="width: 100%">
                            </div>

                            <span class="control-error" v-if="error_message.length > 0">@{{ error_message }}</span>

                            <button class="btn btn-lg btn-black">{{ __('shop::app.checkout.onepage.apply-coupon') }}</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="discount-details-group">
                    <div class="item-detail">
                        <label>{{ __('shop::app.checkout.total.coupon-applied') }}</label>

                        <label class="right" style="display: inline-flex; align-items: center;">
                            <b>{{ $cart->coupon_code }}</b>

                            <span class="icon cross-icon" title="{{ __('shop::app.checkout.total.remove-coupon') }}" v-on:click="removeCoupon"></span>
                        </label>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
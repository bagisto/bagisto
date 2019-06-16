<div class="order-summary" id="summary-template">
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

    <div @if (! request()->is('checkout/cart')) v-if="hide_discount" @endif>
        @if (! request()->is('checkout/cart'))
            <div class="discount">
                @{{ coupon_used }}
                <div class="discount-group" v-if="! coupon_used">
                    <form class="coupon-form" method="post" @submit.prevent="onSubmit">
                        <div class="control-group mt-20" :class="[errors.has('code') ? 'has-error' : '']">
                            <input type="text" class="control" value="" v-model="code" name="code" placeholder="Enter Coupon Code" v-validate="'required'" style="width: 100%">
                        </div>

                        <button class="btn btn-lg btn-black">{{ __('shop::app.checkout.onepage.apply-coupon') }}</button>
                    </form>
                </div>
            </div>

            <div class="discount-details-group" v-if="coupon_used">
                <div class="item-detail">
                    <label>{{ __('shop::app.checkout.total.coupon-applied') }}</label>

                    <label class="right" style="display: inline-flex; align-items: center;">
                        <b>@{{ code }}</b>

                        <span class="icon cross-icon" title="{{ __('shop::app.checkout.total.remove-coupon') }}" v-on:click="removeCoupon"></span>
                    </label>
                </div>
            </div>
        @endif
    </div>
</div>
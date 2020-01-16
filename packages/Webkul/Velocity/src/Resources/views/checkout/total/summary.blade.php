<div class="order-summary fs16">
    <h3 class="fw6">{{ __('velocity::app.checkout.cart.cart-summary') }}</h3>

    <div class="row">
        <span class="col-8">{{ __('velocity::app.checkout.sub-total') }}</span>
        <span class="col-4 text-right">{{ core()->currency($cart->base_sub_total) }}</span>
    </div>

    @if ($cart->selected_shipping_rate)
        <div class="row">
            <span class="col-8">{{ __('shop::app.checkout.total.delivery-charges') }}</span>
            <span class="col-4 text-right">{{ core()->currency($cart->selected_shipping_rate->base_price) }}</span>
        </div>
    @endif

    @if ($cart->base_tax_total)
        <div class="row">
            <span class="col-8">{{ __('shop::app.checkout.total.tax') }}</span>
            <span class="col-4 text-right">{{ core()->currency($cart->base_tax_total) }}</span>
        </div>
    @endif

    @if (
        $cart->base_discount_amount
        && $cart->base_discount_amount > 0
    )
        <div
            id="discount-detail"
            class="row">

            <span class="col-8">{{ __('shop::app.checkout.total.disc-amount') }}</span>
            <span class="col-4 text-right">
                {{ core()->currency($cart->base_discount_amount) }}
            </span>
        </div>
    @endif

    <div class="payable-amount row" id="grand-total-detail">
        <span class="col-8">{{ __('shop::app.checkout.total.grand-total') }}</span>
        <span class="col-4 text-right fw6" id="grand-total-amount-detail">
            {{ core()->currency($cart->base_grand_total) }}
        </span>
    </div>

    <div @if (! request()->is('checkout/cart')) v-if="parseInt(discount)" @endif>
        @if (! request()->is('checkout/cart'))
            @if (! $cart->coupon_code)
                <div class="discount">
                    <div class="discount-group">
                        <form
                            class="custom-form"
                            method="post"
                            @submit.prevent="onSubmit">

                            <div
                                class="control-group mt-20"
                                :class="[errors.has('code') ? 'has-error' : '']">

                                <input
                                    type="text"
                                    class="control"
                                    v-model="coupon_code"
                                    name="code"
                                    placeholder="Enter Coupon Code"
                                    v-validate="'required'"
                                    @change="changeCoupon" />
                            </div>

                            <div
                                class="control-error mb-10"
                                v-if="error_message != null">
                                * @{{ error_message }}
                            </div>

                            <button
                                class="theme-btn show full-width mt15"
                                :disabled="couponChanged">
                                {{ __('shop::app.checkout.onepage.apply-coupon') }}
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="row">
                    <span class="col-8">{{ __('shop::app.checkout.total.coupon-applied') }}</span>

                    <span class="col-4 text-right">
                        <b>{{ $cart->coupon_code }}</b>

                        <span
                            class="icon cross-icon"
                            title="{{ __('shop::app.checkout.total.remove-coupon') }}"
                            v-on:click="removeCoupon">
                        </span>
                    </span>
                </div>
            @endif
        @endif
    </div>

    <div class="row">
        <a
            href="{{ route('shop.checkout.onepage.index') }}"
            class="theme-btn text-uppercase col-12 remove-decoration fw6 text-center">
            {{ __('velocity::app.checkout.proceed') }}
        </a>
    </div>
</div>
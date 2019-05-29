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
        <div class="dicount-group">
            @inject('cart_rule', 'Webkul\Discount\Helpers\Discount')

            @if (! request()->is('checkout/cart'))
                <form class="coupon-form" method="post" @submit.prevent="onSubmit" v-if="discounted == false">
                    <div class="control-group mt-20">
                        <input v-model="code" type="text" class="control" value="" name="code" placeholder="Enter Coupon Code" v-on:change="codeChange" style="width: 100%">

                        <span class="coupon-message mt-5" style="display: block; color: #ff5656; margin-bottom: 5px;" v-if="message != 'Success' && message != 'success'">@{{ message }}</span>

                        <span class="coupon-message mt-5" style="display: block; margin-bottom: 5px;" v-if="message == 'Success' || message == 'success'">@{{ message }}</span>

                        <button class="btn btn-lg btn-black">Apply Coupon</button>
                    </div>
                </form>

                <div class="discounted" v-if="discounted">
                    <div class="mt-15 mb-10">
                        <b>Coupon used</b>
                    </div>

                    <span class="row mb-10">
                        <label style="float: left;">@{{ code }}</label>
                        <label style="float: right;">@{{ discount.amount_given }}</label>
                    </span>

                    <span class="horizontal-rule"></span>

                    <span class="row mt-10">
                        <label style="float: left;">Amount Payable</label>
                        <label style="float: right;">@{{ discount.amount }}</label>
                    </span>
                </div>
            @endif
        </div>
    </div>
</div>
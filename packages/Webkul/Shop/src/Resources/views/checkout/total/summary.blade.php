<div class="order-summary">
    <h3>{{ __('shop::app.checkout.total.order-summary') }}</h3>

    <div class="item-detail">
        <label>
            @if (! request()->is('checkout/cart'))
                <div v-if="! qtyRevealed">{{ intval($cart->items_qty) }}</div>
                <div v-if="qtyRevealed">{{ intval($cart->items_qty) }} @{{ + amount_given (free) }}</div>
            @else
                {{ intval($cart->items_qty) }}
            @endif
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
                <form class="coupon-form" method="post" @submit.prevent="onSubmit">
                    <div class="control-group mt-20">
                        <input v-model="code" type="text" class="control" value="" name="code" placeholder="Enter Coupon Code" v-on:change="codeChange" style="width: 100%">

                        <span class="coupon-message mt-5" style="display: block; color: #ff5656; margin-bottom: 5px;" v-if="message != 'Success' && message != 'success'">@{{ message }}</span>

                        <span class="coupon-message mt-5" style="display: block; margin-bottom: 5px;" v-if="message == 'Success' || message == 'success'">@{{ message }}</span>

                        <button class="btn btn-lg btn-primary">Apply Coupon</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

<form data-vv-scope="shipping-form">
    <div class="form-container">
        <div class="form-header mb-30">
            <span class="checkout-step-heading">{{ __('shop::app.checkout.onepage.shipping-method') }}</span>
        </div>

        <div class="shipping-methods">

            <div class="control-group" :class="[errors.has('shipping-form.shipping_method') ? 'has-error' : '']">

                @foreach ($shippingRateGroups as $rateGroup)
                    {!! view_render_event('bagisto.shop.checkout.shipping-method.before', ['rateGroup' => $rateGroup]) !!}

                    @foreach ($rateGroup['rates'] as $rate)
                        <div class="checkout-method-group mb-20">
                            <div class="line-one">
                                <label class="radio-container">
                                    <input v-validate="'required'" type="radio" id="{{ $rate->method }}" name="shipping_method" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.shipping-method') }}&quot;" value="{{ $rate->method }}" v-model="selected_shipping_method" @change="methodSelected()">
                                    <span class="checkmark"></span>
                                </label>
                                {{-- <label class="radio-view" for="{{ $rate->method }}"></label> --}}
                                <b class="ship-rate method-label">{{ core()->currency($rate->base_price) }}</b>
                            </div>

                            <div class="line-two mt-5">
                                <div class="method-summary">
                                    <b>{{ $rate->method_title }}</b> - {{ $rate->method_description }}
                                </div>
                            </div>
                        </div>

                    @endforeach

                    {!! view_render_event('bagisto.shop.checkout.shipping-method.after', ['rateGroup' => $rateGroup]) !!}

                @endforeach

                <span class="control-error" v-if="errors.has('shipping-form.shipping_method')">
                    @{{ errors.first('shipping-form.shipping_method') }}
                </span>
            </div>
        </div>
    </div>
</form>
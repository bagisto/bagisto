<form data-vv-scope="shipping-form">
    <div class="form-container">
        <div class="form-header">
            <h1>{{ __('shop::app.checkout.onepage.shipping-method') }}</h1>
        </div>

        <div class="shipping-methods">

            <div class="control-group" :class="[errors.has('shipping-form.shipping_method') ? 'has-error' : '']">

                @foreach ($shippingRateGroups as $rateGroup)
                    <h4 for="">{{ $rateGroup['carrier_title'] }}</h4>

                    @foreach ($rateGroup['rates'] as $rate)
                        <span class="radio" >
                            <input v-validate="'required'" type="radio" id="{{ $rate->method }}" name="shipping_method" value="{{ $rate->method }}" v-model="selected_shipping_method">
                            <label class="radio-view" for="{{ $rate->method }}"></label>
                            {{ $rate->method_title }}
                            <b>{{ core()->currency($rate->price) }}</b>
                        </span>
                    @endforeach
                        
                @endforeach

                <span class="control-error" v-if="errors.has('shipping-form.shipping_method')">
                    @{{ errors.first('shipping-form.shipping_method') }}
                </span>

            </div>

        </div>
    </div>
</form>
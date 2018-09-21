<form data-vv-scope="shipping-form">
    <div class="form-container">
        <div class="form-header">
            <h1>{{ __('shop::app.checkout.onepage.shipping-method') }}</h1>
        </div>

        <div class="shipping-methods">

            <div class="control-group" v-for='(shipping_method, index) in shipping_methods' :class="[errors.has('shipping-form.shipping_method') ? 'has-error' : '']">
                <h4 for="">@{{ shipping_method.carrier_title }}</h4>

                <span class="radio"  v-for='(rate, index) in shipping_method.rates'>
                    <input v-validate="'required'" type="radio" :id="rate.method" name="shipping_method" :value="rate.method">
                    <label class="radio-view" :for="rate.method"></label>
                    @{{ rate.method_title }}
                    <b>@{{ rate.price_formated }}</b>
                </span>

                <span class="control-error" v-if="errors.has('shipping-form.shipping_method')">
                    @{{ errors.first('shipping-form.shipping_method') }}
                </span>
            </div>

        </div>
    </div>
</form>
<form data-vv-scope="payment-form" class="payment-form">
    <div class="form-container">
        <div class="form-header mb-30">
            <h3 class="fw6 display-inbl">
                3. {{ __('shop::app.checkout.onepage.payment-methods') }}
            </h3>
        </div>

        <div class="payment-methods">
            @foreach ($paymentMethods as $payment)

                {!! view_render_event('bagisto.shop.checkout.payment-method.before', ['payment' => $payment]) !!}

                <div class="row col-12">
                    <div>
                        <label class="radio-container">
                            <input
                                v-validate="'required'"
                                type="radio"
                                id="{{ $payment['method'] }}"
                                name="payment[method]"
                                value="{{ $payment['method'] }}"
                                v-model="payment.method"
                                @change="methodSelected()"
                                data-vv-as="&quot;{{ __('shop::app.checkout.onepage.payment-method') }}&quot;" />

                            <span class="checkmark"></span>
                        </label>
                    </div>

                    <div class="pl30">
                        <div class="row">
                            <span class="payment-method method-label">
                                <b>{{ $payment['method_title'] }}</b>
                            </span>
                        </div>

                        <div class="row">
                            <span class="method-summary">{{ __($payment['description']) }}</span>
                        </div>
                    </div>
                </div>

                {!! view_render_event('bagisto.shop.checkout.payment-method.after', ['payment' => $payment]) !!}

            @endforeach

            <span class="control-error" v-if="errors.has('payment-form.payment[method]')">
                @{{ errors.first('payment-form.payment[method]') }}
            </span>
        </div>
    </div>
</form>
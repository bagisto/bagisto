<form data-vv-scope="payment-form">
    <div class="form-container" id="">
        <div class="form-header mb-30" id="">
            <span class="checkout-step-heading">{{ __('shop::app.checkout.onepage.payment-methods') }}</span>
        </div>

        <div class="payment-methods" id="">

            <div class="control-group" :class="[errors.has('payment-form.payment[method]') ? 'has-error' : '']" id="">

                @foreach ($paymentMethods as $payment)

                    {!! view_render_event('bagisto.shop.checkout.payment-method.before', ['payment' => $payment]) !!}

                    <div class="checkout-method-group mb-20" id="">
                        <div class="line-one" id="">
                            <label class="radio-container">
                                <input v-validate="'required'" type="radio" id="{{ $payment['method'] }}" name="payment[method]" value="{{ $payment['method'] }}" v-model="payment.method" @change="methodSelected()" data-vv-as="&quot;{{ __('shop::app.checkout.onepage.payment-method') }}&quot;">

                                <span class="checkmark"></span>

                                {{-- <label class="radio-view" for="{{ $payment['method'] }}"></label> --}}
                            </label>

                            <span class="payment-method method-label">
                                <b>{{ $payment['method_title'] }}</b>
                            </span>
                        </div>

                        <div class="line-two mt-5" id="">
                            <span class="method-summary">{{ $payment['description'] }}</span>
                        </div>
                    </div>

                    {!! view_render_event('bagisto.shop.checkout.payment-method.after', ['payment' => $payment]) !!}

                @endforeach

                <span class="control-error" v-if="errors.has('payment-form.payment[method]')">
                    @{{ errors.first('payment-form.payment[method]') }}
                </span>

            </div>
        </div>
    </div>
</form>
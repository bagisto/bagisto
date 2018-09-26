<form data-vv-scope="payment-form">
    <div class="form-container">
        <div class="form-header">
            <h1>{{ __('shop::app.checkout.onepage.payment-information') }}</h1>
        </div>

        <div class="payment-methods">

            <div class="control-group" :class="[errors.has('payment-form.payment[method]') ? 'has-error' : '']">

                @foreach ($paymentMethods as $payment)

                    <span class="radio" >
                        <input v-validate="'required'" type="radio" id="{{ $payment['method'] }}" name="payment[method]" value="{{ $payment['method'] }}" v-model="payment.method" @change="methodSelected()">
                        <label class="radio-view" for="{{ $payment['method'] }}"></label>
                        {{ $payment['title'] }}
                    </span>
                    
                    <span class="control-info">{{ $payment['description'] }}</span>

                @endforeach

                <span class="control-error" v-if="errors.has('payment-form.payment[method]')">
                    @{{ errors.first('payment-form.payment[method]') }}
                </span>

            </div>
        </div>
    </div>
</form>
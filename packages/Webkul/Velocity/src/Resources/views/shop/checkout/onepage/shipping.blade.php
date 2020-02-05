<form data-vv-scope="shipping-form" class="shipping-form">
    <div class="form-container">
        <accordian :title="'{{ __('shop::app.checkout.onepage.shipping-method') }}'" :active="true">
            <div class="form-header" slot="header">
                <h3 class="fw6 display-inbl">
                 {{ __('shop::app.checkout.onepage.shipping-method') }}
                </h3>
                <i class="rango-arrow"></i>
            </div>

            <div :class="`shipping-methods ${errors.has('shipping-form.shipping_method') ? 'has-error' : ''}`" slot="body">

                @foreach ($shippingRateGroups as $rateGroup)

                    {!! view_render_event('bagisto.shop.checkout.shipping-method.before', ['rateGroup' => $rateGroup]) !!}
                        @foreach ($rateGroup['rates'] as $rate)
                            <div class="row col-12">
                                <div>
                                    <label class="radio-container">
                                        <input
                                            v-validate="'required'"
                                            type="radio"
                                            id="{{ $rate->method }}"
                                            name="shipping_method"
                                            data-vv-as="&quot;{{ __('shop::app.checkout.onepage.shipping-method') }}&quot;"
                                            value="{{ $rate->method }}"
                                            v-model="selected_shipping_method"
                                            @change="methodSelected()" />

                                        <span class="checkmark"></span>
                                    </label>
                                </div>

                                <div class="pl30">
                                    <div class="row">
                                        <b>{{ core()->currency($rate->base_price) }}</b>
                                    </div>

                                    <div class="row">
                                        <b>{{ $rate->method_title }}</b> - {{ __($rate->method_description) }}
                                    </div>
                                </div>
                            </div>

                        @endforeach

                    {!! view_render_event('bagisto.shop.checkout.shipping-method.after', ['rateGroup' => $rateGroup]) !!}

                @endforeach

                <span
                    class="control-error"
                    v-if="errors.has('shipping-form.shipping_method')">

                    @{{ errors.first('shipping-form.shipping_method') }}
                </span>
            </div>
        </accordian>
    </div>
</form>
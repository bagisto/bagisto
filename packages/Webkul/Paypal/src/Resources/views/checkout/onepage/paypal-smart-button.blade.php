@if (
    request()->routeIs('shop.checkout.onepage.index')
    && (bool) core()->getConfigData('sales.paymentmethods.paypal_smart_button.active')
)
    @php
        $clientId = core()->getConfigData('sales.paymentmethods.paypal_smart_button.client_id');
        $acceptedCurrency = core()->getConfigData('sales.paymentmethods.paypal_smart_button.accepted_currencies');
    @endphp

    @pushOnce('scripts')
        <script type="text/x-template" id="paypal-smart-button-template">
            <div class="paypal-button-container"></div>
        </script>

        <script type="module">
            let messages = {
                universalError: "{{ __('paypal::app.error.universal-error') }}",
                sdkValidationError: "{{ __('paypal::app.error.sdk-validation-error') }}",
                authorizationError: "{{ __('paypal::app.error.authorization-error') }}"
            };

            app.component('paypal-smart-button', {
                template: '#paypal-smart-button-template',

                props: ['selectedPaymentMethod'],

                created() {
                    let script = document.createElement('script');

                    script.src = 'https://www.paypal.com/sdk/js?client-id={{ $clientId }}&currency={{ $acceptedCurrency }}';

                    script.async = true;

                    script.setAttribute('data-partner-attribution-id', 'Bagisto_Cart');

                    document.body.appendChild(script);
                },

                watch: {
                    selectedPaymentMethod() {
                        if (this.selectedPaymentMethod.method != 'paypal_smart_button') {
                            let paypalButton = document.querySelector('.paypal-buttons');

                            if (paypalButton) {
                                paypalButton.remove();
                            }

                            return;
                        }

                        let options = {
                            style: {
                                layout:  'vertical',
                                shape:   'rect',
                            },

                            authorizationFailed: false,

                            enableStandardCardFields: false,

                            alertBox(message) {
                                alert(message);
                            },

                            createOrder(data, actions) {
                                return this.$axios.get("{{ route('paypal.smart-button.create-order') }}")
                                    .then(function(response) {
                                        return response.data.result;
                                    })
                                    .then(function(orderData) {
                                        return orderData.id;
                                    })
                                    .catch(function (error) {
                                        if (error.response.data.error === 'invalid_client') {
                                            options.authorizationFailed = true;
                                            
                                            options.alertBox(messages.authorizationError);
                                        }

                                        return error;
                                    });
                            },

                            onApprove(data, actions) {
                                this.$axios.post("{{ route('paypal.smart-button.capture-order') }}", {
                                    _token: "{{ csrf_token() }}",
                                    orderData: data
                                })
                                .then(function(response) {
                                    if (response.data.success) {
                                        if (response.data.redirect_url) {
                                            window.location.href = response.data.redirect_url;
                                        } else {
                                            window.location.href = "{{ route('shop.checkout.onepage.success') }}";
                                        }
                                    }
                                })
                                .catch((error) => window.location.href = "{{ route('shop.checkout.cart.index') }}")
                            },

                            onError(error) {
                                if (! options.authorizationFailed) {
                                    alert(messages.universalError)
                                }
                            }
                        };

                        if (typeof paypal == 'undefined') {
                            alert(messages.sdkValidationError);

                            return;
                        }

                        paypal.Buttons(options).render('.paypal-button-container');
                    }
                },
            });
        </script>
    @endPushOnce
@endif

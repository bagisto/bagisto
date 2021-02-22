@if (request()->route()->getName() == 'shop.checkout.onepage.index')

    @php
        $clientId = core()->getConfigData('sales.paymentmethods.paypal_smart_button.client_id');
        $acceptedCurrency = core()->getConfigData('sales.paymentmethods.paypal_smart_button.accepted_currencies');
    @endphp

    <script src="https://www.paypal.com/sdk/js?client-id={{ $clientId }}&currency={{ $acceptedCurrency }}" data-partner-attribution-id="Bagisto_Cart"></script>

    <style>
        .component-frame.visible {
            z-index: 1 !important;
        }
    </style>

    <script>
        let messages = {
            universalError: "Something went wrong!",
            sdkValidationError: "SDK Validation Error: 'Client ID not recognized for either production or sandbox: {{ core()->getConfigData('sales.paymentmethods.paypal_smart_button.client_id') }}'",
            authorizationError: "Client ID and Client Secret should be valid!"
        };

        window.onload = (function() {
            eventBus.$on('after-payment-method-selected', function(payment) {
                if (payment.method != 'paypal_smart_button') {
                    $('.paypal-buttons').remove();

                    return;
                }

                if (typeof paypal == 'undefined') {
                    options.alertBox(messages.sdkValidationError);

                    return;
                }

                let options = {
                    style: {
                        layout:  'vertical',
                        shape:   'rect',
                    },

                    enableStandardCardFields: false,

                    alertBox: function (message) {
                        window.flashMessages = [{'type': 'alert-error', 'message': message }];
                        window.flashMessages.alertMessage = message;
                        app.addFlashMessages();
                    },

                    createOrder: function(data, actions) {
                        return window.axios.get("{{ route('paypal.smart-button.create-order') }}")
                            .then(function(response) {
                                return response.data.result;
                            })
                            .then(function(orderData) {
                                return orderData.id;
                            })
                            .catch(function (error) {
                                if (error.response.data.error === 'invalid_client') {
                                    options.alertBox(messages.authorizationError);
                                }

                                options.alertBox(messages.universalError);
                            });
                    },

                    onApprove: function(data, actions) {
                        app.showLoader();

                        window.axios.post("{{ route('paypal.smart-button.capture-order') }}", {
                            _token: "{{ csrf_token() }}",
                            orderData: data
                        })
                        .then(function(response) {
                            if (response.data.success) {
                                if (response.data.redirect_url) {
                                    window.location.href = response.data.redirect_url;
                                } else {
                                    window.location.href = "{{ route('shop.checkout.success') }}";
                                }
                            }

                            app.hideLoader()
                        })
                        .catch(function (error) {
                            window.location.href = "{{ route('shop.checkout.cart.index') }}";
                        })
                    }
                };

                paypal.Buttons(options).render('.paypal-button-container');
            });
        });
    </script>

@endif
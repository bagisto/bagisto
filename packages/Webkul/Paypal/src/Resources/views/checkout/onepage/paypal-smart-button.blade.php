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
        window.onload = (function() {
            eventBus.$on('after-payment-method-selected', function(payment) {
                if (payment.method != 'paypal_smart_button') {
                    $('.paypal-buttons').remove();

                    return;
                }

                if (typeof paypal == 'undefined') {
                    window.flashMessages = [{'type': 'alert-error', 'message': "SDK Validation error: 'client-id not recognized for either production or sandbox: {{core()->getConfigData('sales.paymentmethods.paypal_smart_button.client_id')}}'" }];

                    window.flashMessages.alertMessage = "SDK Validation error: 'client-id not recognized for either production or sandbox: {{core()->getConfigData('sales.paymentmethods.paypal_smart_button.client_id')}}'";

                    app.addFlashMessages();

                    return;
                }

                let options = {
                    style: {
                        layout:  'vertical',
                        shape:   'rect',
                    },

                    enableStandardCardFields: false,

                    createOrder: function(data, actions) {
                        return window.axios.get("{{ route('paypal.smart-button.create-order') }}")
                            .then(function(response) {
                                return response.data.result;
                            })
                            .then(function(orderData) {
                                return orderData.id;
                            })
                            .catch(function (error) {})
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
                    },

                    onCancel: function (data) {
                        console.log('Canceled payment...');
                    },

                    onError: function (err) {
                        window.flashMessages = [{'type': 'alert-error', 'message': err }];

                        window.flashMessages.alertMessage = err;

                        app.addFlashMessages();
                    }
                };

                paypal.Buttons(options).render('.paypal-button-container');
            });
        });
    </script>

@endif
@if (request()->route()->getName() == 'shop.checkout.onepage.index')
<script src="https://www.paypal.com/sdk/js?client-id={{core()->getConfigData('sales.paymentmethods.paypal_smart_button.client_id')}}" data-partner-attribution-id="Bagisto_Cart"></script>

<style>
    .component-frame.visible {
        z-index: 1 !important;
    }
</style>

<script>
    if (typeof paypal == 'undefined') {
        window.flashMessages = [{'type': 'alert-error', 'message': "SDK Validation error: 'client-id not recognized for either production or sandbox: {{core()->getConfigData('sales.paymentmethods.paypal_smart_button.client_id')}}'" }];
    } else {
        window.onload = (function() {
            eventBus.$on('after-payment-method-selected', function(payment) {
                if (payment.method != 'paypal_smart_button') {
                    $('.paypal-buttons').remove();

                    return;
                }

                var options = {
                    style: {
                        layout:  'vertical',
                        shape:   'rect',
                    },

                    enableStandardCardFields: false,

                    createOrder: function(data, actions) {
                        return window.axios.get("{{ route('paypal.smart_button.details') }}")
                            .then(function(response) {
                                return actions.order.create(response.data);
                            })
                            .catch(function (error) {})
                    },

                    // Finalize the transaction
                    onApprove: function(data, actions) {
                        app.showLoader();

                        return actions.order.capture().then(function(details) {
                            return window.axios.post("{{ route('paypal.smart_button.save_order') }}", {
                                    '_token': "{{ csrf_token() }}",
                                    'data' : details
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
                        });
                    }
                };

                paypal.Buttons(options).render(".paypal-button-container");
            });
        });
    }
</script>
@endif
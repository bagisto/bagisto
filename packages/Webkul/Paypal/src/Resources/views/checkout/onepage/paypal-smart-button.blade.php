@if (request()->route()->getName() == 'shop.checkout.onepage.index')
<script src="https://www.paypal.com/sdk/js?client-id={{core()->getConfigData('sales.paymentmethods.paypal_smart_button.client_id')}}"></script>

<script>
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
</script>
@endif
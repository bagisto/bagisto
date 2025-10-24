<!DOCTYPE html>
<html lang="en">
    <head>
        <title>@lang('razorpay::app.configuration.checkout-title')</title>
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <!-- Add loading spinner styles -->
        <style>
            .loader-container {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                background-color: rgba(255, 255, 255, 0.9);
            }

            .loader {
                border: 5px solid #f3f3f3;
                border-radius: 50%;
                border-top: 5px solid #F37254;
                width: 50px;
                height: 50px;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            
            .error-message {
                display: none;
                text-align: center;
                color: #721c24;
                background-color: #f8d7da;
                border: 1px solid #f5c6cb;
                padding: 1rem;
                margin: 1rem;
                border-radius: 4px;
            }
        </style>
    </head>
    <body>
        <!-- Add loading spinner -->
        <div class="loader-container">
            <div class="loader"></div>
        </div>
        
        <!-- Add error message container -->
        <div id="error-message" class="error-message">
            @lang('razorpay::app.response.error-message')
        </div>

        <script>
            var options = {
                "key": "{{ $payment['key'] }}",
                "amount": "{{ $payment['amount'] }}",
                "currency": "INR",
                "name": @json(core()->getConfigData('sales.payment_methods.razorpay.merchant_name')),
                "description": @json(core()->getConfigData('sales.payment_methods.razorpay.merchant_desc')),
                "image": "{{ $payment['image'] }}",
                "order_id": "{{ $payment['order_id'] }}",
                "handler": function (response) {
                    sessionStorage.removeItem('razorpayInitiated');
                    // Show loading spinner during redirect
                    document.querySelector('.loader-container').style.display = 'flex';

                    window.location.href = "{{ route('razorpay.payment.success') }}?razorpay_payment_id=" + 
                        encodeURIComponent(response.razorpay_payment_id) + 
                        "&razorpay_order_id=" + encodeURIComponent(response.razorpay_order_id) + 
                        "&razorpay_signature=" + encodeURIComponent(response.razorpay_signature);
                },
                "prefill": {
                    "name": "{{ $payment['prefill']['name'] }}",
                    "email": "{{ $payment['prefill']['email'] }}",
                    "contact": "{{ $payment['prefill']['contact'] }}"
                },
                "notes": {
                    "shipping_address": "{{ $payment['notes']['shipping_address'] }}"
                },
                "theme": {
                    "color": "#F37254"
                },
                "modal": {
                    "ondismiss": function () {
                        sessionStorage.removeItem('razorpayInitiated');

                        document.querySelector('.loader-container').style.display = 'flex';

                        window.location.href = "{{ route('razorpay.payment.cancel') }}";
                    },
                    "escape": true,
                    "animation": true
                }
            };

            var rzp1 = new Razorpay(options);

            if (sessionStorage.getItem('razorpayInitiated')) {
                rzp1.open();
            }
            
            document.querySelector('.loader-container').style.display = 'none';

            window.onload = function () {
                sessionStorage.setItem('razorpayInitiated', true);

                rzp1.open();
            };
        </script>
    </body>
</html>
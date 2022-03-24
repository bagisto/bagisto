<?php
    use Webkul\Payment\Payment;
    
    if (! function_exists('payment')) {
        function payment()
        {
            return new Payment;
        }
    }

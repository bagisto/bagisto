<?php
    use Webkul\Shipping\Shipping;
    
    if (! function_exists('shipping')) {
        function shipping()
        {
            return new Shipping;
        }
    }
?>
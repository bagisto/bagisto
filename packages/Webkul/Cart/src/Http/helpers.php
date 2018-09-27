<?php
    use Webkul\Cart\Cart;
    
    if (! function_exists('cart')) {
        function cart()
        {
            return new Cart;
        }
    }
?>
<script>
    dataLayer.push({
        'pageCategory': 'Checkout',
        'pagetype': 'shop.checkout.success',
        'list': 'checkout'
    });

    dataLayer.push({
        'transactionId': {{ $order->id ?? 0 }},
        'transactionTotal': {{ $order->grand_total ?? 0 }},
        'transactionTaxAmount': {{ $order->tax_amount ?? 0 }}
    });
</script>
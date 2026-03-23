@php
    $omnibusHelper = app(\Webkul\Omnibus\Helpers\OmnibusHelper::class);
    $initialHtml = $omnibusHelper->getOmnibusPriceHtml($product);

    // Przygotowujemy mapę najniższych cen dla wszystkich wariantów
    $variantPrices = [];
    if ($product->type === 'configurable') {
        foreach ($product->variants as $variant) {
            $variantPrices[$variant->id] = $omnibusHelper->getLowestPriceFormatted($variant);
        }
    }
@endphp

<div id="omnibus-wrapper">
    {!! $initialHtml !!}
</div>

<script>
    window.omnibusPrices = @json($variantPrices);

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.eventBus !== 'undefined') {
            window.eventBus.$on('configurable-variant-update-price', function (data) {
                const variantId = data.variantId;
                const lowestPrice = window.omnibusPrices[variantId];
                const wrapper = document.getElementById('omnibus-wrapper');

                if (wrapper && lowestPrice) {
                    const priceElement = wrapper.querySelector('strong');
                    if (priceElement) {
                        priceElement.innerText = lowestPrice;
                    }
                    wrapper.style.display = 'block';
                } else if (wrapper) {
                    wrapper.style.display = 'none';
                }
            });
        }
    });
</script>
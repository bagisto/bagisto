<div id="omnibus-manager" data-variants='@json($variantPrices)' class="omnibus-container">
    <div id="omnibus-wrapper">
        @include('omnibus::shop.omnibus-price-info', ['formattedPrice' => $formattedPrice])
    </div>
</div>

@push('scripts')
    <script>
        (function () {
            function initOmnibus() {
                const container = document.getElementById('omnibus-manager');
                if (! container) return;

                const variantPrices = JSON.parse(container.dataset.variants || '{}');

                const interval = setInterval(() => {
                    const emitter = window.app?.config?.globalProperties?.$emitter;

                    if (! emitter) return;

                    clearInterval(interval);

                    emitter.on('configurable-variant-selected-event', (variant) => {
                        const wrapper = document.getElementById('omnibus-wrapper');
                        const variantId = variant ? variant.id : null;
                        const lowestPrice = variantId ? variantPrices[variantId] : null;

                        if (wrapper && lowestPrice) {
                            wrapper.innerHTML = `
                                <div class="omnibus-price-info mt-2 text-sm text-gray-500 flex items-center gap-1">
                                    <span>@lang('omnibus::app.shop.price-info')
                                        <strong class="font-medium text-gray-700">${lowestPrice}</strong>
                                    </span>
                                </div>`;
                            wrapper.style.display = 'block';
                        } else if (wrapper) {
                            wrapper.style.display = 'none';
                        }
                    });
                }, 100);
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initOmnibus);
            } else {
                initOmnibus();
            }
        })();
    </script>
@endpush

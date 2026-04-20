<div
    id="omnibus-manager"
    data-variants='@json($variantPrices)'
    class="omnibus-container"
>
    <div id="omnibus-wrapper">
        @include('shop::products.omnibus.default', ['formattedPrice' => $formattedPrice])
    </div>
</div>

@push('scripts')
    <script>
        (function () {
            function waitFor(test, callback, { interval = 100, timeout = 15000 } = {}) {
                const first = test();

                if (first) {
                    callback(first);

                    return;
                }

                const start = Date.now();

                const id = setInterval(() => {
                    const value = test();

                    if (value) {
                        clearInterval(id);

                        callback(value);

                        return;
                    }

                    if (Date.now() - start > timeout) {
                        clearInterval(id);
                    }
                }, interval);
            }

            function bind(container) {
                const variantPrices = JSON.parse(container.dataset.variants || '{}');

                waitFor(
                    () => window.app?.config?.globalProperties?.$emitter,
                    (emitter) => {
                        emitter.on('configurable-variant-selected-event', (variantId) => {
                            const wrapper = document.getElementById('omnibus-wrapper');

                            if (! wrapper) return;

                            const lowestPrice = variantId ? variantPrices[variantId] : null;

                            if (lowestPrice) {
                                wrapper.innerHTML = `
                                    <div class="omnibus-price-info mt-2 text-sm text-gray-500 flex items-center gap-1">
                                        <span>@lang('shop::app.products.omnibus.price-info')
                                            <strong class="font-medium text-gray-700">${lowestPrice}</strong>
                                        </span>
                                    </div>`;

                                wrapper.style.display = 'block';
                            } else {
                                wrapper.style.display = 'none';
                            }
                        });
                    }
                );
            }

            waitFor(
                () => document.getElementById('omnibus-manager'),
                bind
            );
        })();
    </script>
@endpush

@if ($product->type != 'configurable' && $product->totalQuantity() < 1 && $product->allow_preorder)
    <div class="preorder-info">

        @if (core()->getConfigData('preorder.settings.general.preorder_type') == 'partial')
            @if (core()->getConfigData('preorder.settings.general.percent'))
                <p style="font-weight: 600;">{{ __('preorder::app.shop.products.percent-to-pay', ['percent' => core()->getConfigData('preorder.settings.general.percent')]) }}</p>
            @else
                <p style="font-weight: 600;">{{ __('preorder::app.shop.products.nothing-to-pay') }}</p>
            @endif
        @endif

        @if (core()->getConfigData('preorder.settings.general.message') != '')
            <p>{{ core()->getConfigData('preorder.settings.general.message') }}</p>
        @endif

        @if ($product->preorder_availability && \Carbon\Carbon::parse($product->preorder_availability) > \Carbon\Carbon::now())
            <p>
                {!!
                    __('preorder::app.shop.products.available-on', [
                        'date' => core()->formatDate(\Carbon\Carbon::parse($product->preorder_availability), 'F d, Y')
                    ])
                !!}
            </p>
        @endif
    </div>
@elseif ($product->type == 'configurable')
    <div class="preorder-info" style="display: none">

        @if (core()->getConfigData('preorder.settings.general.preorder_type') == 'partial')
            @if (core()->getConfigData('preorder.settings.general.percent'))
                <p style="font-weight: 600;">{{ __('preorder::app.shop.products.percent-to-pay', ['percent' => core()->getConfigData('preorder.settings.general.percent')]) }}</p>
            @else
                <p style="font-weight: 600;">{{ __('preorder::app.shop.products.nothing-to-pay') }}</p>
            @endif
        @endif

        @if (core()->getConfigData('preorder.settings.general.message') != '')
            <p>{{ core()->getConfigData('preorder.settings.general.message') }}</p>
        @endif

        <p class="availability-text">
        </p>
    </div>

    @push('scripts')

        <script>
            var variants = @json(app('Webkul\SAASPreOrder\Helpers\Product')->getPreOrderVariants($product));

            eventBus.$on('configurable-variant-selected-event', function(variantId) {
                if (typeof variants[variantId] != "undefined") {
                    $('.preorder-info').show()

                    $('.add-to-buttons .addtocart').hide()

                    $('.add-to-buttons .buynow').hide()

                    $('.pre-order-btn').show()

                    $('.preorder-info .availability-text').html(variants[variantId]['availability_text'])
                } else {
                    $('.preorder-info').hide()

                    $('.add-to-buttons .addtocart').show()

                    $('.add-to-buttons .buynow').show()

                    $('.pre-order-btn').hide()
                }
            });

        </script>

    @endpush
@endif

@push('css')
    <style>
        .preorder-info {
            border: 1px solid #d3d3d3;
            border-left: 4px solid #0031f0;
            padding: 0 15px;
        }

        .preorder-info p {
            -webkit-font-smoothing: auto;
        }

        .preorder-info p span {
            font-weight: 600;
        }
    </style>
@endpush
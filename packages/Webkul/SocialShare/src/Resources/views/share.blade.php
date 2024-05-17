@if (core()->getConfigData('catalog.products.social_share.enabled'))
    @php
        $message = core()->getConfigData('catalog.products.social_share.share_message');
    @endphp

    <div class="flex gap-6">
        {!! view_render_event('bagisto.shop.products.view.share.before', ['product' => $product]) !!}

        <div class="hidden gap-2.5 justify-center items-center max-md:flex cursor-pointer">
            <span class="icon-share text-2xl"></span>

            <span onclick="shareProduct()">
                @lang('admin::app.configuration.index.catalog.products.social-share.share')
            </span>
        </div>

        <div class="max-md:hidden">
            <ul class="flex gap-3">
                @foreach(['facebook', 'twitter', 'instagram', 'pinterest', 'linkedin', 'whatsapp', 'email'] as $social)
                    @if (! core()->getConfigData('catalog.products.social_share.' . $social))
                        @continue
                    @endif

                    @include('social_share::links.' . $social , compact('product', 'message'))
                @endforeach
            </ul>
        </div>

        {!! view_render_event('bagisto.shop.products.view.share.after', ['product' => $product]) !!}
    </div>

    @push('scripts')
        <script>
            function shareProduct() {
                let productName = "{{ $product->name }}";
                let productUrl = "{{ route('shop.product_or_category.index', [$product->url_key]) }}";

                if (navigator.share) {
                    navigator.share({
                        title: productName,
                        text: productName + ' ' + productUrl,
                        url: productUrl
                    })
                    .catch((error) => console.error('Error sharing:', error));
                } else {
                    alert('Your browser does not support sharing.');
                }
            }
        </script>    
    @endpush
@endif

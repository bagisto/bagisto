@php
    $productBaseImage = product_image()->getProductBaseImage($product);
    $image = $productBaseImage['medium_image_url'] ?: asset('vendor/webkul/ui/assets/images/product/meduim-product-placeholder.png');
    $url = route('shop.productOrCategory.index', $product->url_key);
    $pinterest_url = 'https://pinterest.com/pin/create/button/?' . http_build_query([
        'url' => $url,
        'media' => $image,
        'description' => $message,
    ]);
@endphp

<pinterest-share></pinterest-share>

@push('scripts')
    <script type="text/x-template" id="pinterest-share-link">
        <li class="bb-social-share__item bb-social--pinterest">
            <a href="#" @click="openSharePopup">
                @include('social_share::icons.pinterest')
            </a>
        </li>
    </script>

    <script type="text/javascript">
        Vue.component('pinterest-share', {
            template: '#pinterest-share-link',
            data: function () {
                return {
                    shareUrl: '{{ $pinterest_url }}'
                }
            },
            methods: {
                openSharePopup: function () {
                    window.open(this.shareUrl, '_blank', 'resizable=yes,top=500,left=500,width=500,height=500')
                }
            }
        });
    </script>
@endpush

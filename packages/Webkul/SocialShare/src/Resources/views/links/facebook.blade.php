@php
    $url = urlencode(route('shop.product_or_category.index', $product->url_key));

    $facebookURL = 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
@endphp

<v-facebook-share></v-facebook-share>

@push('scripts')
    <script
        type="text/x-template"
        id="v-facebook-share-template"
    >
        <li class="transition-all hover:opacity-[0.8]">
            <a 
                href="#"
                @click="openSharePopup"
                aria-label="Facebook"
                role="button"
                tabindex="0"
            >
                @include('social_share::icons.facebook')
            </a>
        </li>
    </script>

    <script type="module">
        app.component('v-facebook-share', {
            template: '#v-facebook-share-template',

            data: function () {
                return {
                    shareUrl: '{{ $facebookURL }}'
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

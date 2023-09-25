@php
    $twitterURL = 'https://twitter.com/intent/tweet?' . http_build_query([
        'url'  => route('shop.product_or_category.index', $product->url_key),
        'text' => $message,
    ]);
@endphp

<v-twitter-share></v-twitter-share>

@push('scripts')
    <script type="text/x-template" id="v-twitter-share-template">
        <li class="transition-all hover:opacity-[0.8]">
            <a 
                href="#" 
                @click="openSharePopup"
                aria-label="Twitter"
            >
                @include('social_share::icons.twitter')
            </a>
        </li>
    </script>

    <script type="module">
        app.component('v-twitter-share', {
            template: '#v-twitter-share-template',

            data() {
                return {
                    shareUrl: '{{ $twitterURL }}'
                }
            },

            methods: {
                openSharePopup() {
                    window.open(this.shareUrl, '_blank', 'resizable=yes,top=500,left=500,width=500,height=500')
                }
            }
        });
    </script>
@endpush

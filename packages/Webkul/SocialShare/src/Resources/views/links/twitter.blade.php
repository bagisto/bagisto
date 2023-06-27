@php
    $twitter_url = 'https://twitter.com/intent/tweet?' . http_build_query([
        'url'  => route('shop.productOrCategory.index', $product->url_key),
        'text' => $message,
    ]);
@endphp

<twitter-share></twitter-share>

@push('scripts')
    <script type="text/x-template" id="twitter-share-link">
        <li class="bb-social-share__item bb-social--twitter">
            <a 
                href="#" 
                @click="openSharePopup"
            >
                @include('social_share::icons.twitter')
            </a>
        </li>
    </script>

    <script type="module">
        app.component('twitter-share', {
            template: '#twitter-share-link',

            data: function () {
                return {
                    shareUrl: '{{ $twitter_url }}'
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

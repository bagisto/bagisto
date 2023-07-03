@php
    $text = [
        'text' => $message . ' ' . route('shop.productOrCategory.index', $product->url_key),
    ];

    $whatsapp_url = 'whatsapp://send?' . http_build_query($text);
@endphp

<v-whatsapp-share></v-whatsapp-share>

@push('scripts')
    <script type="text/x-template" id="whatsapp-share-link">
        <li class="bb-social-share__item bb-social--whatsapp">
            <a 
                :href="shareUrl" 
                data-action="share/whatsapp/share" 
                target="_blank"
            >
                @include('social_share::icons.whatsapp')
            </a>
        </li>
    </script>

    <script type="module">
        app.component('v-whatsapp-share', {
            template: '#v-whatsapp-share-template',

            data: function () {
                return {
                    shareUrl: '{{ $whatsapp_url }}'
                }
            },
        });
    </script>
@endpush
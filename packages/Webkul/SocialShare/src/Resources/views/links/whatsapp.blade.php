@php
    $text = [
        'text' => $message . ' ' . route('shop.product_or_category.index', $product->url_key),
    ];

    $whatsappURL = 'whatsapp://send?' . http_build_query($text);
@endphp

<v-whatsapp-share></v-whatsapp-share>

@push('scripts')
    <script type="text/x-template" id="v-whatsapp-share-template">
        <li class="transition-all hover:opacity-[0.8]">
            <a 
                :href="shareUrl" 
                data-action="share/whatsapp/share" 
                target="_blank"
                aria-label="Whatsapp"
            >
                @include('social_share::icons.whatsapp')
            </a>
        </li>
    </script>

    <script type="module">
        app.component('v-whatsapp-share', {
            template: '#v-whatsapp-share-template',

            data() {
                return {
                    shareUrl: '{{ $whatsappURL }}'
                }
            },
        });
    </script>
@endpush
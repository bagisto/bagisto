@php
    $detailes = [
        'mini'    => 'true',
        'url'     => route('shop.product_or_category.index', $product->url_key),
        'title'   => $product->name,
        'summary' => $message
    ];

    $linkedinURL = 'https://www.linkedin.com/shareArticle?' . http_build_query($detailes);
@endphp

<v-linkedin-share></v-linkedin-share>

@push('scripts')
    <script
        type="text/x-template"
        id="v-linkedin-share-template"
    >
        <li class="transition-all hover:opacity-[0.8]">
            <a 
                :href="shareUrl" 
                @click="openSharePopup"
                aria-label="Linkedin"
                role="button"
                tabindex="0"
            >
                @include('social_share::icons.linkedin')
            </a>
        </li>
    </script>

    <script type="module">
        app.component('v-linkedin-share', {
            template: '#v-linkedin-share-template',

            data() {
                return {
                    shareUrl: '{{ $linkedinURL }}'
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

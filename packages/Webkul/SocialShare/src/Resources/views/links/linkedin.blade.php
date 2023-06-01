@php
    $detailes = [
        'mini'    => 'true',
        'url'     => route('shop.productOrCategory.index', $product->url_key),
        'title'   => $product->name,
        'summary' => $message
    ];

    $linkedin_url = 'https://www.linkedin.com/shareArticle?' . http_build_query($detailes);
@endphp

<linkedin-share></linkedin-share>

@push('scripts')
    <script type="text/x-template" id="linkedin-share-link">
        <li class="bb-social-share__item bb-social--linkedin">
            
            <a 
                href="#" 
                @click="openSharePopup"
            >
                @include('social_share::icons.linkedin')
            </a>
        </li>
    </script>

    <script type="text/javascript">
        Vue.component('linkedin-share', {
            template: '#linkedin-share-link',

            data: function () {
                return {
                    shareUrl: '{{ $linkedin_url }}'
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
@php
    /**
     * @var Webkul\Product\Models\ProductFlat $product
     * @var string $message
     */
    $url = route('shop.productOrCategory.index', $product->url_key);

    if (empty($message)) {
        $message = $product->name;
    }

    $email_url = 'mailto:your@email.com?subject=' . $message . '&body=' . $message . ' ' . $url;
@endphp

<email-share></email-share>

@push('scripts')
    <script type="text/x-template" id="email-share-link">
        <li class="bb-social-share__item bb-social--email">
            <a href="{{ $email_url }}" target="_blank">
                @include('social_share::icons.email')
            </a>
        </li>
    </script>

    <script type="text/javascript">
        Vue.component('email-share', {
            template: '#email-share-link'
        });
    </script>
@endpush
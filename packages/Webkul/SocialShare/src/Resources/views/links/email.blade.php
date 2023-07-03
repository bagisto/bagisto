@php
    $url = route('shop.productOrCategory.index', $product->url_key);

    $message = empty($message) ? $product->name : $message;

    $email_url = 'mailto:your@email.com?subject=' . $message . '&body=' . $message . ' ' . $url;
@endphp

<v-email-share></v-email-share>

@push('scripts')
    <script type="text/x-template" id="v-email-share-template">
        <li class="bb-social-share__item bb-social--email">
            <a 
                href="{{ $email_url }}" 
                target="_blank"
            >
                @include('social_share::icons.email')
            </a>
        </li>
    </script>

    <script type="module">
        app.component('v-email-share', {
            template: '#v-email-share-template'
        });
    </script>
@endpush
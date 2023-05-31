@php
    $links = [];

    core()->getConfigData('catalog.products.social_share.facebook') ? array_push($links, 'facebook') : '' ;

    core()->getConfigData('catalog.products.social_share.instagram') ? array_push($links, 'instagram') : '' ;

    core()->getConfigData('catalog.products.social_share.twitter') ? array_push($links, 'twitter') : '' ;

    core()->getConfigData('catalog.products.social_share.pinterest') ? array_push($links, 'pinterest') : '' ;

    core()->getConfigData('catalog.products.social_share.linkedin') ? array_push($links, 'linkedin') : '' ;

    core()->getConfigData('catalog.products.social_share.whatsapp') ? array_push($links, 'whatsapp') : '' ;

    core()->getConfigData('catalog.products.social_share.email') ? array_push($links, 'email') : '' ;

    $message = core()->getConfigData('catalog.products.social_share.share_message');
@endphp

@if (core()->getConfigData('catalog.products.social_share.enabled'))
    <div class="bb-social-share">
        <label class="bb-social-share__title">
            @lang('SocialShare::app.share-now')
        </label>

        <ul class="bb-social-share__items">
            @foreach($links as $link)
                @include('social_share::links.' . $link, compact('product', 'message'))
            @endforeach
        </ul>
    </div>
@endif

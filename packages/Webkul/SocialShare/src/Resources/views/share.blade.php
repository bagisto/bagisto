@php

    $links = [];

    $socials = [
        'facebook', 
        'twitter', 
        'instagram', 
        'pinterest', 
        'linkedin', 
        'whatsapp', 
        'email'
    ];

    foreach ($socials as $social) {
        $links[] = core()->getConfigData('catalog.products.social_share.' . $social) ? $social : '' ;
    }

    $message = core()->getConfigData('catalog.products.social_share.share_message');
@endphp

@if (core()->getConfigData('catalog.products.social_share.enabled'))
    <div class="bb-social-share">
        <ul class="bb-social-share__items" style="display: flex; column-gap:15px;">
            @foreach($links as $link)
                @if ($link)
                    @include('social_share::links.' . $link , compact('product', 'message'))
                @endif
            @endforeach
        </ul>
    </div>
@endif

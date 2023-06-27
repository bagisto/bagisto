@php

    $links = [];

    $socials = [
        'facebook', 
        'twitter', 
        'instagram', 
        'pintrest', 
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
        <label class="bb-social-share__title flex justify-center items-center gap-[10px] cursor-pointer">
            @lang('socialshare::app.share-now')
        </label>

        <ul class="bb-social-share__items">
            @foreach($links as $link)
                @if ($link)
                    @include('socialshare::links.' . $link , compact('product', 'message'))
                @endif
            @endforeach
        </ul>
    </div>
@endif

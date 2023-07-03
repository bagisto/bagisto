@php

    $links = [];

    $socials = [
        'enable_facebook',
        'enable_twitter',
        'enable_google',
        'enable_linkedin',
        'enable_github'
    ];

    foreach ($socials as $social) {
        $links[] = core()->getConfigData('customer.settings.social_login.' . $social) ? $social : '' ;
    }
@endphp

<div class="social-login-links" style="display:flex; column-gap:15px;">
    @foreach($links as $link)
        @php 
            $icon = explode("_",$link); 
        @endphp

        @if ($link)
            <div class="control-group">
                <a href="{{ route('customer.social-login.index', $icon[1]) }}" class="link facebook-link">
                    @include('social_login::icons.' . $icon[1])
                </a>
            </div>
        @endif
    @endforeach
</div>
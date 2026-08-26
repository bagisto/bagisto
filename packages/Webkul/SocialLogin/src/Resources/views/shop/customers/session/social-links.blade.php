<!-- Spaced here rather than by the form: this is injected through an event, so it cannot rely on whatever it lands under leaving room for it. -->
@php
    $providers = [
        'enable_facebook' => 'facebook',
        'enable_twitter'  => 'twitter',
        'enable_google'   => 'google',
        'enable_linkedin' => 'linkedin-openid',
        'enable_github'   => 'github',
    ];
@endphp

<div class="mt-6 flex gap-3">
    @foreach ($providers as $field => $provider)
        @if (! core()->getConfigData('customer.settings.social_login.'.$field))
            @continue
        @endif

        <a
            href="{{ route('customer.social-login.index', $provider) }}"
            class="transition-all hover:opacity-[0.8]"
            aria-label="{{ $provider }}"
        >
            @include('social_login::icons.'.$provider)
        </a>
    @endforeach
</div>

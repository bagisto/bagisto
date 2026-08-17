<!-- Spaced here rather than by the form: this is injected through an event, so it cannot rely on whatever it lands under leaving room for it. -->
<div class="mt-6 flex gap-3">
    @foreach(['enable_facebook', 'enable_twitter', 'enable_google', 'enable_linkedin-openid', 'enable_github'] as $social)
        @if (! core()->getConfigData('customer.settings.social_login.' . $social))
            @continue
        @endif

        @php 
            $icon = explode('_', $social); 
        @endphp

        <a
            href="{{ route('customer.social-login.index', $icon[1]) }}"
            class="transition-all hover:opacity-[0.8]"
            aria-label="{{ $icon[0] }}"
        >
            @include('social_login::icons.' . $icon[1])
        </a>
    @endforeach
</div>
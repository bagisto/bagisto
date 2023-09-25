<div class="flex gap-[15px]">
    @foreach(['enable_facebook', 'enable_twitter', 'enable_google', 'enable_linkedin', 'enable_github'] as $social)
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
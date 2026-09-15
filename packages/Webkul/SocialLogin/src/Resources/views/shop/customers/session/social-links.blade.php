<div class="mt-6 flex gap-3">
    @foreach (\Webkul\SocialLogin\Http\Controllers\LoginController::PROVIDERS as $provider => $field)
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

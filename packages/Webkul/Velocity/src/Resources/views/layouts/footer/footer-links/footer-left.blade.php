<div class="col-4 software-description">
    <div class="logo">
        <a href="{{ route('shop.home.index') }}">
            @if ($logo = core()->getCurrentChannel()->logo_url)
                <img
                    src="{{ $logo }}"
                    class="logo full-img" />
            @else
                <img
                    src="{{ asset('themes/velocity/assets/images/static/logo-text-white.png') }}"
                    class="logo full-img" />
            @endif

        </a>
    </div>

    {!! $velocityMetaData->footer_left_content !!}
</div>
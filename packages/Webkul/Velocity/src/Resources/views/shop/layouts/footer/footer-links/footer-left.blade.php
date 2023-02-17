<div class="col-lg-4 col-md-12 col-sm-12 software-description">
    <div class="logo">
        <a href="{{ route('shop.home.index') }}" aria-label="Logo">
            @if ($logo = core()->getCurrentChannel()->logo_url)
                <img
                    src="{{ $logo }}"
                    class="logo full-img" alt="" width="200" height="50" />
            @else
                <img
                    src="{{ asset('themes/velocity/assets/images/static/logo-text-white.png') }}"
                    class="logo full-img" alt="" width="200" height="50" />
            @endif
        </a>
    </div>

    @if ($velocityMetaData)
        {!! $velocityMetaData->footer_left_content !!}
    @else
        {!! __('velocity::app.admin.meta-data.footer-left-raw-content') !!}
    @endif
</div>